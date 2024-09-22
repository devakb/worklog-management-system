<?php

namespace App\Http\Requests\Projects;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
            "code"          =>  "required|unique:projects,code",
            "full_name"     =>  "required",
            "client_name"   =>  "required",
            "client_email"  =>  "required|email",
        ];
    }

    public function attributes(){
        return [
            "code"          =>  "Project Code",
            "full_name"     =>  "Full Name",
            "client_name"   =>  "Client Name",
            "client_email"  =>  "Client Email",
        ];
    }
}
