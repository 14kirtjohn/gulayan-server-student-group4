<?php

namespace App\Http\Controllers;

use App\Models\PlantModel;
use Illuminate\Http\Request;
use \Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class PlantController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $plants = PlantModel::paginate(15);
    
    return response()->json([
      'success' => true,
      'data' => $plants->items(),
      'pagination' => [
        'current_page' => $plants->currentPage(),
        'per_page' => $plants->perPage(),
        'total' => $plants->total(),
        'last_page' => $plants->lastPage(),
        'from' => $plants->firstItem(),
        'to' => $plants->lastItem(),
      ],
    ]);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    try {
      $validated = $request->validate([
        'name' => 'required|string|max:255',
        'variety' => 'required|string|max:255',
        'notes' => 'nullable|string',
        'date_planted' => 'required|date',
        'seedling_count' => 'required|integer|min:1',
        'batch_name' => 'nullable|string|max:255',
        'starting_fund' => 'nullable|numeric|min:0',
        'seedling_source' => 'nullable|string|max:255',
      ]);

      $plant = PlantModel::create($validated);

      return response()->json([
        'success' => true,
        'message' => 'Plant record created successfully',
        'data' => $plant,
      ], 201);
    } catch (ValidationException $e) {
      return response()->json([
        'success' => false,
        'message' => 'Validation failed',
        'errors' => $e->errors(),
      ], 422);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to create plant record',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(PlantModel $plantController)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, PlantModel $plantController)
  {
    try {
      $validated = $request->validate([
        'name' => 'sometimes|required|string|max:255',
        'variety' => 'sometimes|required|string|max:255',
        'notes' => 'nullable|string',
        'date_planted' => 'sometimes|required|date',
        'seedling_count' => 'sometimes|required|integer|min:1',
        'batch_name' => 'nullable|string|max:255',
        'starting_fund' => 'nullable|numeric|min:0',
        'seedling_source' => 'nullable|string|max:255',
      ]);

      $plantController->update($validated);

      return response()->json([
        'success' => true,
        'message' => 'Plant record updated successfully',
        'data' => $plantController,
      ], 200);
    } catch (ValidationException $e) {
      return response()->json([
        'success' => false,
        'message' => 'Validation failed',
        'errors' => $e->errors(),
      ], 422);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to update plant record',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(PlantModel $plant)
  {
    try {
      $plant->delete();

      return response()->json([
        'success' => true,
        'message' => 'Plant record deleted successfully',
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to delete plant record',
        'error' => $e->getMessage(),
      ], 500);
    }
  }
}
