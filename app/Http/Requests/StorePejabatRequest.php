<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePejabatRequest extends FormRequest
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
            'nama_karyawan' => 'required|unique:midi_employee,nama_karyawan',
            'id_jabatan' => 'required',
            'nik' => 'required',
            'gender' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'jabatan_id.required' => 'Nama Jabatan Wajib Diisi',
            'nama_karyawan.required' => 'Nama Karyawan Wajib Diisi',
            'nama_karyawan.unique' => 'Nama Karyawan Sudah Ada',
            'nik.required' => 'NIK Wajib Diisi',
            'gender.required' => 'Gender Wajib Diisi',
        ];
    }
}
