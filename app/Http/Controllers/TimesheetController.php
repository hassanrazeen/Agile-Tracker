<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Timesheet;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;


class TimesheetController extends Controller
{
    /**
     * Display a listing of the timesheets.
     *
     * @return JsonResponse
     */
    public function index()
    {
        try {
            // Fetch timesheets with related project and user data
            $timesheets = Timesheet::with([
                'project',
                'user' => function ($query) {
                    $query->select('id', 'first_name', 'email');
                }
            ])->get();

            return response()->json([
                'message' => 'Timesheets retrieved successfully',
                'data' => $timesheets,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to retrieve timesheets',
                'details' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }

    /**
     * Display the specified timesheet.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $timesheet = Timesheet::with([
                'project',
                'user' => function ($query) {
                    $query->select('id', 'first_name', 'email');
                }
            ])->findOrFail($id);

            return response()->json([
                'message' => 'Timesheet retrieved successfully',
                'data' => $timesheet,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Timesheet not found',
                'details' => 'The requested timesheet does not exist.',
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to retrieve timesheet',
                'details' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }

    /**
     * Store a newly created timesheet in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'task_name' => 'required|string|max:255',
                'date' => 'required|date',
                'hours' => 'required|numeric|min:0',
                'user_id' => 'required|uuid|exists:users,id',
                'project_id' => 'required|uuid|exists:projects,id',
            ]);

            // Create the timesheet
            $timesheet = Timesheet::create($validatedData);

            // Convert fields to lowercase
            $validatedData['task_name'] = strtolower($validatedData['task_name']);

            return response()->json([
                'message' => 'Timesheet created successfully',
                'data' => $timesheet,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation Error',
                'details' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to create timesheet',
                'details' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }

    /**
     * Update the specified timesheet in storage.
     *
     * @param Request $request
     * @param string $id (UUID)
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {

            $request->merge(['id' => $id]);

            // Validate the request data
            $validatedData = $request->validate([
                'id' => 'required|uuid',
                'task_name' => 'sometimes|string|max:255',
                'date' => 'sometimes|date',
                'hours' => 'sometimes|numeric|min:0',
                'user_id' => 'sometimes|uuid|exists:users,id',
                'project_id' => 'sometimes|uuid|exists:projects,id',
            ]);

            // Find the timesheet by UUID or fail
            $timesheet = Timesheet::findOrFail($id);

            // Convert fields to lowercase
            if (isset($validatedData['task_name'])) {
                $validatedData['task_name'] = strtolower($validatedData['task_name']);
            }

            // Update the timesheet with validated data
            $timesheet->update($validatedData);

            return response()->json([
                'message' => 'Timesheet updated successfully',
                'data' => $timesheet,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation Error',
                'details' => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Timesheet not found',
                'details' => 'The requested timesheet does not exist.',
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to update timesheet',
                'details' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }

    /**
     * Remove the specified timesheet from storage.
     *
     * @param string $id (UUID)
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        try {
            // Validate the ID parameter
            $request->merge(['id' => $id]); 
            $request->validate([
                'id' => 'required|uuid|exists:timesheets,id', 
            ]);

            // Find the timesheet by UUID or fail
            $timesheet = Timesheet::findOrFail($id);

            // Delete the timesheet
            $timesheet->delete();

            return response()->json([
                'message' => 'Timesheet deleted successfully',
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation Error',
                'details' => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Timesheet not found',
                'details' => 'The requested timesheet does not exist.',
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to delete timesheet',
                'details' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }
}
