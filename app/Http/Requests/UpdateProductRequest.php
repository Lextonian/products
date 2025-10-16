<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function rules(): array
    {
        $productId = $this->route('product')?->id ?? $this->route('product');

        return [
            'title' => 'sometimes|required|string|max:255',
            'slug' => [
                'sometimes','required','string','max:255',
                Rule::unique('products','slug')->ignore($productId),
            ],
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:product_categories,id',
        ];
    }
}
