<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\SnackcornerController;
use App\Http\Controllers\SnackcornerKategoriController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\UkerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsulanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('email', [AuthController::class, 'email'])->name('email');
Route::POST('email/update', [AuthController::class, 'email'])->name('email.update');

Route::post('login-post', [AuthController::class, 'post'])->name('login');


Route::group(['middleware' => 'auth'], function () {

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('user/profil/{id}', [UserController::class, 'profil'])->name('user.profil');
    Route::get('user/profil/edit/{id}', [UserController::class, 'profilEdit'])->name('user.profil.edit');

    // Akses Monitor
    Route::group(['middleware' => ['access:monitor']], function () {

        // Snack Corner
        Route::group(['prefix' => 'snaco', 'as' => 'snaco.'], function () {
            Route::get('laporan', [LaporanController::class, 'snaco'])->name('report');
            Route::get('laporan/chart/{bulan}/{tahun}', [LaporanController::class, 'snacoChart'])->name('report.chart');
            Route::get('laporan/stok', [LaporanController::class, 'snacoStok'])->name('report.stok');

        });

        // Jenis Barang
        Route::group(['prefix' => 'jenis-snaco', 'as' => 'jenis-snaco.', 'middleware' => 'access:monitor'], function () {
            Route::get('daftar', [SnackcornerKategoriController::class, 'show'])->name('show');
            Route::get('tambah', [SnackcornerKategoriController::class, 'create'])->name('create')->middleware('access:admin');
            Route::get('detail/{id}', [SnackcornerKategoriController::class, 'detail'])->name('detail');
            Route::get('hapus/{id}', [SnackcornerKategoriController::class, 'delete'])->name('delete');
            Route::get('edit/{id}', [SnackcornerKategoriController::class, 'edit'])->name('edit')->middleware('access:admin');
            Route::post('proses-edit/{id}', [SnackcornerKategoriController::class, 'update'])->name('update');
            Route::post('proses-tambah', [SnackcornerKategoriController::class, 'post'])->name('store');
        });
    });

    // Akses Super Admin
    Route::group(['middleware' => ['access:master']], function () {
        // User
        Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
            Route::get('daftar', [UserController::class, 'show'])->name('show');
            Route::get('tambah', [UserController::class, 'create'])->name('create');
            Route::get('detail/{id}', [UserController::class, 'detail'])->name('detail');
            Route::get('hapus/{id}', [UserController::class, 'delete'])->name('delete');
            Route::get('edit/{id}', [UserController::class, 'edit'])->name('edit');
            Route::post('proses-edit/{id}', [UserController::class, 'update'])->name('update');
            Route::post('proses-tambah', [UserController::class, 'post'])->name('store');
        });

        // Pegawai
        Route::group(['prefix' => 'pegawai', 'as' => 'pegawai.'], function () {
            Route::get('selectUker/{id}', [PegawaiController::class, 'selectByUker']);
            Route::get('daftar', [PegawaiController::class, 'show'])->name('show');
            Route::get('edit/{id}', [PegawaiController::class, 'edit'])->name('edit');
            Route::post('tambah', [PegawaiController::class, 'post'])->name('store');
            Route::post('update/{id}', [PegawaiController::class, 'update'])->name('update');
        });

        // Unit Kerja
        Route::group(['prefix' => 'unit-kerja', 'as' => 'unit-kerja.'], function () {
            Route::get('select', [UkerController::class, 'select'])->name('select');
            Route::get('selectUtama/{id}', [UkerController::class, 'selectByUtama']);
            Route::get('daftar', [UkerController::class, 'show'])->name('show');
            Route::post('proses-edit/{id}', [UkerController::class, 'update'])->name('update');
        });
    });

    Route::group(['prefix' => 'usulan', 'as' => 'usulan.'], function () {
        Route::get('daftars/{id}', [UsulanController::class, 'index'])->name('show');
        Route::get('daftar/barang', [UsulanController::class, 'itemShow'])->name('show.item');
        Route::get('daftar/barang/selectAll', [UsulanController::class, 'itemSelectAll'])->name('show.item.selectAll');

        Route::get('resend-token/{id}', [UsulanController::class, 'resendToken'])->name('resendToken');

        Route::get('edit/{id}', [UsulanController::class, 'edit'])->name('edit');
        Route::get('surat/{id}', [UsulanController::class, 'surat'])->name('surat');
        Route::get('verifikasi/{id}', [UsulanController::class, 'verif'])->name('verif')->middleware('admVerif');

        Route::get('delete/{id}', [UsulanController::class, 'delete'])->name('delete');
        Route::post('tambah', [UsulanController::class, 'store'])->name('store');
        Route::post('update/{id}', [UsulanController::class, 'update'])->name('update');
    });

    Route::group(['prefix' => 'kegiatan', 'as' => 'kegiatan.'], function () {
        Route::get('daftar', [KegiatanController::class, 'show'])->name('show');
        Route::get('select-daftar', [KegiatanController::class, 'select'])->name('select');
        Route::get('daftar/barang', [KegiatanController::class, 'item'])->name('show.item');
        Route::get('daftar/barang/select', [KegiatanController::class, 'itemSelect'])->name('show.item.select');
        Route::get('tambah', [KegiatanController::class, 'create'])->name('create');
        Route::get('detail/{id}', [KegiatanController::class, 'detail'])->name('detail');
        Route::get('edit/{id}', [KegiatanController::class, 'edit'])->name('edit');
        Route::get('hapus/{id}', [KegiatanController::class, 'delete'])->name('delete');
        Route::get('hapus/item/{id}', [KegiatanController::class, 'itemDelete'])->name('item.delete');
        Route::get('lihat-pdf/{id}', [KegiatanController::class, 'viewPdf'])->name('lihat-pdf');
        Route::get('hapus-pdf/{id}', [KegiatanController::class, 'deletePdf'])->name('hapus-pdf');

        Route::post('store', [KegiatanController::class, 'store'])->name('store');
        Route::post('update/{id}', [KegiatanController::class, 'update'])->name('update');

        Route::post('store/item', [KegiatanController::class, 'itemStore'])->name('item.store');
        Route::post('update/item/{id}', [KegiatanController::class, 'itemUpdate'])->name('item.update');
    });

    Route::group(['prefix' => 'stok', 'as' => 'stok.'], function () {
        Route::get('daftar/barang', [StokController::class, 'itemShow'])->name('show.item');
        Route::get('daftar/barang/selectAll', [StokController::class, 'itemSelectAll'])->name('show.item.selectAll');
    });

    Route::get('snackcorner/show', [SnackcornerController::class, 'usulan'])->name('snc.show');

    Route::group(['prefix' => 'snaco', 'as' => 'snaco.'], function () {
        Route::get('dashboard', [SnackcornerController::class, 'index'])->name('dashboard');

        Route::get('show', [SnackcornerController::class, 'show'])->name('show');
        Route::get('barang/detail/{id}', [SnackcornerController::class, 'detailItem'])->name('detail.item');

        Route::get('edit/{id}', [SnackcornerController::class, 'edit'])->name('edit');
        Route::get('select', [SnackcornerController::class, 'selectAll'])->name('selectAll');
        Route::POST('upload', [SnackcornerController::class, 'upload'])->name('upload')->middleware('access:admin');

        Route::get('stok/uker/barang/{id}', [SnackcornerController::class, 'stokUker']);

        Route::get('stok', [SnackcornerController::class, 'stok'])->name('stok.show')->middleware('access:monitor');
        Route::get('stok/detail/{id}', [SnackcornerController::class, 'stokDetail'])->name('stok.detail')->middleware('access:monitor');
        Route::get('stok/tambah', [SnackcornerController::class, 'stokCreate'])->name('stok.create')->middleware('access:admin');
        Route::get('stok/edit/{id}', [SnackcornerController::class, 'stokEdit'])->name('stok.edit')->middleware('access:admin');
        Route::get('stok/hapus/{id}', [SnackcornerController::class, 'stokDelete'])->name('stok.delete')->middleware('access:admin');

        Route::get('stok/item/delete/{aksi}/{id}', [SnackcornerController::class, 'stokItemDelete'])->name('stok.item.delete');

        Route::post('stok/store', [SnackcornerController::class, 'stokStore'])->name('stok.store')->middleware('access:admin');
        Route::post('stok/update/{id}', [SnackcornerController::class, 'stokUpdate'])->name('stok.update')->middleware('access:admin');
        Route::post('stok/item/daftar', [SnackcornerController::class, 'stokItemUpdate'])->name('stok.item.show')->middleware('access:admin');
        Route::post('stok/item/update/{id}', [SnackcornerController::class, 'stokItemUpdate'])->name('stok.item.update')->middleware('access:admin');
        Route::post('stok/item/tambah', [SnackcornerController::class, 'stokItemStore'])->name('stok.item.store')->middleware('access:admin');

        Route::get('detail/barang/{id}', [SnackcornerController::class, 'select'])->name('select');
        Route::get('detail/usulan/{id}', [SnackcornerController::class, 'detail'])->name('detail');
        Route::get('detail/select/{id}', [SnackcornerController::class, 'snc'])->name('snc');
        Route::get('detail/proses/{id}', [SnackcornerController::class, 'proses'])->name('proses')->middleware('admProses');

        Route::get('keranjang/update/{aksi}/{id}', [SnackcornerController::class, 'updateBucket'])->name('keranjang.update');
        Route::get('keranjang/remove/{id}', [SnackcornerController::class, 'removeBucket'])->name('keranjang.remove');

        Route::get('barang/hapus/{id}', [SnackcornerController::class, 'deleteItem'])->name('item.delete');

        Route::post('update', [SnackcornerController::class, 'update'])->name('update');
        Route::post('keranjang', [SnackcornerController::class, 'storeBucket'])->name('keranjang.store');
        Route::post('barang/tambah', [SnackcornerController::class, 'addItem'])->name('item.store');
        Route::post('barang/update', [SnackcornerController::class, 'updateItem'])->name('item.update');
    });
});
