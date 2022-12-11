<?php

namespace App\Http\Controllers\API;

use App\DataField;
use App\Http\Resources\DataFieldResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class DataFieldController extends BaseController
{
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

        $response = DataField::create($data);

        return $this->sendResponse($response, 'Data field created correctly');
    }

    public function listAll(): JsonResponse
    {
        $data = DataField::get();
        return $this->sendResponse(DataFieldResource::collection($data), 'Data fields retrieved successfully.');
    }

    public function delete(): JsonResponse
    {
        /**@var DataField $dataField*/
        $dataField = DataField::where('handle', $this->request['handle'])
            ->get()
            ->first();

        $dataField->delete();

        return $this->sendResponse([], 'Deleted');
    }
}
