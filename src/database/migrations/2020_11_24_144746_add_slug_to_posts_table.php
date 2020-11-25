<?php

use DarkBlog\Models\Post;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSlugToPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // create the new field first
        Schema::table('posts', function (Blueprint $table) {
            $table->string('slug')->default('');
        });

        // Populate it
        foreach (Post::all() as $post) {
            $post->slug = \DarkBlog\Models\Slug::generate($post->title);
            $post->save();
        }

        // Now force the new field to be unique
        Schema::table('posts', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            //
        });
    }
}
