<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantWorkers extends Migration{

    public function up(){

        Schema::create('items', function (Blueprint $table) {

            $table->increments('id');
            $table -> string('name');
            $table -> string('dni');
            $table -> integer('phone_number');

        }

    }

}