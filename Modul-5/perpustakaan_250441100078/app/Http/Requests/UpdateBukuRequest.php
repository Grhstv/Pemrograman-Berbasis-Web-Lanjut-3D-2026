<?php

namespace App\Http\Requests;

class UpdateBukuRequest extends StoreBukuRequest
{
    public function rules(): array
    {
        // Mengambil semua aturan dari StoreBukuRequest
        $rules = parent::rules();

        // Mengubah semua aturan menjadi opsional saat proses Update (PATCH/PUT)
        foreach ($rules as $key => $rule) {
            $rules[$key] = 'sometimes|' . $rule;
        }

        return $rules;
    }
}