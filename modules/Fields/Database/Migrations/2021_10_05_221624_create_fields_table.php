<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

use function Ramsey\Uuid\v1;

class CreateFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crops', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('fields', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('image');
            $table->text('coordinates');
            $table->double('area', 15, 2);
            $table->unsignedBigInteger('crop_id');
            $table->foreign('crop_id')->references('id')->on('crops');
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->timestamps();
            $table->softDeletes();
        });

        //Do a insert
        try {
            $crops = json_decode(File::get(base_path('/modules/Fields/Database/Migrations/json/crops.json')), true);

            foreach ($crops as $key => $crop) {
                $lastCropId=DB::table('crops')->insertGetId([
                    'name'=>$crop['name']
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fields');
        Schema::dropIfExists('crops');
    }
}
