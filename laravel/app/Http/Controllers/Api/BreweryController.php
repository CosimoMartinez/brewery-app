<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class BreweryController extends Controller
{

    private $api_url = "https://api.openbrewerydb.org";

    private $api_version = "v1";

    /**
     * Get Breweries list with meta
     * 
     * @param \App\Http\Requests\BreweryListRequest $request - The validated BreweryListRequest request object
     * 
     * @return \Illuminate\Http\JsonResponse - The JSON response
     */
    public function index(\App\Http\Requests\BreweryListRequest $request)
    {
        $validated_req = $request->validated();

        $cacheKey = 'breweries_list_page_' . $validated_req['page'] . '_perpage_' . $validated_req['per_page'];

        return Cache::remember($cacheKey, 60, function() use ($validated_req) {

            $metadata = $this->getBreweriesMetadata();

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

        });

    }

    /**
     * Get Breweries list from remote API
     * 
     * @param \App\Http\Requests\BreweryListRequest - The BreweryListRequest request object
     * 
     * @return \Illuminate\Http\JsonResponse - The JSON response
     */
    public function getBreweries($request) {

        $url = "{$this->api_url}/{$this->api_version}/breweries";

        $response = Http::get($url, $request);

        return $response;

    }

    /**
     * Get Breweries metadata from remote API
     * 
     * 
     * @return json
     */
    private function getBreweriesMetadata() {

        $url = "{$this->api_url}/{$this->api_version}/breweries/meta";

        $response = Http::get($url);

        return $response->json();        

    }

}