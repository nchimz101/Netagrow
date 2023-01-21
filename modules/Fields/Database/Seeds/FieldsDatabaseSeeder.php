<?php

namespace Modules\Fields\Database\Seeds;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class FieldsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        

        //INSERT FIELD
        $lastFielfId=DB::table('fields')->insertGetId([
                "name"=>"My first field",
                "image"=>"https://i.imgur.com/Poyhghp.png",
                "coordinates"=>"[[[21.78001745059538,41.809351437267225],[21.78975617340103,41.80649050311203],[21.784886811998234,41.79453297775714],[21.774174216912172,41.79730903072269],[21.78001745059538,41.809351437267225]]]",
                "area"=>999999.99,
                "crop_id"=> 181,
                "company_id"=> 1,
        ]);

        //{"en":"Just reported"} -notestatus
        //{"en":"Just pest"} -notetype
        
        Model::reguard();
    }
}
