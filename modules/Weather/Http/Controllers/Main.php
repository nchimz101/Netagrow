<?php

namespace Modules\Weather\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cookie;
use Modules\Fields\Models\Field;

class Main extends Controller
{

    public function callAgroAPI($params,$resource="weather",$method="GET"){

        //Crete the endpoint
        $endpoint="https://api.agromonitoring.com/agro/1.0/".$resource;

        $client = new \GuzzleHttp\Client();
        $locale = Cookie::get('lang') ? Cookie::get('lang') : config('settings.app_locale');
        $units="metric";

        $queryData=array_merge(['lang'=>$locale,'units'=>$units,'appid' => config('weather.apikey')],$params);
        //dd($queryData);

        $payload = [
            'query' => $queryData,
        ];
        $response = $client->request('GET', $endpoint, $payload);

        if ($response->getStatusCode() !== 200) {
            return response()->json(['status'=>'error']);
        }
        $responseDecoded = json_decode($response->getBody(),true);
        return response()->json(['status'=>'ok','units'=>$units,'result'=>$responseDecoded]);
    }

    public function getWeatherForLatLng($lat,$lng){
        return $this->callAgroAPI(['lat'=>$lat,'lon'=>$lng]);
    }

    public function getForecastForLatLng($lat,$lng){
        return $this->callAgroAPI(['lat'=>$lat,'lon'=>$lng],"weather/forecast");
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('weather::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('weather::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('weather::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('weather::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
