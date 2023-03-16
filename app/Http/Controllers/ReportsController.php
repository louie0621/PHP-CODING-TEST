<?php

namespace App\Http\Controllers;

use App\Models\Properties;
use App\Models\Reports;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

class ReportsController extends Controller
{

    /**
     * Get all reports.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWithoutTrashed(Request $request)
    {
        if (!empty($request->id)) {
            return response()->json(['code' => 200, 'data' => Reports::find($request->id)]);
        }

        return response()->json(['code' => 200, 'data' => Reports::get()]);
    }

    /**
     * Add new reports.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $validatedData  = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'pentesting_start_date' => ['required', 'date'],
            'pentesting_end_date' => ['required', 'date'],
        ]);

        if ($validatedData->fails()) {
            return response()->json(['code' => 400, 'description' => $validatedData->errors()]);
        }

        $properties = Properties::where('title', $request->name)->first();

        if (!$properties){
            return response()->json(['code'=> 400, 'description' => ['name' =>['Title name does not exist.']]]);
        }

        return response()->json(['code' => 200, 'data' => Reports::create($request->all())]);
    }

    /**
     * Update existing reports.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $validatedData  = Validator::make($request->all(), [
            'id' => ['required', 'integer'],
            'name' => ['required', 'string'],
            'pentesting_start_date' => ['required', 'date'],
            'pentesting_end_date' => ['required', 'date'],
        ]);

        if ($validatedData->fails()) {
            return response()->json(['code' => 400, 'description' => $validatedData->errors()]);
        }

        $reports = Reports::find($request->id);

        if (empty($reports)) {
            return response()->json(['code' => 400, 'description' => ['id' => ['The id field is invalid.']]]);
        }

        $properties = Properties::where('title', $request->name)->first();

        if (!$properties){
            return response()->json(['code'=> 400, 'description' => ['name' =>['Title name does not exist.']]]);
        }

        $reports->name = $request->name;
        $reports->pentesting_start_date = $request->pentesting_start_date;
        $reports->pentesting_end_date = $request->pentesting_end_date;
        $reports->save();

        return response()->json(['code' => 200, 'data' => $reports]);
    }

    /**
     * Delete existing reports.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'id' => ['required', 'integer'],
        ]);

        if ($validatedData->fails()) {
            return response()->json(['code' => 400, 'description' => $validatedData->errors()]);
        }

        Reports::find($request->id)->delete();

        return response()->json(['code' => 200, 'description' => 'Reports has been successfully deleted.']);
    }
}
