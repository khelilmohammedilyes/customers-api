<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user=$this->user();
        return $user != null && $user->tokenCan('create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            '*.customerId'=>['required','integer'],
            '*.amount'=>['required','numeric'],
            '*.status'=>['required',Rule::in(['B','P','V','b','p','v'])],
            '*.billedDate'=>['required','date_format:y-m-d h:i:s'],
            '*.paidDate'=>['required','date_format:y-m-d h:i:s','nullable'],
        ];
    }

    protected function prepareForValidation()
    {
        $data=[];
        foreach($this->toArray() as $obj){
            $obj['customer_id']=$obj['customerId'] ?? null;
            $obj['billed_date']=$obj['billedDate'] ?? null;
            $obj['paid_date']=$obj['paidDate'] ?? null;

            $data[]=$obj;
        }
        $this->merge($data);
    }
}
