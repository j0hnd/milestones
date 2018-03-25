<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProjectFormRequest extends FormRequest
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
        $this->sanitize();

        switch ($this->method()) {
            case "GET":
            case "DELETE":
                return [];
                break;

            case "POST":
                return [
                    'project_name'    => 'required|max:100|unique:projects',
                    'project_code'    => 'max:9|unique:projects',
                    'project_type_id' => 'required'
                ];
                break;

            case "PUT":
            case "PATCH":
                return [
                    'project_name'    => 'required|max:100',
                    'project_code'    => 'max:9',
                    'project_type_id' => 'required'
                ];
                break;

            default:
                break;
        }
    }

    /**
     * Sanitize input fields
     *
     */
    public function sanitize()
    {
        $input = $this->all();
        $input['project_name']    = filter_var($input['project_name'], FILTER_SANITIZE_STRING);
        $input['project_code']    = filter_var($input['project_code'], FILTER_SANITIZE_STRING);
        $input['project_type_id'] = filter_var($input['project_type_id'], FILTER_SANITIZE_NUMBER_INT);

        $this->replace($input);
    }
}
