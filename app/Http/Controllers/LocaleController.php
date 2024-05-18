<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;

class LocaleController extends Controller
{
    public function __invoke(string $locale): RedirectResponse
    {
        app()->setLocale($locale);
        Carbon::setLocale($locale);
        session()->put('locale', $locale);
        return redirect()->back();
    }
}
