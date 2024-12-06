<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('__cases', function (Blueprint $table) {
            $table->id();
            $table->string('number_office')->nullable();
            $table->string('number_institution')->nullable();
            $table->string('prosecutor')->nullable();
            $table->string('defendants')->nullable();
            $table->integer('institution_id')->nullable();
            $table->string('mark')->nullable();
            $table->integer('case_type_id')->nullable();
            $table->string('archive')->nullable();
            $table->string('brand')->nullable();
            $table->date('fail_day')->nullable();
            $table->date('date_send_to_expert')->nullable();
            $table->date('date_send_to_mail')->nullable();
            $table->date('date_of_findings')->nullable();
            $table->date('date_of_reporting_to_insurance')->nullable();
            $table->string('deadline')->nullable();
            $table->string('requested_amount')->nullable();
            $table->string('paid_amount')->nullable();
            $table->string('expert_costs')->nullable();
            $table->string('status')->nullable();
            $table->string('mup_note')->nullable();
            $table->string('damage_number')->nullable();
            $table->string('commission')->nullable();
            $table->string('at')->nullable();
            $table->string('lawsuit')->nullable();
            $table->longText('note')->nullable();
            $table->longText('vansudski_number')->nullable(); //npr koji je number_office u parnicnom
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('__cases');
    }
}
