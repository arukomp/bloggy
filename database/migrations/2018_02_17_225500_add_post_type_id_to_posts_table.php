<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPostTypeIdToPostsTable extends Migration
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
        Schema::table($this->dbPrefix . 'posts', function (Blueprint $table) {
            $table->integer('post_type_id')->unsigned()->nullable();

            $table->foreign('post_type_id')->references('id')->on($this->dbPrefix . 'post_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->dbPrefix . 'posts', function (Blueprint $table) {
            $table->dropForeign(['post_type_id']);

            $table->dropColumn(['post_type_id']);
        });
    }
}
