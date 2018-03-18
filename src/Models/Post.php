<?php

namespace Arukomp\Bloggy\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Arukomp\Bloggy\Models\Traits\ValidatesModel;
use Arukomp\Bloggy\Models\Traits\KeepRevisionHistory;

class Post extends Model
{
    use SoftDeletes;
    use KeepRevisionHistory;
    use ValidatesModel;

    protected $fillable = [
        'title',
        'excerpt',
        'body',
        'allow_comments',
        'slug',
        'post_type_id',
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'active' => 'bool',
    ];

    protected $with = ['author', 'type'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->author_id)) {
                $post->author_id = Auth::id();
            }
        });

        static::saving(function ($post) {
            if (Auth::id()) {
                $post->updated_by = Auth::id();
            }
        });
    }

    public function getTable()
    {
        return config('bloggy.database_prefix').'posts';
    }

    public function author()
    {
        return $this->belongsTo(config('bloggy.user_class'));
    }

    public function setActive($active = true)
    {
        return $this->setAttribute('active', $active);
    }

    public function publish()
    {
        return tap($this->setActive())->save();
    }

    public function unpublish()
    {
        return tap($this->setActive(false))->save();
    }

    public function saveDraft()
    {
        return tap($this)->save();
    }

    public function setSlug($slug = null)
    {
        if (is_null($slug)) {
            $slug = str_slug($this->getAttribute('title'), '-');
        }

        return tap($this)->setAttribute('slug', $slug);
    }

    public function setExcerpt($excerpt = null)
    {
        if (is_null($excerpt)) {
            $excerpt = str_limit($this->getAttribute('body'), 350);
        }

        return tap($this)->setAttribute('excerpt', $excerpt);
    }

    public function setAllowCommentsAttribute($value)
    {
        if ($value || $value == 'on') {
            $this->attributes['allow_comments'] = true;
        } else {
            $this->attributes['allow_comments'] = false;
        }
    }

    public function setTitleAttribute($title)
    {
        $this->attributes['title'] = $title;
        $this->setSlug();
    }

    public function setBodyAttribute($body)
    {
        $this->attributes['body'] = $body;
        $this->setExcerpt();
    }

    public function getValidationRules()
    {
        return [
            'title'                   => 'required|string|max:191',
            'slug'                    => 'required|string|max:191',
            'revision_post_parent_id' => 'nullable|integer',
            'author_id'               => 'nullable|integer',
            'revision'                => 'required|integer',
        ];
    }

    public function getValidationMessages()
    {
        return [
            'title.required'    => 'Post Title is required',
            'title.string'      => 'Post Title must be a string of characters/numbers',
            'title.max'         => 'Post Title cannot be longer than 191 characters',
            'slug.required'     => 'Post URL slug is required',
            'slug.string'       => 'Post URL slug must be a string',
            'slug.max'          => 'Post URL slug cannot be longer then 191 characters',
            'author_id.integer' => 'An error occurred. Please refresh the page.',
            'revision.required' => 'An error occurred. Please refresh the page.',
            'revision.integer'  => 'An error occurred. Please refresh the page.',
        ];
    }

    public function type()
    {
        return $this->belongsTo(PostType::class, 'post_type_id', 'id');
    }

    public function getUrlAttribute()
    {
        return route('posts.show', [$this->type->slug, $this->slug]);
    }
}
