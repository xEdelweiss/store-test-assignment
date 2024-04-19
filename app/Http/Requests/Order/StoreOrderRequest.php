<?php

namespace App\Http\Requests\Order;

use App\DTOs\Order\CartDto;
use App\DTOs\Order\CartItemDto;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ];
    }

    public function toDto(): CartDto
    {
        return new CartDto(
            array_map(
                fn ($item) => new CartItemDto($item['product_id'], $item['quantity']),
                $this->validated('items'),
            )
        );
    }
}
