<?php

namespace App\Http\Controllers\RsCoding;

use App\Http\Controllers\Controller;

/**
 * Controller responsible for rendering the main RsCoding pages.
 *
 * This controller only returns Blade views and does not perform
 * any request handling or external HTTP calls.
 */
class HomeController extends Controller
{
    /**
     * Render the homepage.
     */
    public function index()
    {
        return view('rscoding.index');
    }

    /**
     * Render the projects overview page.
     */
    public function projects()
    {
        return view('rscoding.projects');
    }

    /**
     * Render the Laravel project overview page.
     */
    public function laravel()
    {
        return view('rscoding.laravel');
    }

    /**
     * Render the Laravel IoT demo page.
     */
    public function laravelDemo()
    {
        return view('rscoding.laravel-demo');
    }
}
