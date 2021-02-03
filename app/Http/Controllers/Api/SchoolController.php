<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $client = $client = new \GuzzleHttp\Client();
        $url = 'http://webservice.recruit.co.jp/shingaku/school/v1/';
        $key = 'a03b86bb39fdd554';
        $this->validate($request, [
            'keyword' => 'required'
        ]);
        $response = $client->get($url."?key=".$key."&name=".$request->keyword."&format=json&order=3", [
            'http_errors'  => false ,
            'allow_redirects' => false
        ]);
        $results = array_get(json_decode($response->getBody(), true), 'results');
        // $results = array_first(array_get(json_decode(), 'results'));
        $schools = array_get($results,'school');
        $names = [];
        foreach ($schools as $school) {
            $names[] = array_get($school,'name');
        }
        return json_encode($names);
    }
}
