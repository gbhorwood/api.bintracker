<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\FruitbatTestCase;
use App\Models\User;
use App\Libs\Utilities;

class utilitiesTest extends FruitbatTestCase
{
    use RefreshDatabase;

    public function setUp():void
    {
        parent::setUp();
    }

    /**
     * test saltHash and validateSaltHash
     * pass
     *
     * @return void
     */
    public function test_pass_saltHash()
    {

        $utilities = new Utilities();

        $val = "somestring";
        $hash = $utilities->saltHash($val);
        $test = $utilities->validateSaltHash($val, $hash);
        $this->assertEquals(true,$test);
    }

    /**
     * test saltHash and validateSaltHash
     * fail
     *
     * @return void
     */
    public function test_fail_saltHash()
    {

        $utilities = new Utilities();

        $val = "somestring";
        $hash = $utilities->saltHash($val);
        $test = $utilities->validateSaltHash($val."not", $hash);
        $this->assertEquals(false,$test);
        $test = $utilities->validateSaltHash($val, $hash."not");
        $this->assertEquals(false,$test);
    }

    /**
     * test dateMidnightZeroOne
     * fail
     *
     * @return void
     */
    public function test_pass_dateMidnightZeroOne()
    {

        $utilities = new Utilities();
        $date = "2022-01-04 12:34:56";
        $testDate = $utilities->dateMidnightZeroOne($date);
        $this->assertEquals('00:00:01', substr($testDate, -8));
    }

    /**
     * test dateMidnightZeroZero
     * fail
     *
     * @return void
     */
    public function test_pass_dateMidnightZeroZero()
    {

        $utilities = new Utilities();
        $date = "2022-01-04 12:34:56";
        $testDate = $utilities->dateMidnightZeroZero($date);
        $this->assertEquals('00:00:00', substr($testDate, -8));
    }

    /**
     * test dateElevenFiftyNine
     * fail
     *
     * @return void
     */
    public function test_pass_dateElevenFiftyNine()
    {

        $utilities = new Utilities();
        $date = "2022-01-04 12:34:56";
        $testDate = $utilities->dateElevenFiftyNine($date);
        $this->assertEquals('23:59:59', substr($testDate, -8));
    }

    /**
     * test validYYYYMMDDFormat
     * pass
     *
     * @return void
     */
    public function test_pass_validYYYYMMDDFormat()
    {
        $date = "2021-10-23";
        $utilities = new Utilities();
        $this->assertEquals(true, $utilities->validYYYYMMDDFormat($date));
    }

    /**
     * test validYYYYMMDDFormat
     * fail
     *
     * @return void
     * @dataProvider http400provider_validYYYYMMDDFormat
     */
    public function test_fail_validYYYYMMDDFormat($date)
    {
        $utilities = new Utilities();
        $this->assertEquals(false, $utilities->validYYYYMMDDFormat($date));
    }

    /**
     * test validateDateTime
     * pass
     *
     * @return void
     */
    public function test_pass_validateDateTime()
    {
        $date = "2021-10-23 12:34:56";
        $utilities = new Utilities();
        $this->assertEquals(true, $utilities->validateDateTime($date));
    }

    /**
     * test validateDateTime
     * fail
     *
     * @return void
     * @dataProvider http400provider_validateDateTime
     */
    public function test_fail_validateDateTime($date)
    {
        $utilities = new Utilities();
        $this->assertEquals(false, $utilities->validateDateTime($date));
    }

    public function test_pass_getAllDates()
    {
        $utilities = new Utilities();

        $startDate = "2021-12-28";
        $endDate = "2022-01-04";
        $allDates = [
            "2021-12-28",
            "2021-12-29",
            "2021-12-30",
            "2021-12-31",
            "2022-01-01",
            "2022-01-02",
            "2022-01-03",
            "2022-01-04",
        ];

        $result = $utilities->getAllDates($startDate, $endDate);
        $this->assertEquals($allDates, $result);
    }

    /**
     * Data provider for 400
     */
    public function http400provider_validYYYYMMDDFormat()
    {
        return [
            [null],
            ["notadate"],
            ["2021-11-34"],
            ["10/30/2021"],
        ];
    }

    /**
     * Data provider for 400
     */
    public function http400provider_validateDateTime()
    {
        return [
            [null],
            ["notadate"],
            ["2021-11-34"],
            ["2021-11-23 12:34:77"],
            ["10/30/2021"],
        ];
    }
}
