<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BreweryProxyTest extends TestCase
{

    use WithoutMiddleware;  // Bypass authentication for this test
    
    private $api_url = "https://api.openbrewerydb.org";

    private $api_version = 'v1';

    public function testBreweryListIsProxiedCorrectly()
    {
        $api_url = "{$this->api_url}/{$this->api_version}/breweries?page=1&per_page=2";

        // Mock the external API response
        Http::fake([$api_url => Http::response([
            [
                "id" => "5128df48-79fc-4f0f-8b52-d06be54d0cec",
                "name" => "(405) Brewing Co",
                "brewery_type" => "micro",
                "address_1" => "1716 Topeka St",
                "city" => "Norman",
                "state_province" => "Oklahoma",
                "postal_code" => "73069-8224",
                "country" => "United States",
                "longitude" => "-97.46818222",
                "latitude" => "35.25738891",
                "phone" => "4058160490",
                "website_url" => "http://www.405brewing.com",
                "state" => "Oklahoma",
                "street" => "1716 Topeka St"
            ],
            [
                "id" => "9c5a66c8-cc13-416f-a5d9-0a769c87d318",
                "name" => "(512) Brewing Co",
                "brewery_type" => "micro",
                "address_1" => "407 Radam Ln Ste F200",
                "city" => "Austin",
                "state_province" => "Texas",
                "postal_code" => "78745-1197",
                "country" => "United States",
                "phone" => "5129211545",
                "website_url" => "http://www.512brewing.com",
                "state" => "Texas",
                "street" => "407 Radam Ln Ste F200"
            ],
        ], 200)]);

        // Make a GET request to the proxy endpoint
        $response = $this->getJson('/api/breweries?page=1&per_page=2');

        // Assert the proxy returns the mocked response
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                [
                    "id" => "5128df48-79fc-4f0f-8b52-d06be54d0cec",
                    "name" => "(405) Brewing Co",
                    "brewery_type" => "micro",
                    "address_1" => "1716 Topeka St",
                    "city" => "Norman",
                    "state_province" => "Oklahoma",
                    "postal_code" => "73069-8224",
                    "country" => "United States",
                    "longitude" => "-97.46818222",
                    "latitude" => "35.25738891",
                    "phone" => "4058160490",
                    "website_url" => "http://www.405brewing.com",
                    "state" => "Oklahoma",
                    "street" => "1716 Topeka St"
                ],
                [
                    "id" => "9c5a66c8-cc13-416f-a5d9-0a769c87d318",
                    "name" => "(512) Brewing Co",
                    "brewery_type" => "micro",
                    "address_1" => "407 Radam Ln Ste F200",
                    "city" => "Austin",
                    "state_province" => "Texas",
                    "postal_code" => "78745-1197",
                    "country" => "United States",
                    "phone" => "5129211545",
                    "website_url" => "http://www.512brewing.com",
                    "state" => "Texas",
                    "street" => "407 Radam Ln Ste F200"
                ],
            ],
            'meta' => [
                'total' => 8355,
                'per_page' => 2,
                'page' => 1,
                'total_pages' => 4178
            ]
        ]);
    }

    public function testBreweryListHandlesExternalApiErrors()
    {
        $api_url = "{$this->api_url}/{$this->api_version}/breweries?page=1&per_page=2";
        
        // Mock an error response from the external API
        Http::fake([
            $api_url => Http::response(null, 500)
        ]);

        // Make a GET request to the proxy endpoint
        $response = $this->getJson('/api/breweries?page=1&per_page=2');

        \Log::error('Error in Brewery API' . $response->status());

        // Assert the proxy returns the error status
        $response->assertStatus(500);
    }
}
