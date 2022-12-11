<?php

namespace App\Http\Controllers;

use App\DataField;
use App\NodeData;
use App\Nodes;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): Factory|View|Application
    {
        $nodes = Nodes::all()->sortBy('name')->toArray();
        $dataFields = DataField::all()->sortBy('name')->toArray();
        return view('home', [
            'nodes' => $nodes,
            'dataFields' => $dataFields
        ]);
    }

    private function getDayIntervalArray(): array
    {
        $dateArray = [];
        $t1 = new \DateTime();
        $interval = new \DateInterval('PT1H');
        for ($i = 0; $i < 24; $i++) {
            $date = $t1->sub($interval);
            $dateArray[] = array(
                'from' => $date->format('Y-m-d H:00:00'),
                'to' => $date->format('Y-m-d H:59:59'),
                'date' => $date->format('Y-m-d H:00'),
            );
        }
        return $dateArray;
    }

    private function getMonthIntervalArray(): array
    {
        $dateArray = [];
        $t1 = new \DateTime();
        $interval = new \DateInterval('P1D');
        for ($i = 0; $i < $t1->format('t'); $i++) {
            $date = $t1->sub($interval);
            $dateArray[] = array(
                'from' => $date->format('Y-m-d 00:00:00'),
                'to' => $date->format('Y-m-d 23:59:59'),
                'date' => $date->format('Y-m-d'),
            );
        }
        return $dateArray;
    }

    private function getSixMonthIntervalArray(): array
    {
        $dateArray = [];
        $t1 = new \DateTime();
        $interval = new \DateInterval('P1M');
        for ($i = 0; $i < 6; $i++) {
            $date = $t1->sub($interval);
            $dateArray[] = array(
                'from' => $date->format('Y-m-01 00:00:00'),
                'to' => $date->format('Y-m-t 23:59:59'),
                'date' => $date->format('Y-m'),
            );
        }
        return $dateArray;
    }

    private function throughIntervals(array $node, array $dateArray): array
    {
        $data = [];

        foreach ($dateArray as $hour) {
            $result = NodeData::where('node_handle', $node['handle'])
                ->where('date', '<=', $hour['to'])
                ->where('date', '>=', $hour['from'])
                ->get()
                ->toArray();
            $dataFields = DataField::all()->toArray();
            $averages = [];
            $totals = [];
            $count = [];
            foreach ($dataFields as $field) {
                $totals[$field['handle']] = 0;
                $count[$field['handle']] = 0;
            }
            foreach ($result as $row) {
                $parsedData = $row['data'];
                foreach ($parsedData as $field => $value) {
                    if (array_key_exists($field, $totals)) {
                        $totals[$field] += (float)$value;
                        $count[$field]++;
                    }
                }
            }

            foreach ($totals as $field => $value) {
                if ($count[$field] === 0) {
                    continue;
                }
                $averages[$field] = round($value/$count[$field], 2);
            }

            $data[] = [
                'date' => $hour['date'],
                'data' => $averages
            ];
        }

        return array(
            'data' => $data,
            'node_handle' => $node['handle'],
            'node_name' => $node['name']
        );
    }

    public function allData(): Factory|View|Application
    {
        $nodes = Nodes::all()->sortBy('name')->toArray();
        return view('all-data', [
            'nodes' => $nodes
        ]);
    }
}
