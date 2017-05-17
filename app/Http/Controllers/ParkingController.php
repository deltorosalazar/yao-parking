<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Parking;

class ParkingController extends Controller {

    public function index() {
        $parkings = Parking::all();

        return view('parkings.index', [
            'title' => 'Parqueaderos',
            'parkings' => $parkings
        ]);
    }

    public function show($id) {
        $parking = Parking::find($id);

        if (is_null($parking)) {
            return abort(404);
        }

        $simulations = DB::table('simulation_details')
            ->select('simulation_id')
            ->where('parking_id', $id)
            ->distinct()
            ->get();

        // foreach ($simulations as $s) {
        //     var_dump($s);
        //     echo "<br>";
        //     echo "<br>";
        // }

        // dd($simulations);
        // exit;


        return view('parkings.show', [
            'id' => $id,
            'title' => 'Parqueadero #' . $parking->id,
            'parking_name' => $parking->name,
            'simulations' => $simulations
        ]);
    }

    public function store(Request $request) {
        // $rules = array(
        //     'parking_name' => 'required',
        // );
        //
        // $messages = [
        //     '_name.required' => 'Ingrese un Nombre',
        // ];
        //
        // $validator = Validator::make(Input::all(), $rules, $messages);
        // if ($validator->fails()) {
        //     return Response::json(array('errors' => $validator->getMessageBag()->toArray()));
        // } else {
            $parking = new Parking();
            $parking->name = $request->name;

            $parking->save();

            return response()->json($parking);
        // }


    }

    public function update(Request $request) {
        $parking = Parking::findOrFail($request->id);
        $parking->name = $request->name;

        $parking->save();

        return response()->json($parking);
    }

    public function changeState(Request $request) {
        $parking = Parking::findOrFail($request->id);
        $parking->active = ($parking->active == 1) ? 0 : 1;

        $parking->save();

        return response()->json($parking);

    }

}