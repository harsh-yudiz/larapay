<?php

namespace App\Http\Requests\Paypal\Plan;

use Illuminate\Foundation\Http\FormRequest;

class createPlan extends FormRequest
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
            'planname' => 'required',
            'description' => 'required',
            'tax' => 'required|numeric',
            'productdefinitionname' => 'required',
            'productdefinitionname.*' => 'required',
            'planprice' => 'required|numeric',
            'planprice.*' => 'required|numeric',
            'plantype' => 'required|numeric',
            'plantype.*' => 'required|numeric',
            'planfrequency' =>  'required',
            'planfrequency.*' =>  'required',

        ];
    }
}
