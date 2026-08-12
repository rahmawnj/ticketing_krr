<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;

class CreateMemberRequest extends FormRequest
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
            'rfid' => 'nullable|string|unique:members',
            'nama' => 'required|string',
            'alamat' => 'required|string',
            'no_ktp' => 'nullable|string',
            'no_hp' => 'required|numeric',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'membership' => "required|integer|exists:memberships,id",
            'image_profile' => 'nullable|mimes:png,jpg,jpeg',

            'rfid_group' => 'nullable|array',
            'name_group' => 'nullable|array',
            'no_hp_group' => 'nullable|array',
            'tanggal_lahir_group' => 'nullable|array',
            'image_group' => 'nullable|array',

            'rfid_group.*' => 'nullable|string|unique:members,rfid',
            'name_group.*' => 'required_with:rfid_group.*,no_hp_group.*,tanggal_lahir_group.*,image_group.*|nullable|string|max:255',
            'no_hp_group.*' => 'nullable|numeric',
            'tanggal_lahir_group.*' => 'nullable|date',
            'image_group.*' => 'required_with:rfid_group.*,name_group.*,no_hp_group.*,tanggal_lahir_group.*|nullable|mimes:png,jpg,jpeg',
        ];
    }
}
