<?php

namespace App\Traits;

use App\Services\DocumentNumberService;

trait HasDocumentNumber
{
    public static function bootHasDocumentNumber()
    {
        static::creating(function ($model) {
            $config = $model->getDocumentConfig();

            if (empty($model->{$config['column']})) {
                $generator = new DocumentNumberService(
                    $config['prefix'],
                    $config['column'],
                    $model->getDocDateFormat(),
                    $model->getDocNumberPadding()
                );

                $model->{$config['column']} = $generator->generate($model);
            }
        });
    }

    // Default values yang bisa di-override di model
    public function getDocumentConfig(): array
    {
        return [
            'prefix' => 'DOC-',
            'column' => 'document_number'
        ];
    }

    public function getDocDateFormat(): string
    {
        return 'ym';
    }

    public function getDocNumberPadding(): int
    {
        return 4;
    }
}
