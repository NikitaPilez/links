<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\StatisticService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RedirectController extends Controller
{
    public function exportCsv(Request $request, StatisticService $statisticService): StreamedResponse
    {
        $mode = $request->input('mode', 'date');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $geo = json_decode($request->input('geo'), true);
        $bloggerIds = json_decode($request->input('blogger_ids'), true);
        $linkIds = json_decode($request->input('link_ids'), true);
        $generateLinkIds = json_decode($request->input('generate_link_ids'), true);

        /** @var User $user */
        $user = auth()->user();

        $query = $statisticService->getStatistic($user, $mode, $dateFrom, $dateTo, $geo, $bloggerIds, $linkIds, $generateLinkIds);

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=statistics.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        if ($mode === 'date') {
            $field = 'Дата';
        } else if ($mode === 'geo') {
            $field = 'Гео';
        } else if ($mode === 'blogger') {
            $field = 'Блоггер';
        } else if ($mode === 'scenario') {
            $field = 'Сценарий';
        } else if ($mode === 'link') {
            $field = 'Ссылка';
        } else if ($mode === 'generate_link') {
            $field = 'Сгенерированная ссылка';
        }

        $columns = [$field ?? '', 'Кол-во'];

        $callback = function() use ($query, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            $generator = function() use ($query) {
                foreach ($query->cursor() as $row) {
                    yield [$row->field, $row->count];
                }
            };

            foreach ($generator() as $data) {
                fputcsv($file, $data);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}
