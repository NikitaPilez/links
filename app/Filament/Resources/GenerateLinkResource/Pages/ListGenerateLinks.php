<?php

namespace App\Filament\Resources\GenerateLinkResource\Pages;

use App\Filament\Resources\GenerateLinkResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGenerateLinks extends ListRecords
{
    protected static string $resource = GenerateLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
