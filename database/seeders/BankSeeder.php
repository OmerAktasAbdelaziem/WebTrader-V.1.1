<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bank;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $banks = [
            [
                'name' => 'Chase Bank',
                'country' => 'United States',
                'account_number' => '1234567890',
                'beneficiary_name' => 'WebTrader LLC',
                'swift_code' => 'CHASUS33',
                'iban' => null,
                'bic' => 'CHASUS33XXX',
                'address' => '270 Park Avenue, New York, NY 10017',
                'is_active' => true,
                'currency' => 'USD',
                'type' => 'Commercial',
                'beneficiary_country' => 'United States',
                'beneficiary_address' => '270 Park Avenue, New York, NY 10017',
                'aba_routing_number' => '021000021',
                'pipeline_id' => 1,
            ],
            [
                'name' => 'Barclays Bank',
                'country' => 'United Kingdom',
                'account_number' => 'GB123456',
                'beneficiary_name' => 'WebTrader Limited',
                'swift_code' => 'BARCGB22',
                'iban' => 'GB82BARC20038032622823',
                'bic' => 'BARCGB22XXX',
                'address' => '1 Churchill Place, London E14 5HP',
                'is_active' => true,
                'currency' => 'GBP',
                'type' => 'Commercial',
                'beneficiary_country' => 'United Kingdom',
                'beneficiary_address' => '1 Churchill Place, London E14 5HP',
                'aba_routing_number' => null,
                'pipeline_id' => 1,
            ],
            [
                'name' => 'Deutsche Bank',
                'country' => 'Germany',
                'account_number' => 'DE987654321',
                'beneficiary_name' => 'WebTrader GmbH',
                'swift_code' => 'DEUTDEFF',
                'iban' => 'DE89370400440532013000',
                'bic' => 'DEUTDEFFXXX',
                'address' => 'Taunusanlage 12, 60325 Frankfurt am Main',
                'is_active' => true,
                'currency' => 'EUR',
                'type' => 'Commercial',
                'beneficiary_country' => 'Germany',
                'beneficiary_address' => 'Taunusanlage 12, 60325 Frankfurt am Main',
                'aba_routing_number' => null,
                'pipeline_id' => 1,
            ],
        ];

        foreach ($banks as $bank) {
            Bank::updateOrCreate(
                ['name' => $bank['name'], 'country' => $bank['country']],
                $bank
            );
        }
    }
}
