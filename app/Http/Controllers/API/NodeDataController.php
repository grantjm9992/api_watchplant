<?php

namespace App\Http\Controllers\API;

use App\DataField;
use App\Nodes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Validator;
use App\NodeData;
use App\Http\Resources\NodeDataResource;

class NodeDataController extends BaseController
{
    protected const HANDLE = 'handle';
    protected const NODE_HANDLE = 'node_handle';
    protected const NODE_HANDLES = 'node_handles';
    protected const DATA_SIZE = 'data_size';
    protected const DATA_TYPE = 'data_type';

    public function import(Request $request): JsonResponse
    {
        $data = $request->all();

        if ($this->isAssoc($data)) {
            $validator = Validator::make($data, [
                self::NODE_HANDLE => 'required',
            ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());
            }
            $response = NodeData::create($data);
        } else {
            $response = array();
            foreach ($data as $row) {
                $validator = Validator::make($row, [
                    self::NODE_HANDLE => 'required',
                ]);

                if($validator->fails()){
                    return $this->sendError('Validation Error.', $validator->errors());
                }
                $response[] = NodeData::create($row);
            }
        }

        return $this->sendResponse($response, 'Data inserted correctly');
    }

    public function retrieve($nodeId, Request $request): JsonResponse
    {
        /*$data = NodeData::where(self::NODE_HANDLE, $nodeId)->orderBy('date', 'DESC');
        $dataLimit = ($request->has(self::DATA_SIZE)) ? (int)$request->get('data_size') : 50;
        $data->limit($dataLimit);
        if ($request->has(self::DATA_TYPE)) {
            $data->select('date', 'node_handle', $request->get('data_type'));
        }

        $data = $data->get()->toArray();
        $data = array_reverse($data);*/
        $responseData = [];
        $node = Nodes::where(self::HANDLE, $nodeId)
            ->get()
            ->first()
            ->toArray();

        $dataFields = DataField::all()->toArray();

        if ($request->query('date_range') == 'latest') {
            $data = $this->throughIntervals(
                $node,
                $this->getDayIntervalArray()
            );
            $responseData = $data;
        }

        if ($request->query('date_range') == 'month') {
            $data = $this->throughIntervals(
                $node,
                $this->getMonthIntervalArray()
            );
            $responseData = $data;
        }

        if ($request->query('date_range') == 'twelve_months') {

            $data = $this->throughIntervals(
                $node,
                $this->getSixMonthIntervalArray()
            );
            $responseData = $data;
        }


        $responseData['fields'] = $dataFields;

        return $this->sendResponse($responseData, 'Data retrieved successfully.');
    }

    public function retrieveForMultipleNodes(Request $request): JsonResponse
    {
        $nodeIds = $this->request[self::NODE_HANDLES];
        $responseData = [];
        foreach ($nodeIds as $nodeId) {
            $node = Nodes::where(self::HANDLE, $nodeId)
                ->get()
                ->first()
                ->toArray();

            $dataField = DataField::where(self::HANDLE, $request->query->get(self::DATA_TYPE))
                ->get()
                ->first()
                ->toArray();

            if ($this->request['date_range'] == 'latest') {

                $data = $this->throughIntervals(
                    $node,
                    $this->getDayIntervalArray()
                );
                $data['unit'] = $dataField['unit'];
                $responseData[] = $data;

            }

            if ($this->request['date_range'] == 'month') {

                $data = $this->throughIntervals(
                    $node,
                    $this->getMonthIntervalArray()
                );
                $data['unit'] = $dataField['unit'];
                $responseData[] = $data;
            }

            if ($this->request['date_range'] == 'twelve_months') {

                $data = $this->throughIntervals(
                    $node,
                    $this->getSixMonthIntervalArray()
                );
                $data['unit'] = $dataField['unit'];
                $responseData[] = $data;
            }
        }
        return $this->sendResponse($responseData, 'Data retrieved successfully');
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
        for ($i = 0; $i < 12; $i++) {
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
            'data' => array_reverse($data),
            'node_handle' => $node['handle'],
            'node_name' => $node['name']
        );
    }

}
