<?php

namespace Tests\Stubs;

use Illuminate\Database\Eloquent\Model;
use Arukomp\Bloggy\Models\Traits\ValidatesModel;

class ValidationModelStub extends Model
{
    use ValidatesModel;

    protected $fillable = [
        'first',
        'second'
    ];

    protected $validationRules = [
        'first' => 'string',
        'second' => 'integer|max:100'
    ];
}
