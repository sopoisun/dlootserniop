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
    $tanggal = request()->get('tanggal') ? request()->get('tanggal') : date('Y-m-d');

    $data = _GetTransaksi($tanggal);

    if( !is_array($data) ){
        return $data;
    }

    $display = [];

    foreach( $data as $d ){
        array_push($display, [
            'tanggal'   => $d->tanggal,
            'nota'      => $d->kode_transaksi,
            'total'     => strval($d->total),
        ]);
    }

    return $display;
});

Route::group(['prefix' => 'oldapp', 'namespace' => 'Old'], function(){
    Route::match(['get', 'post'], '/', 'ReportController@index'); // as login
    Route::get('logout', 'ReportController@logout');
    Route::group(['middleware' => 'ceklogin'], function(){
        Route::get('report/pertanggal', 'ReportController@reportPerDay');
        Route::get('report/pertanggal/print', 'ReportController@reportPerDayPrint');
        Route::get('report/pertanggal/detail', 'ReportController@ReportDetail');
        Route::get('report/pertanggal/solditem', 'ReportController@soldItem');
        Route::get('report/pertanggal/solditem/print', 'ReportController@soldItemPrint');
        Route::get('report/periode', 'ReportController@reportRangeDay');
        Route::get('report/periode/print', 'ReportController@reportRangeDayPrint');
        Route::get('report/periode/solditem', 'ReportController@soldItemRange');
        Route::get('report/periode/solditem/print', 'ReportController@soldItemRangePrint');
    });
});
