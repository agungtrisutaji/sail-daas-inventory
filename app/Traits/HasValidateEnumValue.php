<?php

namespace App\Traits;

use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;

trait HasValidateEnumValue
{
    use RemembersRowNumber;
    private function validateAndGetEnumValue($value, string $enumClass, string $fieldName = 'value', bool $allowNull = false)
    {
        if ($value === null) {
            if ($allowNull) {
                return $enumClass::cases()[0]; // Return default value (first case)
            }
            throw ValidationException::withMessages([
                $fieldName => " {$this->getRowNumber()} : {$fieldName} is required.",
            ]);
        }


        // Jika status adalah string, coba konversi ke integer jika mungkin
        if (is_string($value)) {
            if (ctype_digit($value)) {
                $value = (int) $value;
            } else {
                // Jika string bukan angka, coba cocokkan dengan nama enum
                $value = strtoupper($value);
            }
        }


        foreach ($enumClass::cases() as $case) {
            if (
                (is_string($case->value) && strtolower($case->value) === $value) ||
                $case->value === $value ||
                $case->name === $value
            ) {
                return $case;
            }
        }

        throw ValidationException::withMessages([
            $fieldName => " {$this->getRowNumber()} : Invalid {$fieldName} value: {$value}. ",
        ]);
    }
}
