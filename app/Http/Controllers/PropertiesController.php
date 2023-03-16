<?php

namespace App\Http\Controllers;

use App\Models\Properties;
use Illuminate\Http\Request;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class PropertiesController extends Controller
{

    /**
     * Get all properties.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWithoutTrashed(Request $request)
    {
        if (!empty($request->id)) {
            return response()->json(['code' => 200, 'data' => Properties::find($request->id)]);
        }

        return response()->json(['code' => 200, 'data' => Properties::get()]);
    }

    /**
     * Add new properties.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $validatedData  = Validator::make($request->all(), [
            'title' => [
                'required',
                Rule::unique('properties')->where(function ($query) use ($request) {
                })
            ],
            'severity' => ['required', 'string', Rule::in(['Critical', 'High', 'Medium', 'Low', 'None'])],
            'status' => ['required', 'string', Rule::in(['New', 'Resolved'])],
        ]);

        if ($validatedData->fails()) {
            return response()->json(['code' => 400, 'description' => $validatedData->errors()]);
        }

        return response()->json(['code' => 200, 'data' => Properties::create($request->all())]);
    }

    /**
     * Update existing properties.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $validatedData  = Validator::make($request->all(), [
            'id' => ['required', 'integer'],
            'title' => [
                'required',
                Rule::unique('properties')->where(function ($query) use ($request) {
                })->ignore($request->id)
            ],
            'severity' => ['required', 'string', Rule::in(['Critical', 'High', 'Medium', 'Low', 'None'])],
            'status' => ['required', 'string', Rule::in(['New', 'Resolved'])],
        ]);

        if ($validatedData->fails()) {
            return response()->json(['code' => 400, 'description' => $validatedData->errors()]);
        }

        $properties = Properties::find($request->id);

        if (empty($properties)) {
            return response()->json(['code' => 400, 'description' => ['id' => ['The id field is invalid.']]]);
        }

        $properties->title = $request->title;
        $properties->severity = $request->severity;
        $properties->status = $request->status;
        $properties->save();

        return response()->json(['code' => 200, 'data' => $properties]);
    }

    /**
     * Delete existing properties.
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

        Properties::find($request->id)->delete();

        return response()->json(['code' => 200, 'description' => 'Properties has been successfully deleted.']);
    }
}
