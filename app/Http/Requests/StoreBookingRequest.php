<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'spa_service_id'   => ['required', Rule::exists('spa_services', 'id')->where('is_active', true)],
            'therapist_id'     => ['required', Rule::exists('therapists', 'id')->where('status', 'ready')],
            'booking_date'     => ['required', 'date', 'after_or_equal:today'],
            'booking_time'     => ['required', 'string'],
            'duration'         => ['required', 'integer', 'min:30', 'max:240'],
            'payment_method'   => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'spa_service_id.required'     => 'Layanan spa harus dipilih.',
            'therapist_id.required'       => 'Therapist harus dipilih.',
            'therapist_id.exists'         => 'Terapis yang dipilih saat ini sedang tidak tersedia (belum ready/sedang melayani/off). Silakan pilih terapis lain yang berstatus Ready.',
            'booking_date.after_or_equal' => 'Tanggal booking tidak boleh di masa lalu.',
        ];
    }
}
