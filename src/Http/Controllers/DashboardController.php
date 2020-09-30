<?php

namespace TobiasDierich\Gauge\Http\Controllers;

use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    /**
     * Display the Gauge dashboard.
     *
     * \Illuminate\Http\Response
     */
    public function index()
    {
        return view('gauge::dashboard');
    }
}
