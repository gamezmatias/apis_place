<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlaceRequest;
use App\Http\Requests\UpdatePlaceRequest;
use App\Models\Place;
use App\Services\PlaceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    public function __construct(private PlaceService $service) {}

    public function index(Request $request): JsonResponse
    {
        $name = $request->query('name');
        $perPage = (int) $request->query('per_page', 15);
        $places = $this->service->list($name, $perPage);

        return response()->json($places);
    }

    public function store(StorePlaceRequest $request): JsonResponse
    {
        $place = $this->service->create($request->validated());
        return response()->json(['data' => $place], 201);
    }

    public function show(Place $place): JsonResponse
    {
        return response()->json(['data' => $place]);
    }

    public function update(UpdatePlaceRequest $request, Place $place): JsonResponse
    {
        $place = $this->service->update($place, $request->validated());

        return response()->json(['data' => $place]);
    }

    public function destroy(Place $place): JsonResponse
    {
        $this->service->delete($place);
        
        return response()->json(['message' => 'Place deleted successfully'], 200);
    }
}
