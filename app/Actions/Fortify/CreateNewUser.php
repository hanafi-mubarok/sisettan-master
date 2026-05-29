<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        $isKaryawan = isset($input['is_karyawan']) && $input['is_karyawan'] === 'yes';

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'nik' => [
                'required',
                'string',
                'max:255',
                Rule::unique(User::class, 'nik'),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
            'selfie_ktp' => ['nullable', 'file', 'image', 'max:2048'],
            'kartu_keluarga' => ['nullable', 'file', 'image', 'max:2048'],
        ];

        // Add rules for non-employee fields only if they submitted optional fields
        if (!$isKaryawan) {
            $rules['phone'] = ['nullable', 'string', 'max:20'];
            $rules['address'] = ['nullable', 'string', 'max:255'];
            $rules['province'] = ['nullable', 'string', 'max:255'];
            $rules['city'] = ['nullable', 'string', 'max:255'];
        }

        Validator::make($input, $rules)->validate();

        $userData = [
            'name' => $input['name'],
            'nik' => $input['nik'],
            'email' => $input['email'],
            'username' => $input['email'],
            'password' => Hash::make($input['password']),
            'is_karyawan' => $isKaryawan,
        ];

        // If employee, set other fields to null
        if ($isKaryawan) {
            $userData['phone'] = null;
            $userData['address'] = null;
            $userData['province'] = null;
            $userData['city'] = null;
        } else {
            // If not employee, include the provided optional fields
            $userData['phone'] = $input['phone'] ?? null;
            $userData['address'] = $input['address'] ?? null;
            $userData['province'] = $input['province'] ?? null;
            $userData['city'] = $input['city'] ?? null;
        }

        // Handle file uploads for both employee and non-employee users.
        // Stored in the same columns as existing registration flow.
        if (!empty($input['selfie_ktp']) && $input['selfie_ktp']->isValid()) {
            $path = $input['selfie_ktp']->storeAs(
                'uploads/users/selfie-ktp',
                $input['selfie_ktp']->hashName(),
                'public'
            );
            $userData['selfie_ktp_path'] = $path;
        } else {
            $userData['selfie_ktp_path'] = null;
        }

        if (!empty($input['kartu_keluarga']) && $input['kartu_keluarga']->isValid()) {
            $path = $input['kartu_keluarga']->storeAs(
                'uploads/users/kartu-keluarga',
                $input['kartu_keluarga']->hashName(),
                'public'
            );
            $userData['kartu_keluarga_path'] = $path;
        } else {
            $userData['kartu_keluarga_path'] = null;
        }

        return User::create($userData);
    }
}
