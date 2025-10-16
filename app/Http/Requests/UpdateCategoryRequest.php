<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function rules(): array
    {
        $id = $this->route('product_category')?->id ?? $this->route('product_category');

        return [
            'title' => 'sometimes|required|string|max:255',
            'parent_id' => [
                'nullable',
                'exists:product_categories,id',
                function ($attribute, $value, $fail) use ($id) {
                    if ($value && $value == $id) {
                        $fail('parent_id не может быть равен id категории.');
                    }
                }
            ],
        ];
    }
}
