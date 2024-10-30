<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
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
        return [
            'rating' => 'required',
            'comment' => 'max:400',
            'image_url' => 'file|mimes:jpeg,png'
        ];
    }

    public function messages()
    {
        return [
            'rating.required' => '評価数を選択してください',
            'comment.max' => '400字以内で入力してください',
            'image_url.file' => '有効なファイルをアップロードしてください',
            'image_url.mimes' => 'アップロード可能なファイル形式は、jpeg,png のみです'
        ];
    }
}
