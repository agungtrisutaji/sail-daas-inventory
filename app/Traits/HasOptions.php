<?php

namespace App\Traits;

trait HasOptions
{
    public static function options(): array
    {
        $cases = self::cases();

        return array_map(function ($elm) {
            $isMethodExist = method_exists(__CLASS__, 'getDescription');
            $value = property_exists($elm, 'value') ? $elm->value : $elm->name;

            return [
                'value' => $value,
                'code' => $value,
                'key' => $value,
                'label' => $isMethodExist
                    ? self::getLabel()
                    : $elm->getLabel(),
            ];
        }, $cases);
    }
}
