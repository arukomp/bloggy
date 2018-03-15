<?php

namespace Arukomp\Bloggy\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'plural',
        'description',
        'slug',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = Auth::id();
        });
    }

    public function getTable()
    {
        return config('bloggy.database_prefix').'post_types';
    }

    public static function findBySlug($slug)
    {
        return self::where('slug', $slug)->first();
    }

    public static function findByName($name)
    {
        return self::where('name', $name)->first();
    }

    public function setNameAttribute($name)
    {
        $this->attributes['name'] = $name;

        $this->setAttribute('slug', str_slug($name, '-'));
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'post_type_id', 'id');
    }
}
