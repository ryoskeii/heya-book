<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    /**
     * Creates a new facility (e.g: WeWork Nogizaka)
     */
    public function create(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $model = new Facility();
        $model->fill($data);
        $model->save();

        return response()->json($model->getAttributes());
    }


    /**
     * Finds a facility by its ID
     */
    public function findById($id)
    {
        $model = Facility::where('id', $id)->first();
        return response()->json($model->getAttributes());
    }
}
