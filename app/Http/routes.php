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

    $CTanggal = \Carbon\Carbon::createFromFormat('Y-m-d', $tanggal);
    $CReadFrom = \Carbon\Carbon::createFromFormat('Y-m-d', '2017-01-15');

    /*if( $CTanggal->lte($CReadFrom) ){
        return "No Data";
    }*/

    $data = DB::select("SELECT temp.kode_transaksi, temp.tanggal, status_struk, kasir, jumlah, ppn, jumlah_ppn,
        potongan, total, bayar, kembali, IF( IFNULL(transaksi_return.tanggal, '') != '', CONCAT( 'return @ ',
        SUBSTRING(transaksi_return.tanggal, 12)), 'sukses' ) AS status_transaksi FROM(SELECT transaksi.kode_transaksi,
        DATE_FORMAT(transaksi.tanggal, '%d-%m-%Y') AS tanggal, transaksi.kasir, transaksi.status_struk,
        (transaksi.potongan) AS potongan, (transaksi.bayar) AS bayar,
        ( ( SUM( transaksi_detail.harga * transaksi_detail.jumlah ) ) ) AS jumlah,
        ROUND( ( SUM( transaksi_detail.harga * transaksi_detail.jumlah ) * transaksi.ppn ) / 100) AS ppn,
        ROUND( ( SUM( transaksi_detail.harga * transaksi_detail.jumlah ) + ( ( SUM( transaksi_detail.harga * transaksi_detail.jumlah ) *
        transaksi.ppn ) / 100 ) ) ) AS jumlah_ppn,
        ROUND( ( ( SUM( transaksi_detail.harga * transaksi_detail.jumlah ) +
        ROUND( ( SUM( transaksi_detail.harga * transaksi_detail.jumlah ) * transaksi.ppn ) / 100 ) ) - transaksi.potongan ) ) AS total,
        ROUND( transaksi.bayar - ( ( SUM( transaksi_detail.harga * transaksi_detail.jumlah ) + ( ( SUM( transaksi_detail.harga *
        transaksi_detail.jumlah ) * transaksi.ppn ) / 100 ) ) - transaksi.potongan ) ) AS kembali
        FROM transaksi INNER JOIN transaksi_detail ON transaksi.kode_transaksi = transaksi_detail.kode_transaksi WHERE
        SUBSTRING(transaksi.tanggal, 1, 10) = '".$tanggal."' GROUP BY transaksi.kode_transaksi) AS temp LEFT JOIN transaksi_return ON
        temp.kode_transaksi = transaksi_return.kode_transaksi WHERE transaksi_return.kode_transaksi IS NULL ");

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
