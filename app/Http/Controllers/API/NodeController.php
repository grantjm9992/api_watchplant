<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Validator;
use App\Nodes;
use App\Http\Resources\NodesResource;

class NodeController extends BaseController
{
    public function retrieve(): JsonResponse
    {
        $data = Nodes::get();
        return $this->sendResponse(NodesResource::collection($data), 'Nodes retrieved successfully.');
    }

    public function create(Request $request): JsonResponse
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'handle' => 'required',
            'name' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $response = Nodes::create($data);

        return $this->sendResponse($response, 'Node created correctly');
    }

    public function delete(Request $request): JsonResponse
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'handle' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $nodes = Nodes::where('handle', $data['handle'])->delete();

        return $this->sendResponse('ok', 'Node deleted');
    }

}
