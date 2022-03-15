<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Events\PaymongoEvent;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/source/success', function () {
    PaymongoEvent::dispatch([
        'from' => 'paymongo.source.success',
        'data' => request()->all()
    ]);
});

Route::get('/source/failed', function () {
    PaymongoEvent::dispatch([
        'from' => 'paymongo.source.failed',
        'data' => request()->all()
    ]);
});

Route::get('/source', function () {
    PaymongoEvent::dispatch([
        'from' => 'paymongo.source',
        'data' => request()->all()
    ]);

    $client = new \GuzzleHttp\Client();

    $body = [
        'data' => [
            'attributes' => [
                'amount' => 10000,
                'currency' => 'PHP',
                'redirect' => [
                    'success' => url('/source') . "/success",
                    'failed'  => url('/source') . "/failed",
                ],
                'type' => 'gcash',
            ]
        ]
    ];
    $init = $client->request('POST', 'https://api.paymongo.com/v1/sources', [
        'body'    => json_encode($body),
        'headers' => [
            'Accept'        => 'application/json',
            'Authorization' => 'Basic ' . env('PM_PUBLIC'),
            'Content-Type'  => 'application/json',
        ],
    ]);
    $result = json_decode($init->getBody());

    return response()->json($result, 200);
});

Route::get('/webhook', function () {
    PaymongoEvent::dispatch([
        'from' => 'paymongo.source (GET)',
        'data' => request()->all()
    ]);

    return response()->json(true, 200);
});

Route::post('/webhook', function () {
    PaymongoEvent::dispatch([
        'from' => 'paymongo.source (POST)',
        'data' => request()->all()
    ]);

    return response()->json(true, 200);
});
