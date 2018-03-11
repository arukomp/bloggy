<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddDefaultPostTypeToPostTypesTable extends Migration
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
        if (! DB::table($this->dbPrefix . 'post_types')->where('slug', 'post')->exists()) {
            DB::table($this->dbPrefix . 'post_types')->insert([
                'name' => 'Post',
                'plural' => 'Posts',
                'slug' => 'post',
                'description' => 'Articles, blog posts, pieces of content',
                'created_by' => null,
                'deleted_at' => null,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
