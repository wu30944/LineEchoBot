<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDictionariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dictionaries', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('word_tyep'); //字詞屬性
            $table->string('word_id'); // 字詞號
            $table->string('word'); //字詞名
            $table->string('radical'); //部首
            $table->string('radical_strokes'); //筆劃
            $table->string('strokes'); //筆劃
            $table->string('phonetic'); //注音
            $table->string('pinyin'); //拼音
            $table->string('synonymous');//相似詞
            $table->string('opposite');//相反
            $table->text('explanation'); //解釋
            $table->string('edit'); //按編
            $table->string('multitone'); //多音
            $table->string('variant');//異體字
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
        Schema::dropIfExists('dictionaries');
    }
}
