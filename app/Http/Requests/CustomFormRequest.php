<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'action' => 'required|string|max:255',
            'method' => 'required|in:GET,POST,PUT,PATCH,DELETE',
            'status' => 'nullable|string|in:draft,published,archived',
            'fields' => 'required|array',
            'fields.*.id' => 'nullable|integer|exists:form_fields,id',
            'fields.*.name' => 'required|string|max:255',
            'fields.*.type' => 'required|string|in:text,email,textarea,select',
            'fields.*.label' => 'nullable|string|max:255',
            'fields.*.placeholder' => 'nullable|string|max:255',
            'fields.*.required' => 'boolean',
            'fields.*.position' => 'required|integer',
            'fields.*.class_name' => 'nullable|string|max:255',
            'fields.*.custom_style' => 'nullable|string',
            'fields.*.options' => 'nullable|array',
            'fields.*.options.*.label' => 'required_with:fields.*.options|string|max:255',
            'fields.*.options.*.value' => 'required_with:fields.*.options|string|max:255',
        ];
    }
}
