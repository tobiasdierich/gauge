<?php

namespace TobiasDierich\Gauge\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class RequestsController extends Controller
{
    public function index(Request $request)
    {
        return 'Hello World';
    }
}
