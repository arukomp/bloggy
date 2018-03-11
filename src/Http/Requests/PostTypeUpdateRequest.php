<?php

namespace Arukomp\Bloggy\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostTypeUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'plural' => 'required',
            'slug' => 'required|unique:post_types,slug'
        ];
    }

    /**
     * Validation messages
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'plural.required' => 'Plural form is required',
            'slug.required' => 'URL slug is required',
            'slug.unique' => ':input slug is already in use by a different post type'
        ];
    }
}
