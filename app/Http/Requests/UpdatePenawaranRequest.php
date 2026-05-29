<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePenawaranRequest extends FormRequest
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
            'id_user' => 'nullable|integer',
            'name' => 'nullable|string',
            'idfk_barang' => 'nullable|integer',
            'aset_id' => 'nullable|string',
            'idfk_daftar' => 'nullable|integer',
            'id_daftar' => 'nullable|string',
            'idfk_tkd' => 'nullable|integer',
            'id_tkd' => 'nullable|string',
            'nilai_penawaran' => 'required',
            'keterangan' => 'nullable|string'
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'nilai_penawaran' => str_replace(['Rp', '.', ','], '', (string) $this->nilai_penawaran),
        ]);
    }
}
