<?php

namespace App\Filament\Pages;

use App\Models\GenerateLink;
use App\Models\Redirect;
use App\Models\User;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

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

        $query = Redirect::query();

        /** @var User $user */
        $user = auth()->user();

        if ($dateFrom) {
            $query->whereDate('redirects.created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('redirects.created_at', '<=', $dateTo);
        }

        if ($geo) {
            $query->whereIn('geo', $geo);
        }

        if ($mode === 'date') {
            $selects = [
                DB::raw('COUNT(gl.id) as count'),
                DB::raw('DATE(gl.created_at) as date')
            ];
            $groupBy = 'date';

        } else if ($mode === 'geo') {
            $selects = [
                DB::raw('COUNT(gl.id) as count'),
                'geo',
            ];
            $groupBy = 'geo';
        } else if ($mode === 'blogger') {
            $selects = [
                DB::raw('COUNT(gl.id) as count'),
                'gl.blogger_id',
                DB::raw('b.name as name'),
            ];
            $groupBy = 'gl.blogger_id';
        } else if ($mode === 'scenario') {
            $selects = [
                DB::raw('COUNT(gl.id) as count'),
                DB::raw('gl.scenario as scenario'),
            ];
            $groupBy = 'gl.scenario';
        }

        if ($user->role !== 1) {
            $userId = $user->id;

            $generateLinkIds = GenerateLink::where('user_id', $userId)->pluck('id')->toArray();

            $query->whereIn('gl.id', $generateLinkIds);
        }

        $query->select(
            $selects
        )
            ->join('generate_links as gl', 'gl.id', '=', 'redirects.generate_link_id')
            ->join('bloggers as b', 'b.id', '=', 'gl.blogger_id')
            ->orderByDesc('count')
            ->groupBy($groupBy);

        $redirects = $query->paginate(20);

        return [
            'redirects' => $redirects,
            'mode' => $mode,
            'allGeos' => Redirect::select('geo')->distinct()->pluck('geo'),
        ];
    }
}
