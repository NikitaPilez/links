<?php

namespace App;

use App\Models\GenerateLink;
use App\Models\Redirect;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class StatisticService
{
    public function getStatistic(string $mode, ?string $dateFrom, ?string $dateTo, ?array $geo): Builder
    {
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
                DB::raw('DATE(gl.created_at) as field')
            ];
            $groupBy = 'field';

        } else if ($mode === 'geo') {
            $selects = [
                DB::raw('COUNT(gl.id) as count'),
                'geo as field',
            ];
            $groupBy = 'field';
        } else if ($mode === 'blogger') {
            $selects = [
                DB::raw('COUNT(gl.id) as count'),
                'gl.blogger_id',
                DB::raw('b.name as field'),
            ];
            $groupBy = 'gl.blogger_id';
        } else if ($mode === 'scenario') {
            $selects = [
                DB::raw('COUNT(gl.id) as count'),
                DB::raw('gl.scenario as field'),
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

        return $query;
    }
}
