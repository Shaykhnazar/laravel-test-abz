<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Make sure this is true to allow the request
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Get the current route name
        $routeName = $this->route()->getName();

        // Use match to provide different rules for different routes
        return match ($routeName) {
            'users.index' => [
                'page'  => 'integer|min:1',
                'count' => 'integer|min:1',
            ],
            'users.store' => [
                'name'       => 'required|string|min:2|max:60',
                'email'      => 'required|email|unique:users,email|email:rfc,dns', // RFC2822 validation
                'phone'      => ['required', 'regex:/^\+380\d{9}$/', 'unique:users,phone'],
                'position_id'=> 'required|integer|exists:positions,id',
                'photo'      => 'required|image|mimes:jpeg,jpg|max:5120|dimensions:min_width=70,min_height=70',
            ],
            default => [],
        };
    }

    /**
     * Customize the validation messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'page.integer' => 'The page parameter must be an integer.',
            'page.min'     => 'The page parameter must be at least 1.',
            'count.integer' => 'The count parameter must be an integer.',
            'count.min'    => 'The count parameter must be at least 1.',
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.unique' => 'This email is already registered.',
            'email.email' => 'The email must be a valid email address according to RFC2822.',
            'phone.required' => 'The phone field is required.',
            'phone.regex' => 'The phone must start with +380 and contain 9 digits.',
            'position_id.required' => 'The position id is required.',
            'position_id.integer' => 'The position id must be an integer.',
            'position_id.exists' => 'The provided position ID does not exist.',
            'photo.required' => 'A photo is required.',
            'photo.mimes' => 'The photo must be a jpeg or jpg file.',
            'photo.max' => 'The photo may not be greater than 5 Mbytes.',
            'photo.dimensions' => 'The photo dimensions must be at least 70x70 pixels.',
        ];
    }

}
