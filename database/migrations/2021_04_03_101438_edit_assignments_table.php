<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
            $table->dropColumn('technicians_name');
            $table->bigInteger('user_id')->default(2)->after('semester_id')->comment('id người trực phòng máy');
            $table->bigInteger('shift')->default(0)->after('user_id')->comment('ca trực. 0: ca sáng, 1: ca chiều');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('shift');
            $table->date('start_date')->default("2020-04-04")->after('phone');
            $table->date('end_date')->default("2020-04-05")->after('start_date');
            $table->string('technicians_name')->default("kĩ thuật viên")->after('semester_id');

        });
    }
}
