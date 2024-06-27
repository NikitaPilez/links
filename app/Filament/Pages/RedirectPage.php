<?php

namespace App\Filament\Pages;

use App\Models\Blogger;
use App\Models\GenerateLink;
use App\Models\Link;
use App\Models\Redirect;
use App\Models\User;
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
        $bloggerIds = $request->input('blogger_ids');
        $linkIds = $request->input('link_ids');
        $generateLinkIds = $request->input('generate_link_ids');

        /** @var User $user */
        $user = auth()->user();

        /** @var StatisticService $statisticService */
        $statisticService = app(StatisticService::class);
        $query = $statisticService->getStatistic($user, $mode, $dateFrom, $dateTo, $geo, $bloggerIds, $linkIds, $generateLinkIds);

        if ($user->role === 1) {
            $allBloggers = Blogger::get()->pluck('name', 'id')->toArray();
            $allLinks = Link::get()->pluck('name', 'id')->toArray();
            $allGenerateLinks = GenerateLink::get()->pluck('generated_link', 'id')->toArray();
        } else {
            $allBloggers = Blogger::whereHas('users', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get()->pluck('name', 'id')->toArray();

            $allLinks = Link::whereHas('users', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get()->pluck('name', 'id')->toArray();

            $allGenerateLinks = GenerateLink::where('user_id', $user->id)->get()->pluck('generated_link', 'id')->toArray();
        }

        return [
            'redirects' => $query->paginate(20),
            'mode' => $mode,
            'allBloggers' => $allBloggers,
            'allLinks' => $allLinks,
            'allGenerateLinks' => $allGenerateLinks,
            'allGeos' => Redirect::select('geo')->distinct()->pluck('geo'),
        ];
    }
}
