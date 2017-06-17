<?php

function set_active($path, $active = 'active') {
    return call_user_func_array('Request::is', (array)$path) ? $active : '';
}

function _GetTransaksi($tanggal)
{
    $CTanggal = \Carbon\Carbon::createFromFormat('Y-m-d', $tanggal);
    $CReadFrom = \Carbon\Carbon::createFromFormat('Y-m-d', '2017-01-15');

    $blockIedFrom = \Carbon\Carbon::createFromFormat('Y-m-d', '2017-06-27');
    $blockIedEnd = \Carbon\Carbon::createFromFormat('Y-m-d', '2017-07-06');

    if( $CTanggal->lte($CReadFrom) ||
        in_array($CTanggal->format('Y-m-d'), ['2017-06-27', '2017-06-28', '2017-06-29', '2017-06-30',
            '2017-07-01', '2017-07-02', '2017-07-03', '2017-07-04', '2017-07-05', '2017-07-06']) ){
        return []; //"No Data";
    }

    return GetTransaksi("WHERE SUBSTRING(transaksi.tanggal, 1, 10) = '".$tanggal."'");
}

function ConvertRawQueryToArray($data)
{
    $temp = [];
    foreach($data as $d){
        array_push($temp, (array)$d);
    }
    return $temp;
}

 function GetTransaksi($where = "")
 {
     return DB::select("SELECT temp.kode_transaksi, temp.tanggal, status_struk, kasir, jumlah, ppn, jumlah_ppn,
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
         FROM transaksi INNER JOIN transaksi_detail ON transaksi.kode_transaksi = transaksi_detail.kode_transaksi
         $where GROUP BY transaksi.kode_transaksi) AS temp LEFT JOIN transaksi_return ON
         temp.kode_transaksi = transaksi_return.kode_transaksi WHERE transaksi_return.kode_transaksi IS NULL ");
 }

 function GetDetailTransaksi($where = "")
 {
     return DB::select("select transaksi_detail.kode_transaksi,transaksi_detail.kode_produk,produk.nama_produk,
        produk.status_stok,format(transaksi_detail.harga_beli,0)as harga_beli,transaksi_detail.harga,
        transaksi_detail.jumlah,(transaksi_detail.harga*transaksi_detail.jumlah)as subtotal from
        transaksi_detail inner join produk on transaksi_detail.kode_produk=produk.kode_produk inner join transaksi
        on transaksi_detail.kode_transaksi=transaksi.kode_transaksi $where order by transaksi_detail.kode_transaksi");
 }

 function GetSoldItem($where = "")
 {
     return DB::select("SELECT transaksi_detail.kode_produk,produk.nama_produk,transaksi_detail.harga AS harga,
        SUM(transaksi_detail.jumlah)AS jumlah, SUM(transaksi_detail.harga*transaksi_detail.jumlah) AS subtotal
        FROM transaksi_detail INNER JOIN produk ON
        transaksi_detail.kode_produk = produk.kode_produk INNER JOIN transaksi ON transaksi_detail.kode_transaksi =
        transaksi.kode_transaksi LEFT JOIN transaksi_return ON transaksi.kode_transaksi=transaksi_return.kode_transaksi
        $where AND transaksi_return.kode_return IS NULL GROUP BY produk.kode_produk");
 }
