<?php

namespace Arukomp\Bloggy\Models\Traits;

use Validator as ValidatorFacade;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Validator;
use Illuminate\Validation\ValidationException;

trait ValidatesModel
{
    /**
     * Validator instance
     *
     * @var \Illuminate\Validation\Validator
     */
    private $validator = null;

    /**
     * Constructor for the trait
     */
    public static function bootValidatesModel()
    {
        static::saving(function ($model) {
            if (!$model->isValid()) {
                throw new ValidationException($model->getValidator());
            }
        });
    }

    /**
     * Validation result as true or false
     *
     * @return bool
     */
    public function isValid() : bool
    {
        return $this->getValidator()->passes();
    }

    /**
     * Runs the actual validation and returns the result as a boolean
     *
     * @return bool
     */
    public function validate($data = null, $rules = null, $messages = null) : self
    {
        $data = $data ?? $this->getAttributes();
        $rules = $rules ?? $this->getValidationRules();
        $messages = $messages ?? $this->getValidationMessages();

        $this->validator = ValidatorFacade::make($data, $rules, $messages);

        return $this;
    }

    /**
     * Get validation errors as an associative array
     *
     * @return MessageBag
     */
    public function errors() : MessageBag
    {
        return $this->getValidator()->errors();
    }

    /**
     * Get the validator instance
     *
     * @return \Illuminate\Validation\Validator
     */
    public function getValidator() : Validator
    {
        if (is_null($this->validator)) {
            $this->validate();
        }

        return $this->validator;
    }

    /**
     * Get Validation Rules
     *
     * @return array
     */
    public function getValidationRules()
    {
        if (property_exists($this, 'validationRules')) {
            return $this->validationRules;
        }

        return [];
    }

    /**
     * Get Validation messages
     *
     * @return array
     */
    public function getValidationMessages()
    {
        if (property_exists($this, 'validationMessages')) {
            return $this->validationMessages;
        }

        return [];
    }
}
