<?php

namespace App\Filament\Pages;

use App\Models\Redirect;
use App\StatisticService;
use Filament\Pages\Page;

class RedirectPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.redirect-page';

    protected static ?string $navigationLabel = 'Статистика';

    protected static ?string $title = 'Статистика';

    protected function getViewData(): array
    {
        $request = request();
        $mode = $request->input('mode', 'date');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $geo = $request->input('geo');

        /** @var StatisticService $statisticService */
        $statisticService = app(StatisticService::class);
        $query = $statisticService->getStatistic($mode, $dateFrom, $dateTo, $geo);

        return [
            'redirects' => $query->paginate(20),
            'mode' => $mode,
            'allGeos' => Redirect::select('geo')->distinct()->pluck('geo'),
        ];
    }
}
