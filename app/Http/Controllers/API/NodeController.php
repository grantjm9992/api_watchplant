<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Nodes;
use App\Http\Resources\NodesResource;

class NodeController extends BaseController
{
    public function retrieve(Request $request): JsonResponse
    {
        $data = Nodes::get();
        return $this->sendResponse(NodesResource::collection($data), 'Nodes retrieved successfully.');
    }

}
