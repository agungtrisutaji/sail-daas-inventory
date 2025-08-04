<?php

namespace App\Services;

use App\Models\Deployment;
use Carbon\Carbon;

class ContractService
{
    /**
     * Kategori unit dan durasi kontrak dalam tahun
     */
    const DURATION_MAPPINGS = [
        'laptop' => 3,
        'desktop' => 4
    ];

    /**
     * Menghitung end_contract_date berdasarkan bast_date dan kategori unit
     */
    public function calculateEndContractDate(string $category, ?string $bastDate): ?string
    {
        if (empty($bastDate)) {
            return null;
        }

        $duration = self::DURATION_MAPPINGS[strtolower($category)] ?? null;

        if (!$duration) {
            throw new \InvalidArgumentException("Invalid unit category: {$category}");
        }

        return Carbon::parse($bastDate)->addYears($duration)->format('Y-m-d H:i');
    }
}
