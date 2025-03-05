<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttributeValue;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class AttributeValueController extends Controller
{
    /**
     * Display a listing of the attribute values.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            // Fetch all attribute values
            $attributeValues = AttributeValue::all();

            return response()->json([
                'message' => 'Attribute values retrieved successfully',
                'data' => $attributeValues,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to retrieve attribute values',
                'details' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }

    /**
     * Display the specified attribute value.
     *
     * @param string $id (UUID)
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            // Find the attribute value by ID or fail
            $attributeValue = AttributeValue::findOrFail($id);

            return response()->json([
                'message' => 'Attribute value retrieved successfully',
                'data' => $attributeValue,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Attribute value not found',
                'details' => 'The requested attribute value does not exist.',
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to retrieve attribute value',
                'details' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }

    /**
     * Store a newly created attribute value in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'attribute_id' => 'required|exists:attributes,id',
                'entity_id' => 'required|exists:projects,id',
                'value' => 'required|string|max:255',
            ]);

            // Convert the fields to lowercase
            $validatedData['value'] = strtolower($validatedData['value']);

            // Create the attribute value
            $attributeValue = AttributeValue::create($validatedData);

            return response()->json([
                'message' => 'Attribute value created successfully',
                'data' => $attributeValue,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation Error',
                'details' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            // echo $e;
            return response()->json([
                'error' => 'Failed to create attribute value',
                'details' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }

    /**
     * Update the specified attribute value in storage.
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
                'attribute_id' => 'sometimes|exists:attributes,id',
                'entity_id' => 'sometimes|exists:projects,id',
                'value' => 'sometimes|string|max:255',
            ]);

            // Find the attribute value by ID or fail
            $attributeValue = AttributeValue::findOrFail($id);

            // Convert fields to lowercase
            if (isset($validatedData['value'])) {
                $validatedData['value'] = strtolower($validatedData['value']);
            }


            // Update the attribute value with validated data
            $attributeValue->update($validatedData);

            return response()->json([
                'message' => 'Attribute value updated successfully',
                'data' => $attributeValue,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation Error',
                'details' => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Attribute value not found',
                'details' => 'The requested attribute value does not exist.',
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to update attribute value',
                'details' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }

    /**
     * Remove the specified attribute value from storage.
     *
     * @param string $id (UUID)
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            // Find the attribute value by ID or fail
            $attributeValue = AttributeValue::findOrFail($id);

            // Delete the attribute value
            $attributeValue->delete();

            return response()->json([
                'message' => 'Attribute value deleted successfully',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Attribute value not found',
                'details' => 'The requested attribute value does not exist.',
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to delete attribute value',
                'details' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }
}
