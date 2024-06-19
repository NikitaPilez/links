<?php

namespace App\Filament\Resources\GenerateLinkResource\Pages;

use App\Filament\Resources\GenerateLinkResource;
use App\Models\Blogger;
use App\Models\Domain;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditGenerateLink extends EditRecord
{
    protected static string $resource = GenerateLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $bloggerId = $data['blogger_id'];
        $domainId = $data['domain_id'];
        $scenario = $data['scenario'];

        $blogger = Blogger::find($bloggerId);
        $domain = Domain::find($domainId);

        $generateLink = $domain->name . '/' . $blogger->alias . $scenario;

        $record->update([
            'blogger_id' => $bloggerId,
            'link_id' => $data['link_id'],
            'domain_id' => $domainId,
            'user_id' => auth()->user()->id,
            'scenario' => $scenario,
            'generated_link' => $generateLink,
        ]);

        return $record;
    }
}
