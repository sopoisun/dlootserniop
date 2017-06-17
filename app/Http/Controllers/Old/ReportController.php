<?php

namespace App\Http\Controllers\Old;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Validator;
use Session;

class ReportController extends Controller
{
    public function index(Request $request) // as login
    {
        if( $request->session()->has('userlogin') ){
            return redirect('oldapp/pertanggal');
        }

        if( $request->isMethod('post') ){
            $validator = Validator::make($request->all(), [
                'username'  => 'required',
                'password'  => 'required',
            ], [
                'username.required' => 'Username tidak boleh kosong.',
                'password.required' => 'Password tidak boleh kosong.',
            ]);
            if( $validator->fails() ){
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $username = $request->get('username');
            $password = $request->get('password');

            $user = User::where('username', $username)->where('password', md5($password))->get();

            if( $user->count() ){
                $user = $user->first();
                Session::put('userlogin', $user);

                return redirect('oldapp/perday');
            }

            return redirect()->back()->withInput()->withErrors(['failed' => 'Username / password salah.']);
        }

        return view('metronic.auth.login');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('userlogin');

        return redirect('oldapp');
    }

    public function reportPerDay(Request $request)
    {
        $data = $this->_reportPerDay($request);

        return view('metronic.report.pertanggal', $data);
    }

    public function reportPerDayPrint(Request $request)
    {
        $data = $this->_reportPerDay($request);

        $print = new \App\Libraries\Penjualan([
            'header' => "Laporan transaksi ".$data['tanggal']->format('d M Y'),
            'data'  => $data['reports'],
        ]);

        $print->WritePage();
    }

    protected function _reportPerDay($request)
    {
        $tanggal = $request->get('tanggal') ?
            \Carbon\Carbon::parse($request->get('tanggal')) : \Carbon\Carbon::now('Asia/Jakarta');

        return [
            'tanggal'   => $tanggal,
            'reports'   => collect(ConvertRawQueryToArray(
                GetTransaksi("WHERE SUBSTRING(transaksi.tanggal, 1, 10) = '".$tanggal->format('Y-m-d')."'")
            )),
        ];
    }

    public function ReportDetail(Request $request)
    {
        $kode = $request->get('kode');

        $data = [
            'nota'  => $kode,
            'orderDetail' => collect(ConvertRawQueryToArray(GetDetailTransaksi("where transaksi.kode_transaksi = '$kode'"))),
        ];

        return view('metronic.report.pertanggal-detail', $data);
    }

    public function soldItem(Request $request)
    {
        $data = $this->_soldItem($request);

        return view('metronic.report.pertanggal-solditem', $data);
    }

    public function soldItemPrint(Request $request)
    {
        $data = $this->_soldItem($request);

        $print = new \App\Libraries\SoldItem([
            'header' => "Laporan produk terjual (sold item) ".$data['tanggal']->format('d M Y'),
            'data'  => $data['produks'],
        ]);

        $print->WritePage();
    }

    protected function _soldItem($request)
    {
        $tanggal = $request->get('tanggal') ?
            \Carbon\Carbon::parse($request->get('tanggal')) : \Carbon\Carbon::now('Asia/Jakarta');

        return [
            'tanggal'   => $tanggal,
            'produks'   => collect(ConvertRawQueryToArray(
                GetSoldItem("WHERE SUBSTRING(transaksi.tanggal, 1, 10) = '".$tanggal->format('Y-m-d')."'"))),
        ];
    }

    public function reportRangeDay(Request $request)
    {
        $data = $this->_reportRangeDay($request);

        return view('metronic.report.periode', $data);
    }

    public function reportRangeDayPrint(Request $request)
    {
        $data = $this->_reportRangeDay($request);

        $print = new \App\Libraries\Penjualan([
            'header' => "Laporan transaksi ".$data['tanggal']->format('d M Y')." s/d ".$data['to_tanggal']->format('d M Y'),
            'data'  => $data['reports'],
        ]);

        $print->WritePage();
    }

    public function _reportRangeDay($request)
    {
        $tanggal    = $request->get('tanggal') ?
            \Carbon\Carbon::parse($request->get('tanggal')) : \Carbon\Carbon::now('Asia/Jakarta');
        $to_tanggal = $request->get('to_tanggal') ?
            \Carbon\Carbon::parse($request->get('to_tanggal')) : \Carbon\Carbon::now('Asia/Jakarta');

        return [
            'tanggal'   => $tanggal,
            'to_tanggal'=> $to_tanggal,
            'reports'   => collect(ConvertRawQueryToArray(
                GetTransaksi(
                    "WHERE SUBSTRING(transaksi.tanggal, 1, 10) between '".$tanggal->format('Y-m-d')."' AND '".$to_tanggal->format('Y-m-d')."'"
                ))),
        ];
    }

    public function soldItemRange(Request $request)
    {
        $data = $this->_soldItemRange($request);

        return view('metronic.report.periode-solditem', $data);
    }

    public function soldItemRangePrint(Request $request)
    {
        $data = $this->_soldItemRange($request);

        $print = new \App\Libraries\SoldItem([
            'header' => "Laporan produk terjual (sold item) ".$data['tanggal']->format('d M Y')." s/d ".$data['to_tanggal']->format('d M Y'),
            'data'  => $data['produks'],
        ]);

        $print->WritePage();
    }

    public function _soldItemRange($request)
    {
        $tanggal    = $request->get('tanggal') ?
            \Carbon\Carbon::parse($request->get('tanggal')) : \Carbon\Carbon::now('Asia/Jakarta');
        $to_tanggal = $request->get('to_tanggal') ?
            \Carbon\Carbon::parse($request->get('to_tanggal')) : \Carbon\Carbon::now('Asia/Jakarta');

         return [
            'tanggal'   => $tanggal,
            'to_tanggal'=> $to_tanggal,
            'produks'   => collect(ConvertRawQueryToArray(GetSoldItem(
                "WHERE (SUBSTRING(transaksi.tanggal, 1, 10) between '".$tanggal->format('Y-m-d')."' AND '".$to_tanggal->format('Y-m-d')."')"
            ))),
        ];
    }
}
;
