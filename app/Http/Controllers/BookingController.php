<?php

namespace App\Http\Controllers;

use App\Helpers\AuthHelper;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Creates a new booking for any given 'resource'. Assumes the auth identity is the one making the booking (cannot make bookings on behalf of others).
     * 
     * @return mixed
     */
    public function create(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $fromTime = strtotime($data['from_datetime']);
        $toTime = strtotime($data['to_datetime']);

        if ($fromTime > $toTime) {
            return response([
                'error' => 'Bad Request',
                'message' => 'Invalid start and end times',
            ], 400);
        }

        if (!$this->isBookingValid($data['resource_id'], $data['from_datetime'], $data['to_datetime'])) {
            return response([
                'error' => 'Bad Request',
                'message' => 'Resources are already reserved at those times',
            ], 400);
        }

        $callerIdentity = AuthHelper::getCallerIdentity($request);

        $model = new Booking();
        $model->fill($data);
        $model->user_id = $callerIdentity->id;
        $model->save();

        return $model->toArray();
    }

    public function findById($id)
    {
        $model = Booking::where('id', $id)
            ->where('deleted', 0)
            ->first();
        return response()->json($model->getAttributes());
    }

    public function findForUser(Request $request)
    {
        $callerIdentity = AuthHelper::getCallerIdentity($request);
        $models = DB::table('bookings')
            ->where('user_id', $callerIdentity->id)
            ->where('deleted', 0)
            ->get('*');

        return $models;
    }

    private function isBookingValid(string $resourceId, string $fromDatetime, string $toDatetime)
    {
        return !DB::table('bookings')
            ->where('resource_id', '=', $resourceId)
            ->where('from_datetime', '<=', $toDatetime)
            ->where('to_datetime', '>', $fromDatetime)
            ->exists();
    }
}
