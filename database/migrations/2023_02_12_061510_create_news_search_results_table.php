<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Enhancement - User table and its foreign key to be added
        Schema::create('news_search_results', function (Blueprint $table) {
            $table->id();
            $table->string('content_ref_id')->unique();
            $table->string('title'); //TODO: approx 150 characters - review and update
            $table->string('article_url')->unique();
            $table->date('published_date')->nullable();
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();
            $table->softDeletes(); //adds deleted_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_search_results');
    }
};
