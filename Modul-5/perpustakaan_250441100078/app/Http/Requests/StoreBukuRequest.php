<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBukuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Soal 2e: Izinkan request
    }

    public function rules(): array
    {
        // Menentukan tahun saat ini secara dinamis (2026)
        $tahunSekarang = date('Y');
        
        return [
            'kategori_id' => 'required|exists:kategoris,id',
            'judul' => 'required|string|min:3|max:150',
            'penulis' => 'required|string|min:3|max:100',
            'tahun_terbit' => "required|integer|between:1900,{$tahunSekarang}",
        ];
    }

    // Soal 2c: Custom error message bahasa Indonesia
    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'min' => ':attribute minimal berisi :min karakter.',
            'max' => ':attribute maksimal berisi :max karakter.',
            'integer' => ':attribute harus berupa bilangan bulat.',
            'between' => ':attribute harus antara :min sampai :max.',
            'exists' => ':attribute yang dipilih tidak valid.',
        ];
    }

    // Soal 2d: Atribut ramah
    public function attributes(): array
    {
        return [
            'kategori_id' => 'Kategori',
            'judul' => 'Judul buku',
            'penulis' => 'Penulis',
            'tahun_terbit' => 'Tahun terbit',
        ];
    }
}