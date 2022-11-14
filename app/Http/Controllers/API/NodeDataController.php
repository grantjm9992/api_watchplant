<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Validator;
use App\NodeData;
use App\Http\Resources\NodeDataResource;

class NodeDataController extends BaseController
{
    public function importFromFiles(Request $request): JsonResponse
    {
        $i = 0;
        while ($i < 2) {
            $handle = fopen(__DIR__ . "/rpi$i.csv", "r");
            $row = 1;
            $keys = [];
            $ass = [];
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                if ($row === 1) {
                    for ($c=0; $c < $num; $c++) {
                        $string = $data[$c];
                        $string = str_replace('-', '_', $string);
                        $string = strtolower($string);
                        if ($string === 'timestamp') {
                            $keys[0] = 'date';
                        }
                        else {
                            $keys[$c] = $string;
                        }
                    }
                }
                else {
                    $thisRow = [];
                    $dataField = [];
                    for ($c=0; $c < $num; $c++) {
                        $dataField[$keys[$c]] = $data[$c];
                    }
                    $thisRow['node_handle']  = "test_node_$i";
                    $thisRow['data'] = $dataField;
                    $thisRow['date'] = $data[0];
                    $ass[] = $thisRow;
                }

                $row++;
            }

            $this->import($request, $ass);
            $i++;
        }


        return $this->sendResponse(null, '');
    }

    public function import(Request $request, $testDataFromFile = null): JsonResponse
    {
        $data = $request->all();

        if ($testDataFromFile !== null) {
            $data = $testDataFromFile;
        }

        $response = null;

        if ($this->isAssoc($data)) {
            $validator = Validator::make($data, [
                'node_handle' => 'required',
            ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());
            }
            $response = NodeData::create($data);
        } else {
            $response = array();
            foreach ($data as $row) {
                $validator = Validator::make($row, [
                    'node_handle' => 'required',
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
        $data = NodeData::where('node_handle', $nodeId)->orderBy('date', 'DESC');
        $dataLimit = ($request->has('data_size')) ? (int)$request->data_size : 50;
        $data->limit($dataLimit);
        if ($request->has('data_type')) {
            $data->select('date', 'node_handle', $request->data_type);
        }

        $data = $data->get()->toArray();
        $data = array_reverse($data);

        return $this->sendResponse(NodeDataResource::collection($data), 'Data retrieved successfully.');
    }

    public function retrieveForMultipleNodes(Request $request): JsonResponse
    {
        $dataLimit = array_key_exists('data_size', $this->request) ? (int)$this->request->data_size : 50;
        $nodeIds = $this->request['node_handles'];
        $responseData = [];
        foreach ($nodeIds as $nodeId) {
            $data = NodeData::where('node_handle', $nodeId)->orderBy('date', 'DESC');
            $data->limit($dataLimit);
            $data = $data->get()->toArray();
            $data = array_reverse($data);
            $responseData[$nodeId] = $data;
        }
        return $this->sendResponse($responseData, 'Data retrieved successfully');
    }
}
