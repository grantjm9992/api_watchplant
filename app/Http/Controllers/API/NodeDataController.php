<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\NodeData;
use App\Http\Resources\NodeDataResource;
   
class NodeDataController extends BaseController
{
    public function import(Request $request)
    {
        $data = $request->all();
        

        $response = null;

        if ($this->isAssoc($data)) {
            $validator = Validator::make($data, [
                'node_id' => 'required',
            ]);
       
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }
            $response = NodeData::create($data);
        } else {
            $response = array();
            foreach ($data as $row) {
                $validator = Validator::make($row, [
                    'node_id' => 'required',
                ]);
           
                if($validator->fails()){
                    return $this->sendError('Validation Error.', $validator->errors());       
                }
                $response[] = NodeData::create($row);
            }
        }

        return $this->sendResponse($response, 'Data inserted correctly');
    }

    public function retrieve(Request $request, $nodeId)
    {
        $data = NodeData::where('node_id', $nodeId)->orderBy('date', 'DESC');
        $dataLimit = ($request->has('data_size')) ? (int)$request->data_size : 100;
        $data->limit($dataLimit);
        if ($request->has('data_type')) {
            $data->select('date', 'node_id', $request->data_type);
        }
        return $this->sendResponse(NodeDataResource::collection($data->get()), 'Data retrieved successfully.');
    }

}