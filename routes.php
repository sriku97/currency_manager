<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('home');
});

Route::get('info',function() {
    return view('info');
});

Route::get('current_rates',function() {
    return view('current_rates');
});

Route::get('converter',function() {
    return view('converter');
});

Route::get('graph',function() {
    return view('graph');
});

Route::get('table',function(){
    return view('table');
});

Route::get('checkcurrentrate/{code}',function($code){ //route called through an ajax request
    $data['code']=$code;
    return View::make('checkcurrentrate',$data);
});

Route::get('convertvalue/{cur1}/{cur2}/{input}',function($cur1,$cur2,$input){ //route called through an ajax request
    $data['cur1']=$cur1;
    $data['cur2']=$cur2;
    $data['input']=$input;
    return View::make('convertvalue',$data);
});

Route::get('drawgraph/{curr}/{bardata}',function($curr,$bardata){ //route called through an ajax request
    $data['curr']=$curr;
    $data['bardata']=$bardata;
    return View::make('drawgraph',$data);
});