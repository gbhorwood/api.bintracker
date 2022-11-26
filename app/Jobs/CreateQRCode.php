<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Traits\QrTrait;
use App\Traits\S3Trait;

use App\Models\Bin;

/**
 * Worker to create QR code, generate access url for it and save in bin record.
 *
 * @author gbh
 */
class CreateQRCode implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, QrTrait, S3Trait;

    /**
     * Bin id as passed to constructor at dispatch()
     */
    protected Int $binId;

    /**
     * S3 configurations
     */
    protected String $s3config = 's3_public';          // @see config.filesystems
    protected String $s3directoryQrcodes = 'qrcodes/'; // directory path to store in s3


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Int $binId)
    {
        $this->binId = $binId;
    }


    /**
     * Execute the job.
     * Makes a QR code for the frontend url for the bin and pushes the QR to
     * S3. S3 url of QR then stored in the bin's record.
     *
     * @return Boolean
     */
    public function handle()
    {

        /**
         * Validate the bin exists
         */
        $bin = Bin::find($this->binId);
        if (!$bin) {
            return false;
        }

        /**
         * Create the QR code and save to local disk
         * @see Traits.QrTrait.makeQrPng
         */
        $qrData = config('app.fe_url')."/bins/".$bin->id;
        $sizePx = 200;
        $outfilePath = public_path()."/qrcodes/".uniqid('qr').".png";
        $logoUrl = config('app.logo_url');
        $qrPathOnDisk = $this->makeQrPng($qrData, $sizePx, $outfilePath, $logoUrl);

        /**
         * Save QR url in bin record
         */

        /**
         * Local and testing: store QR on local disk
         */
        if (\App::Environment() === 'local' || \App::Environment() == 'testing') {
            // deduce local url
            $url = config('app.url')."/qrcodes/".basename($qrPathOnDisk);

            // save url in record
            $bin->qr_url = $url;
            $bin->save();
        }
        /**
         * Staging and production: store QR in S3
         */
        else {
            // push to S3 public
            $s3Result = $this->s3PutFromDisk(
                $this->s3config,
                $qrPathOnDisk,  // local file
                true,           // public
                $this->s3directoryQrcodes
            );

            // save s3 url in record
            $bin->qr_url = $s3Result;
            $bin->save();
         
            // cleanup local file
            unlink($qrPathOnDisk);
        }

        /**
         * Return true
         */
        return true;
    } // handle
}
