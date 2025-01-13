<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;

class BreweryController extends Controller
{

    private $api_url = "https://api.openbrewerydb.org";

    private $api_version = "v1";

    /**
     * Get Breweries list
     * 
     * @param obj $request - Illuminate Http Request
     * 
     * @return json
     */
    public function index(\App\Http\Requests\BreweryListRequest $request)
    {

        $metadata = $this->getBreweriesMetadata();

        $validated_req = $request->validated();

        $perPage = $validated_req['per_page'];
        $page = $validated_req['page'];
        $total = $metadata['total'] ?? 0;
        $totalPages = ceil($total / $perPage);



        $response = $this->getBreweries($validated_req);

        if($response->failed()) {
            throw new \Exception('API esterna ha restituito un errore', $response->status());
        }
        else {

            $data = [
                'data' => $response->json(),
                'meta' => [
                    'total' => $total,
                    'per_page' => $perPage,
                    'page' => $page,
                    'total_pages' => $totalPages,
                ],
            ];   
            $status = 200; 
        }

        return response()->json($data, $status);

    }

    public function getBreweries($request) {

        $url = "{$this->api_url}/{$this->api_version}/breweries";

        $response = Http::get($url, $request);

        return $response;

    }

    /**
     * Get Breweries metadata
     * 
     * @param obj $request - Illuminate Http Request
     * 
     * @return json
     */
    private function getBreweriesMetadata() {

        $url = "{$this->api_url}/{$this->api_version}/breweries/meta";

        $response = Http::get($url);

        return $response->json();        

    }

}