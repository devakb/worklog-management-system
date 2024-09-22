<?php

namespace App\Http\Requests\WorkLogs;

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
            'project_id' => 'required',
            'work_duration_in_minutes' => 'required|numeric',
            'work_description' => 'required',
            'date_of_work' => 'required|date',
        ];
    }

    public function attributes(){
        return [
            'project_id' => 'Project ID',
            'work_duration_in_minutes' => 'Work Duration',
            'work_description' => 'Work Description',
            'date_of_work' => 'Date of Work',
        ];
    }
}
