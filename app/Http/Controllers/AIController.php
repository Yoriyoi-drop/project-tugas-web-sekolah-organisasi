<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ContinueClient;

class AIController extends Controller
{
    public function chat(Request $request, ContinueClient $client)
    {
        $data = $request->validate([
            'messages' => 'required|array',
            'model' => 'nullable|string'
        ]);

        $result = $client->chat($data['messages'], $data['model'] ?? null);

        return response()->json($result);
    }
}
