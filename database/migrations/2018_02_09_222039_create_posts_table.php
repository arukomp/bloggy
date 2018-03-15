<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    protected $dbPrefix;

    public function __construct()
    {
        $this->dbPrefix = config('bloggy.database_prefix');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->dbPrefix.'posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('slug');
            $table->integer('revision')->unsigned()->default(1);
            $table->integer('revision_parent_post_id')->nullable();
            $table->text('excerpt');
            $table->longText('body');
            $table->boolean('active')->default(false);
            $table->boolean('allow_comments')->default(true);
            $table->integer('author_id')->unsigned();
            $table->timestamps();
            $table->integer('updated_by')->unsigned();
            $table->softDeletes();

            $table->foreign('author_id')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->dbPrefix.'posts');
    }
}
