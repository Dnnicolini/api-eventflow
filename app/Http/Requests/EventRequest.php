<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_date' => ['date'],
            'end_time' => ['date_format:H:i'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'numeric'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'location_id' => ['required', 'exists:locations,id'],
            'organizer_id' => ['required', 'exists:users,id'],
        ];
    }
}
