<?php

namespace App\Http\Controllers;

use App\Models\GenerateLink;
use App\Models\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MainController extends Controller
{
    public function index(string $alias, string $scenario): RedirectResponse
    {
        /** @var GenerateLink $link */
        $generatedLink = GenerateLink::where('scenario', $scenario)->whereHas('blogger', function ($query) use ($alias) {
            $query->where('alias', $alias);
        })->first();

        if (!$generatedLink) {
            throw new NotFoundHttpException();
        }

        Redirect::create([
            'geo' => $_SERVER['HTTP_CF_IPCOUNTRY'] ?? null,
            'generate_link_id' => $generatedLink->id,
        ]);

        return redirect()->to($generatedLink->link->url);
    }
}
