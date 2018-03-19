<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DatabaseSeeder.
 */
class HermoxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $brands_data = [
            [
                'name' => 'Mango'
            ],
            [
                'name' => 'Abu'
            ],
        ];
        DB::table('brands')->insert($brands_data);
        
        $countries_data = [
            [
                'name' => 'Malaysia'
            ],
            [
                'name' => 'Singapore'
            ],
            [
                'name' => 'Brunei'
            ],
        ];
        DB::table('countries')->insert($countries_data);

        $promotion_code_data = [
            [
                'name' => 'OFF5PC'
            ],
            [
                'name' => 'GIVEME15'
            ],
        ];
        DB::table('promotion_code')->insert($promotion_code_data);
    }
}
