<?php

namespace App\Traits;

trait HasEnumQuery
{
    public static function getQuery(string $column, ?bool $lastNullItems = false): string
    {
        $cases = self::cases();

        $query = "CASE\n";
        $onNullValue = $lastNullItems ? 'null' : "'-'";
        foreach ($cases as $case) {
            $description = '-';

            if (method_exists(self::class, 'getDescription')) {
                $description = self::getDescription($case->value);
            }

            if (method_exists(self::class, 'getLabel')) {
                $description = self::getLabel($case->value);
            }

            $query .= "WHEN $column = '".$case->value."' THEN '$description'\n";
        }

        $query .= "ELSE $onNullValue END";

        return $query;
    }
}
