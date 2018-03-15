<?php

namespace Arukomp\Bloggy\Tests\Stubs;

use Arukomp\Bloggy\Models\Traits\ValidatesModel;
use Illuminate\Database\Eloquent\Model;

class ValidationModelStub extends Model
{
    use ValidatesModel;

    protected $fillable = [
        'first',
        'second',
    ];

    protected $validationRules = [
        'first'  => 'string',
        'second' => 'integer|max:100',
    ];
}
