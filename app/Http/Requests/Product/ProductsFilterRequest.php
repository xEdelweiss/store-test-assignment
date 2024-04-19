<?php

namespace App\Http\Requests\Product;

use App\DTOs\Product\ProductFilterDto;
use Illuminate\Foundation\Http\FormRequest;

class ProductsFilterRequest extends FormRequest
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
            'max_price' => 'nullable|numeric',
            'min_price' => 'nullable|numeric',
            'title' => 'nullable|string',
        ];
    }

    public function toDto(): ProductFilterDto
    {
        return new ProductFilterDto(
            $this->validated('max_price'),
            $this->validated('min_price'),
            $this->validated('title')
        );
    }
}
