<?php

namespace App\Traits;

use DB;
use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * S3 trait
 *
 * Trait for accessing public and private files on s3 buckets using one or
 * more 's3' configuration values in 'config.filesystems.disks'
 *
 * You will need stuff like this in your .env
 *
 * AWS_ACCESS_KEY_ID
 * AWS_SECRET_ACCESS_KEY
 * AWS_DEFAULT_REGION
 * AWS_USE_PATH_STYLE_ENDPOINT=false
 *
 * And then for each bucket you want to use, you will need an .env entry with a sensible configuration name, ie
 * AWS_BUCKET_USER=bintracker-user-assets
 * AWS_BUCKET_SITE=bintracker-site-assets
 *
 * Then you will need to create an entry in `config.filesystems.disks` for each of those buckets.
 *
 * Think about your public-vs-private strategy!
 *
 * Storing:
 * To store in a bucket call `s3Upload`. You can choose to set the 'public' parameter to 'true' to make the object public
 * but only if the bucket allows public objects. If the bucket does not, an exception will be thrown.
 *
 * The return from `s3Upload` will be an array with three elements:
 *   - s3_config
 *   - path
 *   - public
 *
 * Store all of these in the database.
 *
 * Retreiving
 * When it comes time to get the url of the assets from the bucket, consult your three datapoints. If 'public' is false
 * then you must get a signed url with `s3SignedLink`. If 'public' is true, then you can use `s3Link`.
 *
 * Passing the 'path' and 's3_config' to either of those methods will then give you a usable link.
 *
 * @see config.filesystems.disks
 * @author gbh
 */
trait S3Trait
{
    /**
     * Stores an uploaded file to a bucket and returns an array with:
     *  - s3_config: the config block for this s3 bucket as found in config.filesystems
     *  - path: the path to the the file
     *  - public: true if the file is public
     *
     * @param  String   $s3config   The S3 config block to use. see config.disks for valid s3 blocks; there may be many to choose from.
     * @param  Request  $request    The Request object from the controller
     * @param  bool     $public     Is the object going to be public in the bucket. Note: on private-only buckets, setting this to true will error. Default 'false'.
     * @param  String   $file       The name of the file as uploaded in the Request. Default 'file'
     * @param  String   $directory  The directory path on the bucket where the file goes
     * @return Array
     * @author gbh
     */
    public function s3Upload(String $s3config, Request $request, bool $public=false, String $file="file", String $directory=null):array
    {
        /**
         * Confirm s3 configuration exists
         */
        if (!count(config("filesystems.disks.$s3config"))) {
            throw new \Exception("Unable to access storage bucket");
        }

        /**
         * Munge directory to ensure a usable path
         */
        $directory = trim($directory, "/ ");

        /**
         * Push to the bucket
         */
        if ($public) {
            $path = Storage::disk($s3config)->put($directory, $request->file($file), 'public');
        } else {
            $path = Storage::disk($s3config)->put($directory, $request->file($file));
        }

        /**
         * Return array of data needed to make signed url
         */
        return [
            's3_config' => $s3config,
            'path' => $path,
            'public' => $public,
        ];
    } // s3Upload


    /**
     * Stores a file on local disk to a bucket and the url of the asset on s3
     *
     * @param  String   $s3config   The S3 config block to use. see config.disks for valid s3 blocks; there may be many to choose from.
     * @param  String   $filePath   The full path to the file on local disk to push to the s3
     * @param  bool     $public     Is the object going to be public in the bucket. Note: on private-only buckets, setting this to true will error. Default 'false'.
     * @param  String   $directory  The directory path on the bucket where the file goes
     * @return String   The url of the s3 assets
     * @author gbh
     */
    public function s3PutFromDisk(String $s3config, String $filePath, bool $public=false, String $directory=null):String
    {
        /**
         * Confirm s3 configuration exists
         */
        if (!count(config("filesystems.disks.$s3config"))) {
            throw new \Exception("Unable to access storage bucket");
        }

        /**
         * Build destination file path
         */
        $directory = trim($directory, "/ ");
        $destFfileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
        $destFileName = Str::uuid().".".$destFfileExtension;
        $destFilePath = $directory."/".$destFileName;

        /**
         * Push to the bucket
         */
        if ($public) {
            $path = Storage::disk($s3config)->put($destFilePath, file_get_contents($filePath), 'public');
        } else {
            $path = Storage::disk($s3config)->put($destFilePath, file_get_contents($filePath));
        }

        /**
         * Return the s3 url
         */
        $url = "https://".config("filesystems.disks.$s3config.bucket").".s3.".config("filesystems.disks.$s3config.region").".amazonaws.com/".$destFilePath;
        return $url;
    } // s3PutFromDisk


    /**
     * Returns a signed link to access a private file on a bucket
     *
     * @param String $s3config  The S3 config block to use. see config.disks for valid s3 blocks; there may be many to choose from.
     * @param String $path      The path to the file on the bucket, ie "my/directory/structure/myfile.jpg"
     * @param String $expiry    How long the link is good for in aws format, ie "+3 hours". Default "+10 minutes"
     * @return String
     */
    public function s3SignedLink(String $s3config, String $path, String $expiry="+10 minutes"):String
    {
        /**
         * Confirm s3 configuration exists
         */
        if (!count(config("filesystems.disks.$s3config"))) {
            throw new \Exception("Unable to access storage bucket");
        }

        /**
         * Request signed link
         */
        $s3 = Storage::disk($s3config);
        $client = $s3->getDriver()->getAdapter()->getClient(); // @phpstan-ignore-line
        $command = $client->getCommand('GetObject', [
            'Bucket' => config("filesystems.disks.$s3config.bucket"),
            'Key'    => $path
        ]);
        $request = $client->createPresignedRequest($command, $expiry);

        return (String)$request->getUri();
    } // s3SignedLink


    /**
     * Returns a link to access a public file on a bucket.
     *
     * @param String $s3config  The S3 config block to use. see config.disks for valid s3 blocks; there may be many to choose from.
     * @param String $path      The path to the file on the bucket, ie "my/directory/structure/myfile.jpg"
     * @return String
     */
    public function s3Link(String $s3config, String $path):String
    {
        /**
         * Confirm s3 configuration exists
         */
        if (!count(config("filesystems.disks.$s3config"))) {
            throw new \Exception("Unable to access storage bucket");
        }

        return "https://".config("filesystems.disks.$s3config.bucket").".s3.amazonaws.com/".$path;
    } // s3Link
}
