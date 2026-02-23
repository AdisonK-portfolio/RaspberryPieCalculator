<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CalculationRequest extends FormRequest
{
    public function rules() {
        /* Allowed characters: 0-9 (with decimals), +, -, *, /, ., (), √(), and ^2 */
        return [
            'string' => ['required','max:50', 'regex:/^([0-9]|(\^2)|(\√\()|\(|\)|[\.\+\-\*\/])+$/']
        ];
    }

    public function messages(){

        return ['string' => "Oops, looks like there is something in your equation that isn't allowed"];
    }
}
