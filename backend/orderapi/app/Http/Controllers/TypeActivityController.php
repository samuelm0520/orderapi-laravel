<?php

namespace App\Http\Controllers;

use App\Models\TypeActivity;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class TypeActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $rules = [
        'description' => 'required|string|max:50|min:3',
        
    ];
    private $traductionAttributes = [
        'description' => 'descripcion',
    ];

    public function applyvalidator(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules);
        $validator->setAttributeNames($this->traductionAttributes);
        $data = [];
        if ($validator->fails()) 
        {
            $data = response()->json([
                'errors'=>$validator->errors(),
                'data' =>$request->all()
            ], Response::HTTP_BAD_REQUEST);
        }

        return $data;
    }

    public function index()
    {
        $type_technicians = TypeActivity::all();
        return response()->json($type_technicians, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->applyvalidator($request);
        if (!empty($data))
        {
            return $data;
        }

        $type_technician = TypeActivity::create($request->all());
        $response = [
            'message' => 'Registro creado exitosamente',
            'type_technician' => $type_technician
        ];

        return response()->json($response, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(TypeActivity $type_technician)
    {
        return response()->json($type_technician, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TypeActivity $type_technician)
    {
        $data = $this->applyvalidator($request);
        if (!empty($data))
        {
            return $data;
        }

        $type_technician -> update($request ->all(''));
        $response = [
            'message' => 'Registro actualizado exitosamente',
            'type_technician' => $type_technician
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TypeActivity $type_technician)
    {
        $type_technician -> delete();
        $response = [
            'message' => 'Registro eliminado exitosamente',
            'type_technician' => $type_technician->id
        ];

        return response()->json($response, Response::HTTP_OK);
    }
}
