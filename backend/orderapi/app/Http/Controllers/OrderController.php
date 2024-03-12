<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{

    private $rules = [
        'legalization_date' => 'required|date_format:Y-m-d',
        'addres' => 'required|string|max:50|min:3',
        'city' => 'required|string|max:50|min:3',
        'observation_id' => 'numeric',
        'causal_id' => 'numeric',
    ];
    private $traductionAttributes = [
        'legalization_date' => 'fecha de legalización',
        'addres' => 'dirección',
        'city' => 'ciudad',
        'observation_id' => 'observacion',
        'causal_id' => 'causal',
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

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::all();
        $orders->load(['causal','observation']); //Si es una se mete solo entre parentesis si son dos o mas se pone con []
        return response()->json($orders, Response::HTTP_OK);
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

        $order = Order::create($request->all());
        $response = [
            'message' => 'Registro creado exitosamente',
            'order' => $order
        ];

        return response()->json($response, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load(['causal','observation']);
        return response()->json($order, Response::HTTP_OK);
    }


    public function update(Request $request, Order $order)
    {
        $data = $this->applyvalidator($request);
        if (!empty($data))
        {
            return $data;
        }

        $order -> update($request ->all(''));
        $response = [
            'message' => 'Registro actualizado exitosamente',
            'order' => $order
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order -> delete();
        $response = [
            'message' => 'Registro eliminado exitosamente',
            'order' => $order->id
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * remueve una actividad a una orden
     */

    public function add_activity(Order $order, Activity $activity)
    {
        $order->activities()->attach($activity->id);
        $response = [
            'message' => 'Actividad agregada exitosamente',
            'order_activity' => $order->activities
        ];

        return response()->json($response, Response::HTTP_OK);

    }

    public function remueve_activity(Order $order, Activity $activity)
    {
        $order->activities()->detach($activity->id);
        $response = [
            'message' => 'Actividad eliminada exitosamente',
            'order_activity' => $order->activities
        ];

        return response()->json($response, Response::HTTP_OK);

    }
}
