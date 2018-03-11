<?php

namespace Arukomp\Bloggy\Models\Traits;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait KeepRevisionHistory
{
    private $keepCurrentRevision = false;

    public static function bootKeepRevisionHistory()
    {
        static::addGlobalScope('without_revision', function (Builder $builder) {
            $builder->whereNull('revision_parent_post_id');
        });
    }

    /**
     * Saves the model
     */
    public function save(array $options = [])
    {
        if ($this->isHistory() === false && $this->keepCurrentRevision === false) {
            $this->makeHistory()->increaseRevision();
        }

        parent::save($options);
    }

    /**
     * Increase the model's revision number
     */
    public function increaseRevision($increment = 1)
    {
        return $this->setAttribute(
            'revision',
            $this->getAttribute('revision') + $increment
        );
    }

    /**
     * Makes a copy of the model and saves it as history
     */
    public function makeHistory()
    {
        if (!is_null($this->id)) {
            $copy = self::withTrashed()->find($this->id)->replicate();
            $copy->revision_parent_post_id = $this->id;
            $copy->save();
        }

        return $this;
    }

    /**
     * Whether this model is history or not
     */
    public function isHistory()
    {
        return !is_null($this->getAttribute('revision_parent_post_id'));
    }

    /**
     * Don't include history and don't increase revision
     */
    public function withoutHistory()
    {
        $this->keepCurrentRevision = true;

        return $this;
    }
}
