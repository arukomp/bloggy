<?php

namespace Arukomp\Bloggy\Tests\Stubs;

class ValidationModelWithValidationMessagesStub extends ValidationModelStub
{
    protected $validationMessages = [
        'first.string' => 'First is not a string!',
        'second.integer' => 'Second is not an integer!',
        'second.max' => 'Second number is too large.'
    ];
}
