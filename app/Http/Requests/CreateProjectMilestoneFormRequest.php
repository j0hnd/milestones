<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProjectMilestoneFormRequest extends FormRequest
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
            // 'pid'                  => 'required',
            'announcement'         => 'nullable|date|date_format:m/d/Y',
            'scoping_design'       => 'nullable|date|date_format:m/d/Y',
            'advertising'          => 'nullable|date|date_format:m/d/Y',
            'award'                => 'nullable|date|date_format:m/d/Y',
            'commencement'         => 'nullable|date|date_format:m/d/Y',
            '20_percent_complete'  => 'nullable|date|date_format:m/d/Y',
            '40_percent_complete'  => 'nullable|date|date_format:m/d/Y',
            '60_percent_complete'  => 'nullable|date|date_format:m/d/Y',
            '80_percent_complete'  => 'nullable|date|date_format:m/d/Y',
            'practical_completion' => 'nullable|date|date_format:m/d/Y'
        ];
    }
}
