<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Attribute;
use App\Models\ProjectUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class ProjectController extends Controller
{
    /**
     * Display a listing of the projects.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {

            // Get the authenticated user's ID
            // $userId = Auth::id();

            // $query = Project::whereHas('users', function ($q) use ($userId) {
            //     $q->where('user_id', $userId);
            // });

            $query = Project::query();

            if ($request->has('filters')) {
                foreach ($request->input('filters') as $key => $value) {
                    $key = strtolower($key);
                    $value = strtolower($value);
                    if (in_array($key, ['name', 'status'])) {
                        $query->where($key, 'LIKE', "%$value%");
                    }
                }
            }

            // EAV attribute filtering
            if ($request->has('filters')) {
                foreach ($request->input('filters') as $key => $value) {
                    $key = strtolower($key);
                    $value = strtolower($value);
                    $attribute = Attribute::whereRaw('LOWER(name) = ?', [$key])->first();

                    if ($attribute) {
                        $query->whereHas('attributeValues', function ($q) use ($attribute, $value) {
                            $q->where('attribute_id', $attribute->id)
                                ->whereRaw('LOWER(value) LIKE ?', ["%$value%"]);
                        });
                    }
                }
            }

            return response()->json(['message' => 'project retrieved successfully', 'data' => $query->with('attributeValues')->get()]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified project.
     *
     * @param string $id (UUID)
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            // Find the project by ID with its attribute values or fail
            $project = Project::with('attributeValues')->findOrFail($id);

            return response()->json([
                'message' => 'Project retrieved successfully',
                'data' => $project,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Project not found',
                'details' => 'The requested project does not exist.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created project in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'status' => 'sometimes|string|in:pending,in_progress,completed',
            ], [
                'status.in' => 'Invalid status. Allowed values are: pending, in_progress, completed.',
            ]);


            // Convert fields to lowercase
            $validatedData['name'] = strtolower($validatedData['name']);
            if (isset($validatedData['status'])) {
                $validatedData['status'] = strtolower($validatedData['status']);
            }

            // Get the authenticated user's ID
            $userId = Auth::id();

            // Create the project
            $project = Project::create($validatedData);

            // Store the relationship in the project_user table
            ProjectUser::create([
                'user_id' => $userId,
                'project_id' => $project->id,
            ]);

            return response()->json([
                'message' => 'Project created successfully',
                'data' => $project,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation Error',
                'details' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified project in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {

            $request->merge(['id' => $id]);

            // Validate the request data
            $validatedData = $request->validate([
                'id' => 'required|uuid',
                'name' => 'sometimes|string|max:255',
                'status' => 'sometimes|string|in:pending,in_progress,completed',
            ], [
                'status.in' => 'Invalid status. Allowed values are: pending, in_progress, completed.',
            ]);

            // echo $request;

            // Convert fields to lowercase
            if (isset($validatedData['name'])) {
                $validatedData['name'] = strtolower($validatedData['name']);
            }
            if (isset($validatedData['status'])) {
                $validatedData['status'] = strtolower($validatedData['status']);
            }

            // Find the project by ID or fail
            $project = Project::findOrFail($id);

            // Update the project with validated data
            $project->update($validatedData);

            return response()->json([
                'message' => 'Project updated successfully',
                'data' => $project,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation Error',
                'details' => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Project not found',
                'details' => 'The requested project does not exist.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified project from storage.
     *
     * @param string $id (UUID)
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            // Find the project by ID or fail
            $project = Project::findOrFail($id);

            // Delete the project
            $project->delete();

            return response()->json([
                'message' => 'Project deleted successfully',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Project not found',
                'details' => 'The requested project does not exist.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}
