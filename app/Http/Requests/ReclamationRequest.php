<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReclamationRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'nom' => 'required',
            'telephone' => 'required_without:email',
            'email' => 'required_without:telephone|email',
            'reference' => 'required',
            'type' => 'required',
            'description' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'nom.required' => "Le champ 'nom' est requis.",
            'telephone.required_without' => "Le champ 'tel' est requis si la valeur 'mail' est absente.",
            'email.required_without' => "Le champ 'mail' est requis si la valeur 'tel' est absente.",
            'reference.required' => "Le champ 'reference courte' est requis.",
            'type.required' => "Le champ 'type reclamation' est requis.",
            'description.required' => "Le champ 'description' est requis.",
        ];
    }
}
