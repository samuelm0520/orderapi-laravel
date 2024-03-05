<?php

namespace App\Http\Controllers;

use App\Models\Technician;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class TechnicianController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    private $rules = [
        'document' => 'required|integer|max:99999999999999999999|min:3',
        'name' => 'required|string|max:80|min:3',
        'especiality' => 'string|max:50|min:2',
        'phone' => 'string|max:30|min:4',
    ];
    private $traductionAttributes = [
        'document' => 'documento',
        'name' => 'nombre',
        'especiality' => 'especialidad',
        'phone' => 'telefono',
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
        $techinicians = Technician::all();
        return response()->json($techinicians, Response::HTTP_OK);
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

        $techinician = Technician::create($request->all());
        $response = [
            'message' => 'Registro creado exitosamente',
            'causal' => $techinician
        ];

        return response()->json($response, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Technician $technician)
    {
        return response()->json($technician, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Technician $technician)
    {
        $data = $this->applyvalidator($request);
        if (!empty($data))
        {
            return $data;
        }

        $technician -> update($request ->all(''));
        $response = [
            'message' => 'Registro actualizado exitosamente',
            'technician' => $technician
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Technician $technician)
    {
        $technician -> delete();
        $response = [
            'message' => 'Registro eliminado exitosamente',
            'technician' => $technician->id
        ];

        return response()->json($response, Response::HTTP_OK);
    }
}
