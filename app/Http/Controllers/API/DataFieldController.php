<?php

namespace App\Http\Controllers\API;

use App\DataField;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DataFieldController extends BaseController
{
    public function create(Request $request): JsonResponse
    {
        $data = $request->all();
        $response = DataField::create($data);

        return $this->sendResponse($response, 'Data field created correctly');
    }
}
