<?php

namespace App\Filament\Pages;

use App\Models\Redirect;
use Filament\Pages\Page;

class RedirectPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.redirect-page';

    protected function getViewData(): array
    {
        $redirects = Redirect::select('id', 'generate_link_id', 'geo', 'created_at')
            ->orderByDesc('created_at')
            ->get();

        return ['redirects' => $redirects];
    }
}
