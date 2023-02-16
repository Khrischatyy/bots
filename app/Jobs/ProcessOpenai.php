<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Http\Request;

class ProcessOpenai implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $response;
    private $prompt;
    private $apiKey;
    private $client_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($prompt, $apiKey, $client_id)
    {
	    $this->prompt = $prompt;
	    $this->apiKey = $apiKey;
	    $this->client_id = $client_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
	    $result = OpenAI::completions()->create([
                'model' => 'text-davinci-003',
                'prompt' => $this->prompt,
                'temperature' => 0.2,
                'max_tokens' => 500
	    ]);
	    $result = $result->choices ? $result->choices[0]->text : '';
	    #$this->response =
	    $apiKey = $this->apiKey;
	    $client_id = $this->client_id;
	    $endpoint = 'https://chatter.salebot.pro/api/'.$apiKey.'/callback';

        $params = array('client_id' => $client_id, 'message' => '#result_scenario '.$result);
        $url = $endpoint . '?' . http_build_query($params);

        //Initialize cURL.
        $ch = curl_init();

        //Set the URL that you want to GET by using the CURLOPT_URL option.
        curl_setopt($ch, CURLOPT_URL, $url);


        //Execute the request.
        $data = curl_exec($ch);

        //Close the cURL handle.
        curl_close($ch);


        die();
        exit();

	    return response()->json($result);
    }

    public function getResponse()
    {
    	return $this->response;
    }
}
