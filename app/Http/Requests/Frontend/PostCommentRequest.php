<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

use Auth;

class PostCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $nameRule = Auth::guest() ? 'required|string' : 'nullable|string';
        $emailRule = Auth::guest() ? 'required|email' : 'nullable|email';

        return [
            'body' => 'required',
            'name' => $nameRule,
            'email' => $emailRule,
        ];
    }
}
