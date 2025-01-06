<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StorePodcastRequest extends FormRequest
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
            'banner' => ['required','image','mimes:jpeg,png,jpg,gif,svg', 'max:5120'],
            'title' => ['required','string'],
            'description' => ['required','string'],
            'video_url' => ['nullable','string'],
            'audio_url' => ['nullable','string'],
            'tags' => ['required','string'],
            'category' => ['required','string'],
            // 'approved_by' => ['required','string'],
            // 'views' => ['required','integer'],
            // 'likes' => ['required','integer'],
            // 'shares' => ['required','integer'],
        ];
    }
}
