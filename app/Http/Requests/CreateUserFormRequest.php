<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserFormRequest extends FormRequest
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
        switch ($this->method()) {
            case "GET":
            case "DELETE":
                return [];
                break;

            case "POST":
                $this->sanitize();
                return [
                    'name'         => 'required|max:100',
                    'email'        => 'required|unique:users|email'
                ];
                break;

            case "PUT":
            case "PATCH":
                $this->sanitize();
                return [
                    'name'         => 'required|max:100',
                    'email'        => 'required|email',
                    'password'     => 'sometimes|required|min:5|confirmed'
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
        $input['name']                  = filter_var($input['name'], FILTER_SANITIZE_STRING);
        $input['email']                 = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
        // $input['password']              = filter_var($input['password'], FILTER_SANITIZE_STRING);
        // $input['password_confirmation'] = filter_var($input['password_confirmation'], FILTER_SANITIZE_STRING);
        // $input['user_role_id']          = filter_var($input['user_role_id'], FILTER_SANITIZE_NUMBER_INT);

        $this->replace($input);
    }
}
