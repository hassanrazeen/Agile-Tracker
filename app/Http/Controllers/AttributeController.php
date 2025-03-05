<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attribute;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class AttributeController extends Controller
{
    /**
     * Display a listing of the attributes.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            // Fetch all attributes
            $attributes = Attribute::all();

            return response()->json([
                'message' => 'Attributes retrieved successfully',
                'data' => $attributes,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to retrieve attributes',
                'details' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }

    /**
     * Display the specified attribute.
     *
     * @param string $id (UUID)
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            // Find the attribute by ID or fail
            $attribute = Attribute::findOrFail($id);

            return response()->json([
                'message' => 'Attribute retrieved successfully',
                'data' => $attribute,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Attribute not found',
                'details' => 'The requested attribute does not exist.',
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to retrieve attribute',
                'details' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }

    /**
     * Store a newly created attribute in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'name' => 'required|string|unique:attributes,name|max:255',
                'type' => 'required|string|in:text,date,number,select',
            ]);

            // Convert the 'value' to lowercase
            $validatedData['name'] = strtolower($validatedData['name']);
            $validatedData['type'] = strtolower($validatedData['type']);

            // Create the attribute
            $attribute = Attribute::create($validatedData);

            return response()->json([
                'message' => 'Attribute created successfully',
                'data' => $attribute,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation Error',
                'details' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to create attribute',
                'details' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }

    /**
     * Update the specified attribute in storage.
     *
     * @param Request $request
     * @param string $id (UUID)
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'name' => 'sometimes|string|unique:attributes,name,' . $id . '|max:255',
                'type' => 'sometimes|string|in:text,date,number,select',
            ]);

            // Find the attribute by ID or fail
            $attribute = Attribute::findOrFail($id);

            // Convert fields to lowercase
            if (isset($validatedData['name'])) {
                $validatedData['name'] = strtolower($validatedData['name']);
            }
            if (isset($validatedData['type'])) {
                $validatedData['type'] = strtolower($validatedData['type']);
            }


            // Update the attribute with validated data
            $attribute->update($validatedData);

            return response()->json([
                'message' => 'Attribute updated successfully',
                'data' => $attribute,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation Error',
                'details' => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Attribute not found',
                'details' => 'The requested attribute does not exist.',
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to update attribute',
                'details' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }

    /**
     * Remove the specified attribute from storage.
     *
     * @param string $id (UUID)
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            // Find the attribute by ID or fail
            $attribute = Attribute::findOrFail($id);

            // Delete the attribute
            $attribute->delete();

            return response()->json([
                'message' => 'Attribute deleted successfully',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Attribute not found',
                'details' => 'The requested attribute does not exist.',
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to delete attribute',
                'details' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }
}
