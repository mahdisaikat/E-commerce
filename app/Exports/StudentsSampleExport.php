<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class StudentsSampleExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function array(): array
    {
        // Return sample data (2 empty rows for demonstration)
        return [
            [
                '', // name_bn
                'John Doe', // name_en
                'Robert Doe', // father_name
                '01234567890', // father_phone
                'Business', // father_occupation
                'Mary Doe', // mother_name
                '01234567891', // mother_phone
                'Teacher', // mother_occupation
                'Village: XYZ, Post: ABC, District: Dhaka', // permanent_address
                'House# 123, Road# 456, Dhaka', // present_address
                '2015-05-15', // dob (YYYY-MM-DD)
                'BC123456789', // birth_certificate_no
                'Islam', // religion
                'Male', // gender
                'Class 1', // class_applied
                'Bangladeshi', // nationality
                '0', // fee_waiver (0 or 1)
                '101', // roll
                'Morning', // shift
                'john@example.com', // email
                '01234567892', // phone
                'Self', // transport_type
                '1000.00', // monthly_fee
            ],
            [
                '', // name_bn
                'Jane Smith', // name_en
                'Michael Smith', // father_name
                '01234567893', // father_phone
                'Engineer', // father_occupation
                'Sarah Smith', // mother_name
                '01234567894', // mother_phone
                'Doctor', // mother_occupation
                'Village: PQR, Post: DEF, District: Chittagong', // permanent_address
                'House# 789, Road# 101, Chittagong', // present_address
                '2016-08-20', // dob
                'BC987654321', // birth_certificate_no
                'Christian', // religion
                'Female', // gender
                'Class 2', // class_applied
                'Bangladeshi', // nationality
                '1', // fee_waiver
                '102', // roll
                'Day', // shift
                'jane@example.com', // email
                '01234567895', // phone
                'School Van', // transport_type
                '1200.00', // monthly_fee
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'name_bn', // বাংলা নাম
            'name_en', // English Name
            'father_name', // Father's Name
            'father_phone', // Father's Phone
            'father_occupation', // Father's Occupation
            'mother_name', // Mother's Name
            'mother_phone', // Mother's Phone
            'mother_occupation', // Mother's Occupation
            'permanent_address', // Permanent Address
            'present_address', // Present Address
            'dob', // Date of Birth (YYYY-MM-DD)
            'birth_certificate_no', // Birth Certificate No
            'religion', // Religion
            'gender', // Gender (Male, Female, Others)
            'class_applied', // Class Applied
            'nationality', // Nationality
            'fee_waiver', // Fee Waiver (0=No, 1=Yes)
            'roll', // Roll Number
            'shift', // Shift
            'email', // Email
            'phone', // Phone
            'transport_type', // Transport Type (Self, School Van)
            'monthly_fee', // Monthly Fee
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row (headings) as bold
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['argb' => 'FFE6E6FA'] // Light purple background
                ]
            ],
            // Freeze the first row
            'A1' => ['freeze' => true],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15, // name_bn
            'B' => 20, // name_en
            'C' => 20, // father_name
            'D' => 15, // father_phone
            'E' => 20, // father_occupation
            'F' => 20, // mother_name
            'G' => 15, // mother_phone
            'H' => 20, // mother_occupation
            'I' => 30, // permanent_address
            'J' => 30, // present_address
            'K' => 15, // dob
            'L' => 20, // birth_certificate_no
            'M' => 15, // religion
            'N' => 10, // gender
            'O' => 15, // class_applied
            'P' => 15, // nationality
            'Q' => 12, // fee_waiver
            'R' => 10, // roll
            'S' => 10, // shift
            'T' => 25, // email
            'U' => 15, // phone
            'V' => 15, // transport_type
            'W' => 15, // monthly_fee
        ];
    }
}