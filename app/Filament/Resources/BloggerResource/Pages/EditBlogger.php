<?php

namespace App\Filament\Resources\BloggerResource\Pages;

use App\Filament\Resources\BloggerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBlogger extends EditRecord
{
    protected static string $resource = BloggerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
