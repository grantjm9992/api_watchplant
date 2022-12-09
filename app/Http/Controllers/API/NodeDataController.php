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
        $data = NodeData::where(self::NODE_HANDLE, $nodeId)->orderBy('date', 'DESC');
        $dataLimit = ($request->has(self::DATA_SIZE)) ? (int)$request->get('data_size') : 50;
        $data->limit($dataLimit);
        if ($request->has(self::DATA_TYPE)) {
            $data->select('date', 'node_handle', $request->get('data_type'));
        }

        $data = $data->get()->toArray();
        $data = array_reverse($data);

        return $this->sendResponse(NodeDataResource::collection($data), 'Data retrieved successfully.');
    }

    public function retrieveForMultipleNodes(Request $request): JsonResponse
    {
        $dataLimit = array_key_exists(self::DATA_SIZE, $this->request) ? (int)$this->request[self::DATA_SIZE] : 50;
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

            $data = NodeData::where(self::NODE_HANDLE, $nodeId)
                ->orderBy('date', 'DESC')
                ->limit($dataLimit)
                ->get()
                ->toArray();

            $data = array_reverse($data);
            $responseData[] = array(
                'node_name' => $node['name'],
                'node_handle' => $nodeId,
                'data' => $data,
                'unit' => $dataField['unit']
            );
        }
        return $this->sendResponse($responseData, 'Data retrieved successfully');
    }
}
