<?php

namespace App\Http\Requests\Api\front;

use App\Enum\UserType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'=>'required|string|max:255|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'image' =>'nullable|mimes:png,jpg,jpeg|max:2048',
            'mobile' => ['required', 'regex:/^01[0125][0-9]{8}$/','unique:users,mobile'],
            
            'department_id' => [
                 Rule::requiredIf(function () {
                 return request('user_type') == UserType::Doctor->value;
               }),'nullable','exists:departments,id'],

             'surgery_id' => [
                 Rule::requiredIf(function () {
                 return request('user_type') == UserType::Patient->value;
               }),'nullable','exists:surgeries,id'],   

             'allergy_id' => [
                 Rule::requiredIf(function () {
                 return request('user_type') == UserType::Patient->value;
               }),'nullable','exists:allergies,id'], 

            'disease_id' => [
                 Rule::requiredIf(function () {
                 return request('user_type') == UserType::Patient->value;
               }),'nullable','exists:diseases,id'], 

            'blood_id' => [
                 Rule::requiredIf(function () {
                 return request('user_type') == UserType::Patient->value;
               }),'nullable','exists:bloods,id'], 

            'user_type' => ['required', 'integer', Rule::in(array_column(UserType::cases(), 'value'))],
        
            ];
}
}