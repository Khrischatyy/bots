<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessOpenai;
use Illuminate\Support\Facades\Http;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Http\Request;

class BotController extends Controller
{
    public function getScenarioAsync(Request $request): \Illuminate\Http\JsonResponse
    {
	    ProcessOpenai::dispatch($request->prompt, $request->apiKey, $request->client_id);


	    return response()->json(123);
    }

    public function getScenario(Request $request): \Illuminate\Http\JsonResponse
    {
	    $result = OpenAI::completions()->create([
	    	'model' => 'text-davinci-003',
		'prompt' => $request->text,
		'temperature' => 0.2,
             	'max_tokens' => 500
	    ]);

	    return response()->json($result);
}
}

