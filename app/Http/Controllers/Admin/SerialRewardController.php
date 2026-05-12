<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SerialReward;
use Illuminate\Http\Request;

class SerialRewardController extends Controller
{
    public function index(Request $request)
    {
        $rewards = SerialReward::orderBy('attempt_threshold')->orderBy('id')->paginate(20);

        if ($request->expectsJson()) {
            return response()->json($rewards);
        }

        return view('sorteo.index', compact('rewards'));
    }

    public function store(Request $request)
    {
        $reward = SerialReward::create($this->validated($request));

        return response()->json([
            'success' => true,
            'message' => 'Premio creado correctamente.',
            'reward' => $reward,
        ], 201);
    }

    public function create()
    {
        abort(404);
    }

    public function show(SerialReward $serialReward)
    {
        return response()->json($serialReward);
    }

    public function update(Request $request, SerialReward $serialReward)
    {
        $serialReward->update($this->validated($request));

        return response()->json([
            'success' => true,
            'message' => 'Premio actualizado correctamente.',
            'reward' => $serialReward,
        ]);
    }

    public function edit(SerialReward $serialReward)
    {
        abort(404);
    }

    public function destroy(SerialReward $serialReward)
    {
        $serialReward->delete();

        return response()->json([
            'success' => true,
            'message' => 'Premio eliminado correctamente.',
        ]);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'attempt_threshold' => 'nullable|integer|min:1',
            'available_from' => 'nullable|date',
            'is_active' => 'sometimes|boolean',
        ]);
    }
}
