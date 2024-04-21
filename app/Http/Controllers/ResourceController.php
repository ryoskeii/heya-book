<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    /**
     * Creates a new resource. A resource is anything that is part of a facility (such as a room, or a desk, or anything).
     */
    public function create(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $model = new Resource();
        $model->fill($data);
        $model->save();

        return response()->json($model->getAttributes());
    }

    /**
     * Finds a resource by it's ID
     */
    public function findById($id)
    {
        $model = Resource::where('id', $id)->first();
        return response()->json($model->getAttributes());
    }

    /**
     * Returns a list of available resources (e.g: Rooms) and their respective bookings
     */
    public function find(Request $request)
    {
        $q = Resource::with('bookings')
            ->where('deleted', 0);

        if (!empty($request->query('facility_id'))) {
            $q->where('facility_id', $request->query('facility_id'));
        }

        return $q->get('*');
    }
}
