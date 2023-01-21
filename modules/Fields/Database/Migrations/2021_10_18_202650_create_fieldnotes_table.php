<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CreateFieldnotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        Schema::create('fieldnotes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('notetype_id');
            $table->foreign('notetype_id')->references('id')->on('posts');
            $table->unsignedBigInteger('notestatus_id');
            $table->foreign('notestatus_id')->references('id')->on('posts');
            $table->unsignedBigInteger('field_id')->nullable();
            $table->foreign('field_id')->references('id')->on('fields');
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies');

            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');

            $table->unsignedBigInteger('assigned_to')->nullable();;
            $table->foreign('assigned_to')->references('id')->on('users');


            $table->string('lat');
            $table->string('lng');
            $table->string('title');
            $table->text('description');
            $table->string('uuid');
            $table->string('image');
            $table->integer('is_public');
            $table->timestamps();
            $table->softDeletes();
        });

        /*
         //Do a insert
         $notetypes = json_decode(File::get(base_path('modules/fields/database/migrations/json/notetypes.json')), true);

         foreach ($notetypes as $key => $notetype) {
            DB::table('notetypes')->insertGetId([
                 'name'=>$notetype['name'],
                 'icon'=>$notetype['icon']
             ]);
         }


         $notestatuses = json_decode(File::get(base_path('modules/fields/database/migrations/json/notestatuses.json')), true);

         foreach ($notestatuses as $key => $notestatus) {
             DB::table('notestatuses')->insertGetId([
                 'name'=>$notestatus['name']
             ]);
         }
         */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fieldnotes');
        Schema::dropIfExists('notestatuses');
        Schema::dropIfExists('notetypes');
    }
}
