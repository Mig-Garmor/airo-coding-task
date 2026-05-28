<?php

namespace App\Http\Requests;

use App\Rules\ValidAgeList;
use Illuminate\Foundation\Http\FormRequest;

class QuotationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'age' => ['required', new ValidAgeList],
            'currency_id' => ['required', 'string', 'in:EUR,GBP,USD'],
            'start_date' => ['required', 'date_format:Y-m-d'],
            'end_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:start_date'],
        ];
    }

    public function parsedAges(): array
    {
        return array_map(
            static fn (string $age): int => (int) trim($age),
            explode(',', $this->string('age')->toString())
        );
    }
}
