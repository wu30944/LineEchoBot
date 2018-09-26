<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIdiomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('idioms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('word'); //成語
            $table->string('phonetic'); //注音
            $table->string('pinyin'); //拼音
            $table->text('explanation'); //釋義
            $table->text('story'); //典源
            $table->text('story_explanation'); //典故說明
            $table->text('origin'); //書證
            $table->text('origin_explanation'); //用法說明
            $table->string('synonymous');//近義
            $table->string('opposite');//反義
            $table->string('reference'); //辨識 
            $table->string('other'); //參考語詞
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
        Schema::dropIfExists('idioms');
    }
}
