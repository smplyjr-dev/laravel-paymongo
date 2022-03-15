<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/paymongo/webhook', function () {
});

Route::post('/paymongo/source', function () {
    $client = new \GuzzleHttp\Client();

    $amount = 10000;
    $url  = url('/payment');
    $body = [
        'data' => [
            'attributes' => [
                'amount' => $amount,
                'currency' => 'PHP',
                'redirect' => [
                    'success' => "$url/success",
                    'failed'  => "$url/failed",
                ],
                'type' => 'gcash',
            ]
        ]
    ];
    $init = $client->request('POST', 'https://api.paymongo.com/v1/sources', [
        'body'    => json_encode($body),
        'headers' => [
            'Accept'        => 'application/json',
            'Authorization' => 'Basic cGtfdGVzdF84MjJFZ2NLbzFHNzYxemU0ejFBS3RxdzI6YXNk',
            'Content-Type'  => 'application/json',
        ],
    ]);
    $result = json_decode($init->getBody());

    return response()->json($result, 200);
});
