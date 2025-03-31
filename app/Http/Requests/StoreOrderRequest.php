<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'Müşteri ID zorunludur.',
            'customer_id.exists'   => 'Geçerli bir müşteri ID giriniz.',
            'items.required'       => 'Sipariş kalemleri zorunludur.',
            'items.array'          => 'Sipariş kalemleri geçerli bir formatta olmalıdır.',
            'items.min'            => 'En az bir sipariş kalemi olmalıdır.',
            'items.*.product_id.required' => 'Ürün ID zorunludur.',
            'items.*.product_id.exists'   => 'Geçerli bir ürün ID giriniz.',
            'items.*.quantity.required'   => 'Ürün adedi zorunludur.',
            'items.*.quantity.integer'    => 'Ürün adedi tamsayı olmalıdır.',
            'items.*.quantity.min'        => 'Ürün adedi en az 1 olmalıdır.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json(['errors' => $errors], 422));
    }
}
