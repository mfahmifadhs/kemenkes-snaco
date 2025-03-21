<?php

namespace App\Helpers;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class QrCodeHelper
{
    public static function generateQrCode($url)
    {
        $qrCode = new QrCode($url);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        return $result->getDataUri();
    }
}
