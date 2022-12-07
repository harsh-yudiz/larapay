<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('currencys')->truncate();

        $data = [
            ['currency' => 'USD'],
            ['currency' => 'ALL'],
            ['currency' => 'AED'],
            ['currency' => 'AFN'],
            ['currency' => 'AMD'],
            ['currency' => 'ANG'],
            ['currency' => 'AOA'],
            ['currency' => 'ARS'],
            ['currency' => 'AUD'],
            ['currency' => 'AWG'],
            ['currency' => 'AZN'],
            ['currency' => 'BAM'],
            ['currency' => 'BBD'],
            ['currency' => 'BDT'],
            ['currency' => 'BGN'],
            ['currency' => 'BIF'],
            ['currency' => 'BOB'],
            ['currency' => 'BWP'],
            ['currency' => 'CAD'],
            ['currency' => 'CDF'],
            ['currency' => 'CNY'],
            ['currency' => 'DKK'],
            ['currency' => 'DOP'],
            ['currency' => 'DZD'],
            ['currency' => 'EGP'],
            ['currency' => 'ETB'],
            ['currency' => 'EUR'],
            ['currency' => 'FKP'],
            ['currency' => 'GIP'],
            ['currency' => 'GMD'],
            ['currency' => 'HKD'],
            ['currency' => 'HNL'],
            ['currency' => 'HRK'],
            ['currency' => 'HTG'],
            ['currency' => 'HUF'],
            ['currency' => 'ILS'],
            ['currency' => 'INR'],
            ['currency' => 'ISK'],
            ['currency' => 'JMD'],
            ['currency' => 'KYD'],
            ['currency' => 'KZT'],
            ['currency' => 'LAK'],
            ['currency' => 'LBP'],
            ['currency' => 'LKR'],
            ['currency' => 'LRD'],
            ['currency' => 'LSL'],
        ];

        DB::table('currencys')->insert($data);
        
    }
}
