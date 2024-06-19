<?php

namespace App\Filament\Resources\BloggerResource\Pages;

use App\Filament\Resources\BloggerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBloggers extends ListRecords
{
    protected static string $resource = BloggerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
