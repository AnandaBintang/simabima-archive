<?php

namespace App\Filament\Resources\ArchiveCategoryResource\Pages;

use App\Filament\Resources\ArchiveCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateArchiveCategory extends CreateRecord
{
    protected static string $resource = ArchiveCategoryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
