<?php

namespace App\Http\Controllers;

use App\Nodes;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): Factory|View|Application
    {
        $nodes = Nodes::all()->toArray();
        return view('home', [
            'nodes' => $nodes
        ]);
    }

    public function allData(): Factory|View|Application
    {
        $nodes = Nodes::all()->toArray();
        return view('all-data', [
            'nodes' => $nodes
        ]);
    }
}
