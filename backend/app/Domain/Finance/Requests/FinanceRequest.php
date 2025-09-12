<?php

namespace App\Domain\Finance\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FinanceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'office_id' => 'nullable|exists:offices,id',
            'article_id' => 'nullable|exists:articles,id',
            'user_id' => 'nullable|exists:users,id',
            'year' => 'nullable|integer|min:1900|max:2100',
            'month' => 'nullable|integer|min:1|max:12',
            'day' => 'nullable|integer|min:1|max:31',
            'date' => 'nullable|date',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
        ];
    }
}
