<?php

use Mpociot\BotMan\BotMan;

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

Route::post('/', function () {
    if ($challenge = request()->get('challenge')) {
        return $challenge;
    }

    /** @var \GuzzleHttp\Client $watsonApi */
    $watsonApi = resolve('watson');

    $botman = app('botman');

    $botman->fallback(function($bot) use ($watsonApi) {
        $response = $watsonApi->post('?version=2017-04-24', ['body' => json_encode(['input' => ['text' => 'Hello']])]);
        $resp = json_decode((string) $response->getBody());
        $bot->reply($resp->output->text[0]);
    });

    // start listening
    $botman->listen();
});
