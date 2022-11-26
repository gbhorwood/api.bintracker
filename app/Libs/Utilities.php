<?php
namespace App\Libs;

use DB;
use Exception;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Helper utilities
 *
 */
class Utilities {

    /**
     * Hashes a value with our configured secret_salt
     *
     * @param   String $toHash The value to hash
     * @return  String
     * @note    warning: MD5
     */
    public function saltHash(String $toHash):String
    {
        return md5($toHash.config('app.secret_salt'));
    }


    /**
     * Tests if the hash $hash is valid for the value $value
     *
     * @param   String $value
     * @param   String $hash
     * @return  bool
     * @note    warning: MD5
     */
    public function validateSaltHash(String $value, String $hash):bool
    {
        return md5($value.config('app.secret_salt')) == $hash;
    }


    /**
     * Sets the time of a date string to midnight plus one second.
     * This gets us our 'start of day' datetime to the second.
     *
     * eg. '2020-02-23 13:45:23' => '2020-02-23 00:00:01'
     *
     * @param   String  $date
     * @return  String
     */
    public function dateMidnightZeroOne(String $date):String
    {
        $dateObj = new \DateTime($date);
        $dateObj->setTime(00, 00, 01);
        return $dateObj->format('Y-m-d H:i:s');
    }


    /**
     * Sets the time of a date string to midnight
     *
     * eg. '2020-02-23 13:45:23' => '2020-02-23 00:00:00'
     *
     * @param   String  $date
     * @return  String
     */
    public function dateMidnightZeroZero(String $date):String
    {
        $dateObj = new \DateTime($date);
        $dateObj->setTime(00, 00, 00);
        return $dateObj->format('Y-m-d H:i:s');
    }


    /**
     * Sets the time of a date string to 11:59:59
     * This gets us our 'end of day' datetime to the second
     *
     * eg. '2020-02-23 13:45:23' => '2020-02-23 23:59:59'
     *
     * @param   String  $date
     * @return  String
     */
    public function dateElevenFiftyNine(String $date):String
    {
        $dateObj = new \DateTime($date);
        $dateObj->setTime(23, 59, 59);
        return $dateObj->format('Y-m-d H:i:s');
    }


    /**
     * Validates that a string is in YYYY-MM-DD format
     *
     * @param String $date The string to validate
     * @return boolean
     */
    public function validYYYYMMDDFormat($date):bool
    {
        $format = 'Y-m-d';
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }


    /**
     * Validates that a string is in YYYY-MM-DD H:i:s format
     *
     * @param String $date The string to validate
     * @return boolean
     */
    public function validateDateTime($date, $format = 'Y-m-d H:i:s')
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }


    /**
     * Get an array of all dates as YYYY-MM-DD between start date and end date
     *
     * @param   String  $startdate Lowest date in range as YYYY-MM-DD
     * @param   String  $enddate   Highest date in range as YYYY-MM-DD
     * @return  Array
     */
    public function getAllDates(String $startdate, String $enddate):array
    {
        $begin      = new \DateTime($startdate);
        $end        = new \DateTime($enddate);
        $end        = $end->modify('+1 day');
        $interval   = new \DateInterval('P1D');
        $daterange  = new \DatePeriod($begin, $interval, $end);

        $returnableDateRange = [];
        foreach ($daterange as $d) {
            $returnableDateRange[] = $d->format("Y-m-d");
        }

        return $returnableDateRange;
    }
}