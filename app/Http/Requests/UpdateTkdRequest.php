<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTkdRequest extends FormRequest
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

    public function rules()
    {
        $id = $this->route('tkd')->id;
        return [
            'aset_id' => 'nullable|string|max:255',
            'id_branch' => 'required|integer',
            'nama_barang' => 'nullable|string|max:255',
            'kondisi' => 'nullable|string|max:255',
            'kategori' => 'required|string|max:255',
            'merk' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'harga_dasar' => 'required|numeric',
            'kelipatan' => 'nullable|integer',
            'tahun' => 'nullable|integer',
            'status' => 'nullable|string|max:191',
            'keterangan' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:10000',
            'tgl_start_penawaran' => 'nullable|date_format:Y-m-d\TH:i|date',
            'tgl_akhir_penawaran' => 'nullable|date_format:Y-m-d\TH:i|date',
        ];
    }

    public function messages()
    {
        return [
            'id_branch.required' => 'Branch wajib diisi',
            'kategori.required' => 'Kategori wajib diisi',
            'merk.required' => 'Merk wajib diisi',
            'lokasi.required' => 'Lokasi wajib diisi',
            'harga_dasar.required' => 'Harga Dasar wajib diisi',
            'harga_dasar.numeric' => 'Harga Dasar harus berupa angka',
            'kelipatan.integer' => 'Kelipatan harus berupa angka',
            'tahun.integer' => 'Tahun harus berupa angka',
            'foto.image' => 'Foto Wajib Sesuai Format',
            'foto.mimes' => 'Foto Tidak Sesuai Format',
            'foto.max' => 'Foto Melebihi Maksimal Ukuran',
            'tgl_start_penawaran.date_format' => 'Format tanggal mulai penawaran tidak sesuai (YYYY-MM-DDTHH:mm)',
            'tgl_akhir_penawaran.date_format' => 'Format tanggal akhir penawaran tidak sesuai (YYYY-MM-DDTHH:mm)',
        ];
    }
}
