<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait HasAdvanceQuery
{
    protected function checkAlreadyJoined($query, $table)
    {
        $joins = $query->getQuery()->joins;
        if ($joins == null) {
            return false;
        }
        foreach ($joins as $join) {
            if ($join->table == $table) {
                return true;
            }
        }

        return false;
    }

    protected function checkIsUniqueValue(mixed $search, array|null|Collection $arr, mixed $key): bool
    {
        if (!empty($arr)) {
            foreach ($arr as $idx => $item) {
                if (collect($item)->get($key) === $search?->label) {
                    return false;
                }
            }
        }

        return true;
    }

    protected function makeArrayRequest(&$arr, $content, $source, $key)
    {
        if ($content) {
            if ($key === 'identites') {
                $arr[$key] = [
                    ...(!is_null($source) ? $source : []),
                    [
                        'id' => Str::orderedUuid()->toString(),
                        'type' => 'KTP',
                        'identity_number' => $content?->label,
                        'documents' => [],
                    ],
                ];
            }
            $arr[$key] = [
                ...(!is_null($source) ? $source : []),
                [
                    'id' => Str::orderedUuid()->toString(),
                    'type' => '-',
                    'content' => $content?->label,
                ],
            ];
        }
    }

    protected function filterDateRange(
        Builder $query,
        string|Expression $column,
        ?string $filterStart,
        ?string $filterEnd,
        ?string $columnEnd = null,
        ?bool $withoutStartEndDay = false
    ): Builder {
        $columnStart = $column;
        $columnEnd = $columnEnd ?? $column;

        if ($filterStart) {
            $dateStart = new Carbon($filterStart);
            $start = $withoutStartEndDay ? $dateStart : $dateStart->startOfDay();
            $query->where($columnStart, '>=', $start);
        }

        if ($filterEnd) {
            $dateEnd = new Carbon($filterEnd);
            $end = $withoutStartEndDay ? $dateEnd : $dateEnd->endOfDay();
            $query->where($columnEnd, '<=', $end);
        }

        return $query;
    }

    protected function getQueryFullname(
        ?array $columns = [
            'contact.first_name',
            'contact.middle_name',
            'contact.last_name',
        ]
    ): string {
        $column = '';
        // concat all column name
        for ($i = 0; $i < count($columns); $i++) {
            $column .= $columns[$i];
            if ($i !== (count($columns) - 1)) {
                $column .= ', ';
            }
        }

        $queryFullname = 'ARRAY_TO_STRING(
            ARRAY(
                SELECT e FROM (SELECT DISTINCT e, idx FROM
                    UNNEST(ARRAY[' . $column . "])
                WITH ORDINALITY AS t(e, idx) ORDER BY idx) as e),
            ' ')";

        return 'CASE
            WHEN ' . $queryFullname . " = ' ' THEN null
            WHEN " . $queryFullname . " = '' THEN null
            ELSE " . $queryFullname . ' END';
    }

    protected function getQueryStripTags(string $column, ?bool $toLower = false): string
    {
        $queryStrip = 'REPLACE(
                REGEXP_REPLACE(
                    REGEXP_REPLACE(' . $column . ", E'<[^>]+>', '', 'gi'),
                '^\\s+', '', 'g'),
            '\"','')";

        if ($toLower) {
            $queryStrip = 'LOWER(' . $queryStrip . ')';
        }

        return 'CASE WHEN ' . $queryStrip . " = '' THEN null ELSE " . $queryStrip . 'END';
    }

    protected function localeToDate(string $column): string
    {
        $monthTranslation = [
            'Februari' => 'February',
            'Maret' => 'March',
            'Mei' => 'May',
            'Juni' => 'June',
            'Juli' => 'July',
            'Agustus' => 'August',
            'Oktober' => 'October',
            'Desember' => 'December',
        ];
        $query = "REPLACE($column, 'Januari', 'January')";

        foreach ($monthTranslation as $key => $value) {
            $query = "REPLACE($query, '$key', '$value')";
        }

        return "to_date($query, 'DD Month YYYY')";
    }
}
