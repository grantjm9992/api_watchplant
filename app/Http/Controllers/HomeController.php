<?php

namespace App\Http\Controllers;

use App\DataField;
use App\Nodes;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): Factory|View|Application
    {
        $nodes = Nodes::all()->toArray();
        $dataFields = DataField::all()->toArray();
        return view('home', [
            'nodes' => $nodes,
            'dataFields' => $dataFields
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
