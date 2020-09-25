<?php

namespace TobiasDierich\Gauge\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class QueriesController extends Controller
{
    public function index(Request $request)
    {
        return 'Hello World';
    }
}
