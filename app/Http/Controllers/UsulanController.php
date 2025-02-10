<?php

namespace App\Http\Controllers;

use App\Mail\emailOTP;
use App\Models\SnackcornerKategori;
use App\Models\SnackcornerKeranjang;
use App\Models\UsulanSnc;
use App\Models\Usulan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use setasign\Fpdi\Fpdi;
use Auth;
use Str;
use Illuminate\Support\Facades\Mail;

use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class UsulanController extends Controller
{
    public function index(Request $request, $id)
    {
        $form = $id;
        $role  = Auth::user()->level_id;
        $query = Usulan::orderBy('status_persetujuan', 'asc')->orderBy('status_proses', 'asc')->orderBy('tanggal_usulan', 'asc')
            ->join('t_form', 'id_form', 'form_id')
            ->join('users', 'id', 'user_id')
            ->join('t_pegawai', 'id_pegawai', 'pegawai_id')
            ->where('kategori', $id);

        if ($role == 4) {
            $query = $query->where('unit_kerja_id', Auth::user()->pegawai->unit_kerja_id)->get();
        } else {
            $query = $query->get();
        }

        $usulan = $query->count();

        $aksi    = $request->get('aksi');
        $uker    = $request->get('uker_id');
        $proses  = $request->get('proses');
        $tanggal = $request->get('tanggal');
        $bulan   = $request->get('bulan');
        $tahun   = $request->get('tahun');

        return view('pages.usulan.show', compact('form', 'usulan', 'aksi', 'uker', 'proses', 'tanggal', 'bulan', 'tahun'));
    }

    public function select($id)
    {
        $role  = Auth::user()->role_id;
        $query = Usulan::orderBy('status_persetujuan', 'asc')->orderBy('status_proses', 'asc')->orderBy('tanggal_usulan', 'asc')
            ->join('t_form', 'id_form', 'form_id')
            ->where('kategori', $id);

        if ($role == 4) {
            $query = $query->where('user_id', Auth::user()->id)->get();
        } else {
            $query = $query->get();
        }
    }

    public function store(Request $request)
    {
        if ($request->form_id == 601 && !$request->id_snc) {
            return redirect()->route('snaco.dashboard')->with('failed', 'Anda belum memilih barang');
        }

        // USULAN
        $id = Usulan::withTrashed()->count() + 1;
        $usulan  = new Usulan();
        $usulan->id_usulan      = $id;
        $usulan->kode_usulan    = Str::random(5);
        $usulan->user_id        = Auth::user()->id;
        $usulan->form_id        = $request->form_id;
        $usulan->tanggal_usulan = Carbon::now();
        $usulan->keterangan     = $request->keterangan;
        $usulan->otp_1          = rand(111111, 999999);
        $usulan->created_at     = Carbon::now();
        $usulan->save();

        // USULAN SNACK CORNER
        if ($request->form_id == 601) {
            $snc = $request->id_snc;
            foreach ($snc as $i => $snc_id) {
                $id_snc = UsulanSnc::withTrashed()->count() + 1;
                $detail = new UsulanSnc();
                $detail->id_usulan_snc          = $id_snc;
                $detail->usulan_id              = $id;
                $detail->snc_id                 = $snc_id;
                $detail->jumlah_permintaan      = $request->jumlah[$i];
                $detail->keterangan_permintaan  = $request->keterangan_permintaan[$i];
                $detail->created_at             = Carbon::now();
                $detail->save();

                SnackcornerKeranjang::where('snc_id', $snc_id)->delete();
            }

            return redirect()->route('snaco.detail', $id)->with('success', 'Berhasil Membuat Usulan');
        }
    }

    public function edit($id)
    {
        $data = Usulan::where('id_usulan', $id)->first();
        $form = $data->form->kategori;

        if ($form == 'SNC') {
            $kategori = SnackcornerKategori::orderBy('nama_kategori', 'ASC')->get();
            return view('pages.snackcorner.edit', compact('kategori', 'data'));
        }
    }

    public function delete($id)
    {
        $data = Usulan::with('form')->where('id_usulan', $id)->first();

        if ($data) {
            if ($data->form->kategori == 'SNC') {
                UsulanSnc::where('usulan_id', $data->id_usulan)->delete();
                Usulan::where('id_usulan', $id)->delete();
                return redirect()->route('usulan.show', 'snc')->with('success', 'Berhasil Menghapus');
            }
        } else {
            return back()->with('failed', 'Data tidak ditemukan');
        }
    }


    // ===============================================
    //                   VERIFIKASI
    // ===============================================

    public function verif(Request $request, $id)
    {
        $cekData = Usulan::where('id_usulan', $id)->first();
        if (!$request->all() && $cekData->status_persetujuan) {
            return redirect()->route('snaco.detail', $id)->with('failed', 'Permintaan tidak dapat di proses');
        }

        if (!$request->all()) {
            $data = Usulan::where('id_usulan', $id)->first();
            return view('pages.snackcorner.verif', compact('id', 'data'));
        } else {
            $data = Usulan::with('form', 'user.pegawai.uker')->where('id_usulan', $id)->first();

            $otp3 = rand(111111, 999999);
            $tokenMail = Str::random(32);
            // $logMail = new LogMail();
            // $logMail->token   = $tokenMail;
            // $logMail->save();

            $dataMail = [
                'token' => $tokenMail,
                'nama'  => $data->user->pegawai->nama_pegawai,
                'uker'  => $data->user->pegawai->uker->unit_kerja,
                'otp'   => $otp3
            ];

            Mail::to($data->user->email)->send(new emailOTP($dataMail));

            $klasifikasi = $data->form->klasifikasi;
            $kodeSurat   = $data->user->pegawai->uker->kode_surat;
            $nomorSurat  = Usulan::whereHas('user.pegawai', function ($query) use ($data) {
                $query->where('status_persetujuan', 'true')->where('uker_id', $data->user->pegawai->unit_kerja_id)->whereYear('tanggal_usulan', Carbon::now()->format('Y'));
            })->count() + 1;
            $tahunSurat  = Carbon::now()->format('Y');

            $format = $klasifikasi . '/' . $kodeSurat . '/' . $nomorSurat . '/' . $tahunSurat;

            Usulan::where('id_usulan', $id)->update([
                'verif_id'           => Auth::user()->pegawai_id,
                'nomor_usulan'       => $request->persetujuan == 'true' ? $format : null,
                'status_persetujuan' => $request->persetujuan,
                'status_proses'      => $request->persetujuan == 'true' ? 'proses' : null,
                'keterangan_tolak'   => $request->alasan_penolakan ?? null,
                'tanggal_ambil'      => $request->tanggal_ambil ?? null,
                'otp_2'              => $request->persetujuan == 'true' ? rand(111111, 999999) : null,
                'otp_3'              => $otp3,
            ]);
            return redirect()->route('snaco.detail', $id)->with('success', 'Berhasil Melakukan Verifikasi');
        }
    }

    public function surat($id)
    {
        $query    = Usulan::where('id_usulan', $id)->first();
        $utama    = $query->user->pegawai->uker->utama_id;
        $template = public_path('dist/format/format-' . $utama . '.pdf');
        $kategori = $query->form->nama_form;

        $qrPengusul = $query->user->pegawai->nip . " | " . $query->user->pegawai->nama_pegawai
            . " | " . $query->user->pegawai->jabatan->jabatan . " " . $query->user->pegawai->timker?->tim_kerja
            . " | " . $query->user->pegawai->uker->unit_kerja;

        $qrVerif    = $query->verif?->nip . " | " . $query->verif?->nama_pegawai
            . " | " . $query->verif?->jabatan->jabatan . " " . $query->verif?->timker?->tim_kerja
            . " | " . $query->verif?->uker->unit_kerja;

        $qrPenerima = $query->nama_penerima . " | STAFF | " . $query->user->pegawai->uker->unit_kerja;
        $qrPetugas  = "PETUGAS GUDANG | STAFF | BIRO UMUM";

        $qrData = [
            'pengusul'    => $qrPengusul,
            'verifikator' => $qrVerif,
            'penerima'    => $qrPenerima,
            'petugas'     => $qrPetugas
        ];

        $qrDataJson = json_encode($qrData);

        $renderer   = new ImageRenderer(
            new RendererStyle(400),
            new ImagickImageBackEnd()
        );

        $writer = new Writer($renderer);
        $filePathPengusul = public_path('dist/qrcode/ttd_pengusul.png');
        $writer->writeFile($qrData['pengusul'], $filePathPengusul);

        // Simpan QR Code Verifikator
        $filePathVerif = public_path('dist/qrcode/ttd_verifikator.png');
        $writer->writeFile($qrData['verifikator'], $filePathVerif);

        // Simpan QR Code Verifikator
        $filePathPenerima = public_path('dist/qrcode/ttd_penerima.png');
        $writer->writeFile($qrData['penerima'], $filePathPenerima);

        // Simpan QR Code Verifikator
        $filePathPetugas = public_path('dist/qrcode/ttd_petugas.png');
        $writer->writeFile($qrData['petugas'], $filePathPetugas);

        $data = [
            'nomor_naskah'   => 'Nomor : ' . $query->nomor_usulan,
            'tanggal_naskah' => Carbon::parse($query->tanggal_usulan)->isoFormat('DD MMMM Y'),
            'hal'            => $kategori,
            'deskripsi'      => 'Bersama dengan surat ini, kami ' . $query->user->pegawai->uker->unit_kerja . ' bermaksud mengajukan permohonan, perihal ' . $query->keterangan . '. Dengan rincian permintaan sebagai berikut: ',
            'closing'        => 'Atas perhatian dan kerjasama, kami ucapkan terima kasih.',

            'verifikator'         => 'Disetujui oleh,',
            'jabatan_verifikator' => $query->verif?->jabatan->jabatan . " " . $query->verif?->timker?->tim_kerja,
            'ttd_verifikator'     => asset('dist/qrcode/ttd_verifikator.png'),
            'nama_verifikator'    => $query->verif?->nama_pegawai,

            'pengusul'         => 'Yang mengusulkan,',
            'jabatan_pengusul' => $query->user->pegawai->jabatan->jabatan . " " . $query->user->pegawai->timker?->tim_kerja,
            'ttd_pengusul'     => asset('dist/qrcode/ttd_pengusul.png'),
            'nama_pengusul'    => $query->user->pegawai->nama_pegawai,

            'serahterima'      => 'Berdasarkan permintaan di atas, telah dilakukan serah terima kebutuhan Snack Corner pada tanggal ' . Carbon::parse($query->tanggal_ambil)->isoFormat('DD MMMM Y'),

            'penerima'         => 'Diterima oleh,',
            'jabatan_penerima' => 'Staf '. $query->user->pegawai->uker->unit_kerja,
            'ttd_pengusul'     => asset('dist/qrcode/ttd_penerima.png'),
            'nama_penerima'    => $query->nama_penerima,

            'petugas'         => 'Diserahkan oleh,',
            'jabatan_petugas' => 'Petugas Gudang',
            'ttd_petugas'     => asset('dist/qrcode/ttd_petugas.png'),
            'nama_petugas'    => 'Nidia Mahayani',
        ];

        $pdf = new FPDI();

        $pageCount = $pdf->setSourceFile($template);

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $pdf->importPage($pageNo);
            $pdf->addPage();
            $pdf->useTemplate($templateId);

            if ($pageNo == 1) {
                $pdf->SetFont('Arial', '', 11);
                $pdf->SetTextColor(0, 0, 0);

                $pdf->SetXY(80, 70);
                // $pdf->Write(0, $data['nomor_naskah']);
                $pdf->Write(0, $data['nomor_naskah']);

                $pdf->SetXY(24, 80);
                $deskripsi = "      " . $data['deskripsi'];
                $pdf->MultiCell(163, 6, $deskripsi);

                $posisiYDeskripsi = $pdf->GetY();
                $pdf->SetXY(26, $posisiYDeskripsi + 5);
                $tabelData = [
                    ['No.', 'Barang', 'Jumlah', 'Satuan'],
                ];

                $lebarKolom = [10, 90, 30, 30];
                $tinggiBaris = 6;

                $pdf->SetFont('Arial', 'B', 10);
                foreach ($tabelData[0] as $index => $header) {
                    $pdf->Cell($lebarKolom[$index], $tinggiBaris, $header, 1, 0, 'C');
                }
                $pdf->Ln();

                function wrapTextByCharacter($text, $maxLength)
                {
                    $wrappedText = '';
                    while (strlen($text) > $maxLength) {
                        $wrappedText .= substr($text, 0, $maxLength) . "\n";
                        $text = substr($text, $maxLength);
                    }
                    $wrappedText .= $text;
                    return $wrappedText;
                }

                $maxLength = 30;
                $pdf->SetFont('Arial', '', 9);
                $no = 1;
                foreach ($query->usulanSnc as $row) {
                    $barangNama = wrapTextByCharacter($row->snc->snc_nama, $maxLength);

                    $posisiYHeader = $pdf->GetY();
                    $pdf->SetXY(26 + $lebarKolom[0], $posisiYHeader);
                    $pdf->MultiCell($lebarKolom[1], $tinggiBaris, $barangNama . ' ' . $row->snc->snc_deskripsi, 1, 'L');
                    $finalY = $pdf->GetY();
                    $rowHeight = $finalY - $posisiYHeader;

                    $pdf->SetXY(26, $posisiYHeader);
                    $pdf->Cell($lebarKolom[0], $rowHeight, $no++, 1, 0, 'C');
                    $pdf->SetXY(26 + $lebarKolom[0] + $lebarKolom[1], $posisiYHeader);

                    $pdf->Cell($lebarKolom[2], $rowHeight, $row->jumlah_permintaan, 1, 0, 'C');
                    $pdf->Cell($lebarKolom[3], $rowHeight, $row->snc->satuan->satuan, 1, 0, 'C');
                    $pdf->SetY($finalY);
                }

                $pdf->SetFont('Arial', '', 11);
                $posisiYTable = $pdf->GetY();
                $pdf->SetXY(24, $posisiYTable + 5);
                $closing = "      " . $data['closing'];
                $pdf->MultiCell(170, 6, $closing);

                $lebarKolom = 70;
                $tinggiBaris = 6;

                $posisiX = $pdf->GetPageWidth() - $lebarKolom - 20;

                $posisiY = $pdf->GetY();
                $pdf->SetXY(120, $posisiY + 10);
                $pdf->Write(0, $data['tanggal_naskah']);

                // ====================== TTD PENYETUJU =============================

                $posisiYClosing = $pdf->GetY();
                if ($query->status_persetujuan == 'true') {
                    $pdf->SetXY(25, $posisiYClosing + 5);
                    $pdf->Write(0, $data['verifikator']);

                    $pdf->SetXY(25, $posisiYClosing + 10);
                    $pdf->Write(0, $data['jabatan_verifikator']);
                }

                $pdf->SetXY(120, $posisiYClosing + 5);
                $pdf->Write(0, $data['pengusul']);

                $posisiYClosing = $pdf->GetY();
                $pdf->SetXY(120, $posisiYClosing + 5);
                $pdf->Write(0, $data['jabatan_pengusul']);

                $posisiYPengusul = $pdf->GetY();
                if ($query->status_persetujuan == 'true') {
                    $pdf->SetXY(25, $posisiYPengusul + 15);
                    $filePathQrCode = public_path('dist/qrcode/ttd_verifikator.png');
                    $jpegFilePath = $this->convertPngToJpeg($filePathQrCode);
                    $pdf->Image($jpegFilePath, 25, $posisiYPengusul + 3, 22, 22);
                }

                $pdf->SetXY(25, $posisiYPengusul + 15);
                $filePathQrCode = public_path('dist/qrcode/ttd_pengusul.png');
                $jpegFilePath = $this->convertPngToJpeg($filePathQrCode);
                $pdf->Image($jpegFilePath, 120, $posisiYPengusul + 3, 22, 22);

                $posisiYNamaPengusul = $pdf->GetY();
                $pdf->SetXY(25, $posisiYNamaPengusul + 15);
                $pdf->Write(0, $data['nama_verifikator']);

                $posisiYNamaPengusul = $pdf->GetY();
                $pdf->SetXY(120, $posisiYPengusul + 30);
                $pdf->Write(0, $data['nama_pengusul']);

                if ($query->status_proses == 'selesai') {
                    $posisiSerahTerima = $pdf->GetY();
                    $pdf->SetXY(24, $posisiYNamaPengusul + 5);
                    $serahterima = "      " . $data['serahterima'];
                    $pdf->MultiCell(163, 6, $serahterima);

                    $posisiYPenerima = $pdf->GetY();
                    $pdf->SetXY(25, $posisiYPenerima + 5);
                    $pdf->Write(0, $data['penerima']);

                    $pdf->SetXY(120, $posisiYPenerima + 5);
                    $pdf->Write(0, $data['petugas']);

                    $posisiYJabatanPenerima = $pdf->GetY();
                    $pdf->SetXY(25, $posisiYJabatanPenerima + 5);
                    $pdf->Write(0, $data['jabatan_penerima']);

                    $pdf->SetXY(120, $posisiYJabatanPenerima + 5);
                    $pdf->Write(0, $data['jabatan_petugas']);

                    $posisiYQrCodePenerima = $pdf->GetY();
                    $pdf->SetXY(25, $posisiYQrCodePenerima + 15);
                    $filePathQrCode = public_path('dist/qrcode/ttd_penerima.png');
                    $jpegFilePath = $this->convertPngToJpeg($filePathQrCode);
                    $pdf->Image($jpegFilePath, 25, $posisiYQrCodePenerima + 3, 22, 22);

                    $pdf->SetXY(120, $posisiYQrCodePenerima + 15);
                    $filePathQrCode = public_path('dist/qrcode/ttd_petugas.png');
                    $jpegFilePath = $this->convertPngToJpeg($filePathQrCode);
                    $pdf->Image($jpegFilePath, 120, $posisiYQrCodePenerima + 3, 22, 22);


                    $posisiYNamaPenerima = $pdf->GetY();
                    $pdf->SetXY(25, $posisiYNamaPenerima + 15);
                    $pdf->Write(0, $data['nama_penerima']);

                    $pdf->SetXY(120, $posisiYNamaPenerima + 15);
                    $pdf->Write(0, $data['nama_petugas']);
                }

                $posisiYNotes = $pdf->GetY();
                $pdf->SetXY(120, $posisiYNotes + 50);
                $filePathNoted = public_path('dist/img/noted-surat.png');
                $pdf->Image($filePathNoted, 20, $posisiYNotes + 10, 170, 16);
            }
        }

        $time = time();
        $outputPath = public_path("dist/format/{$id}{$time}SuratPengajuan.pdf");
        $pdf->Output($outputPath, 'F');

        return response()->download($outputPath)->deleteFileAfterSend(true);
    }

    public function convertPngToJpeg($filePath)
    {
        $image = imagecreatefrompng($filePath);

        $jpegPath = str_replace('.png', '.jpg', $filePath);

        imagejpeg($image, $jpegPath);

        imagedestroy($image);

        return $jpegPath;
    }

    // ======================= BARANG =============================

    public function itemShow()
    {
        $role = Auth::user()->role_id;
        $uker = Auth::user()->pegawai->uker_id;
        $data = UsulanSnc::join('t_usulan','id_usulan','usulan_id')->with('usulan.user.pegawai');

        if ($role == 4) {
            $barang = $data->whereHas('usulan.user.pegawai', function ($query) use ($uker) {
                $query->where('uker_id', $uker);
            })->count();
        } else {
            $barang = $data->count();
        }

        return view('pages.usulan.barang', compact('barang'));
    }

    public function itemSelectAll()
    {
        $uker = Auth::user()->pegawai->uker_id;
        $role = Auth::user()->role_id;

        $data = UsulanSnc::join('t_usulan','id_usulan','usulan_id')->with('usulan.user.pegawai');
        $no       = 1;
        $response = [];

        if ($role == 4) {
            $result = $data->whereHas('usulan.user.pegawai', function ($query) use ($uker) {
                $query->where('uker_id', $uker);
            })->get();
        } else {
            $result = $data->get();
        }

        foreach ($result as $row) {
            $aksi   = '';
            $status = '';

            if ($row->snc->snc_foto) {
                $foto = '<img src="' . asset('storage/file/foto_snaco/' . $row->snc->snc_foto) . '" class="img-fluid" alt="">';
            } else {
                $foto = '<img src="https://cdn-icons-png.flaticon.com/512/679/679821.png" class="img-fluid" alt="">';
            }


            $response[] = [
                'no'         => $no,
                'id'         => $row->snc->id_snc,
                'kode'       => $row->usulan->kode_usulan,
                'foto'       => $foto,
                'fileFoto'   => $row->snc->snc_foto,
                'kategori'   => $row->snc->kategori->nama_kategori,
                'barang'     => $row->snc->snc_nama,
                'deskripsi'  => $row->snc->snc_deskripsi ?? '',
                'harga'      => 'Rp' . number_format($row->snc->snc_harga, 0, '.'),
                'satuan'     => $row->snc->satuan->satuan,
                'maksimal'   => $row->snc->snc_maksimal,
                'jumlah'     => $row->jumlah_permintaan,
                'keterangan' => $row->snc->snc_keterangan ?? ''
            ];

            $no++;
        }

        return response()->json($response);
    }
}
