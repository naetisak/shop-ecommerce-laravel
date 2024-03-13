<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->after('id'); // สร้างคอลัมน์ user_id หลังจากคอลัมน์ id
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // เพิ่ม Foreign Key ให้กับคอลัมน์ user_id
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // ลบ Foreign Key ที่เพิ่มขึ้นมาก่อนหน้านี้
            $table->dropColumn('user_id'); // ลบคอลัมน์ user_id
        });
    }
}
