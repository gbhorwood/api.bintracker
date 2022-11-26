<?php

namespace App\Traits;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

/**
 * Qr Trait
 *
 * Trait for making QR codes
 *
 */
trait QrTrait
{
    /**
     * Make the QR code as a png and save to local disk
     *
     * @param  String $qrData        The text data to encode in the QR code
     * @param  Int    $sizePx        The size of the QR code in pixels. QRs are square, so only one dimension needed.
     * @param  String $outfilePath   The full path to the file on local disk where the QR will be saved
     * @param  String $logoUrl       The optional url to the logo to put in the center of the QR
     * @return String                The path to the saved QR code
     * @author gbh
     */
    public function makeQrPng(String $qrData, Int $sizePx, String $outfilePath, String $logoUrl=null):String
    {

        /**
         * Set QR code creator
         */
        $qrCreator = QrCode::format('png')
            ->size($sizePx)
            ->errorCorrection('H')
            ->encoding('ASCII');

        /**
         * Optionally add the logo
         */
        if ($logoUrl) {
            $qrCreator->merge($logoUrl, .25, true);
        }

        /**
         * Write QR to local disk
         */
        $qrCreator->generate($qrData, $outfilePath);

        /**
         * Return the path to the qr on local disk
         */
        return $outfilePath;
    } // makeQrPng
}
