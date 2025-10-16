<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StudentsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new Student([
            'student_id'        => Student::generateStudentId(),
            'name_bn'           => $row['name_bn'] ?? null,
            'name_en'           => $row['name_en'] ?? null,
            'father_name'       => $row['father_name'] ?? null,
            'father_phone'      => $row['father_phone'] ?? null,
            'father_occupation' => $row['father_occupation'] ?? null,
            'mother_name'       => $row['mother_name'] ?? null,
            'mother_phone'      => $row['mother_phone'] ?? null,
            'mother_occupation' => $row['mother_occupation'] ?? null,
            'permanent_address' => $row['permanent_address'] ?? null,
            'present_address'   => $row['present_address'] ?? null,
            'dob'               => $row['dob'] ?? null,
            'birth_certificate_no' => $row['birth_certificate_no'] ?? null,
            'religion'          => $row['religion'] ?? null,
            'gender'            => $row['gender'] ?? null,
            'class_applied'     => $row['class_applied'] ?? null,
            'nationality'       => $row['nationality'] ?? null,
            'fee_waiver'        => $row['fee_waiver'] ?? false,
            'roll'              => $row['roll'] ?? null,
            'shift'             => $row['shift'] ?? null,
            'email'             => $row['email'] ?? null,
            'phone'             => $row['phone'] ?? null,
            'transport_type'    => $row['transport_type'] ?? null,
            'monthly_fee'       => $row['monthly_fee'] ?? null,
            'status'            => $row['status'] ?? 1,
        ]);
    }

    public function rules(): array
    {
        return [
            'name_bn'           => 'nullable|string|max:255',
            'name_en'           => 'nullable|string|max:255',
            'father_name'       => 'nullable|string|max:255',
            'father_phone'      => 'nullable|string|max:20',
            'father_occupation' => 'nullable|string|max:255',
            'mother_name'       => 'nullable|string|max:255',
            'mother_phone'      => 'nullable|string|max:20',
            'mother_occupation' => 'nullable|string|max:255',
            'permanent_address' => 'nullable|string',
            'present_address'   => 'nullable|string',
            'dob'               => 'nullable|date',
            'birth_certificate_no' => 'nullable|string|max:100',
            'religion'          => 'nullable|string|max:50',
            'gender'            => 'nullable|in:Male,Female,Others',
            'class_applied'     => 'nullable|string|max:100',
            'nationality'       => 'nullable|string|max:100',
            'fee_waiver'        => 'nullable|boolean',
            'roll'              => 'nullable|max:50',
            'shift'             => 'nullable|string|max:50',
            'email'             => 'nullable|email',
            'phone'             => 'nullable|string|max:20',
            'transport_type'    => 'nullable|in:Self,School Van',
            'monthly_fee'       => 'nullable|numeric|min:0',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'email.unique' => 'Email already exists',
            'phone.unique' => 'Phone number already exists',
        ];
    }
}