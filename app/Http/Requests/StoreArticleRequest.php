<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
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
        $rules = [
            'title'       => 'required|string',
            'description' => 'required|string',
            'category_id' => 'required',
            'tag_id'    => 'required|array',
            'tag_id.*'    => 'exists:tags,id',
        ];
         if ($this->is('api/*')) {
            $rules['image'] = 'nullable|mimes:jpeg,jpg,png';
        } elseif ($this->isMethod('POST')) {
           // For store
            $rules['image'] = 'required|mimes:jpeg,jpg,png';
        } elseif ($this->isMethod('PATCH') || $this->isMethod('PUT')) {
            // For update
            $rules['image'] = 'nullable|mimes:jpeg,jpg,png';
        }
        return $rules;
    }
    public function messages(): array
    {
         $messages = [
            'title.required'       => 'Title is required',
            'description.required' => 'Description is required',
            'category_id.required' => 'Select Category',
            'image.mimes'          => 'Invalid image format. Only jpeg, jpg, png allowed.',
            'tag_id.required' => 'Please select at least one tag.',
            'tag_id.array'    => 'Invalid tag format.',
            'tag_id.*.exists' => 'One or more selected tags are invalid.',
        ];

        // Add only when image is required (store case)
        if ($this->isMethod('post')) {
            $messages['image.required'] = 'Image is required';
        }

        return $messages;

    }
}
