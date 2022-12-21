<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Post;

class CreateAllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->text('content');
            $table->string('imagem');
            $table->dateTime('date');
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->foreignIdFor(Post::class)->references('id')->on('posts')->onDelete('CASCADE');
        });

        
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->string('rating');
            $table->foreignIdFor(Post::class)->references('id')->on('posts')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeignIdFor(Post::class);
        });

        Schema::table('ratings', function (Blueprint $table) {
            $table->dropForeignIdFor(Post::class);
        });
 
        Schema::dropIfExists('comments');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('ratings');
    }
}
