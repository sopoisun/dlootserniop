<?php

namespace App\Libraries;

use App\Libraries\Fpdf\Fpdf;

class Penjualan extends Fpdf{
    public function __construct($data=[])
    {
        parent::__construct('L', 'mm', 'A4');
		$this->SetMargins(10, 10);

        foreach($data as $key => $val){
            if( isset($this->$key) ){
                $this->$key = $val;
            }
        }
    }

    protected $data = [];
    protected $header = '';

    public function Header(){
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 8, $this->header, '', 1);
        $this->Ln(1);
    }

    public function WritePage()
    {
        $this->addPage();

        // table header
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(10, 8, "#", 1, 0, 'C');
        $this->Cell(40, 8, "Nota", 1, 0);
        $this->Cell(40, 8, "Kasir", 1, 0);
        $this->Cell(25, 8, "Jumlah", 1, 0);
        $this->Cell(20, 8, "Pajak", 1, 0);
        $this->Cell(25, 8, "Jml+Pjk", 1, 0);
        $this->Cell(20, 8, "Potongan", 1, 0);
        $this->Cell(25, 8, "Total", 1, 0);
        $this->Cell(25, 8, "Bayar", 1, 0);
        $this->Cell(25, 8, "Kembali", 1, 0);
        $this->Cell(20, 8, "Status", 1, 1);

        $no = 0;
        foreach($this->data as $v)
        {
            $this->SetFont('Arial', '', 11);
            $no++;

            $this->Cell(10, 8, $no, 1, 0, 'C');
            $this->Cell(40, 8, $v['kode_transaksi'], 1, 0);
            $this->Cell(40, 8, $v['kasir'], 1, 0);
            $this->Cell(25, 8, number_format($v['jumlah'], 0, ',', '.'), 1, 0, 'R');
            $this->Cell(20, 8, number_format($v['ppn'], 0, ',', '.'), 1, 0, 'R');
            $this->Cell(25, 8, number_format($v['jumlah_ppn'], 0, ',', '.'), 1, 0, 'R');
            $this->Cell(20, 8, number_format($v['potongan'], 0, ',', '.'), 1, 0, 'R');
            $this->Cell(25, 8, number_format($v['total'], 0, ',', '.'), 1, 0, 'R');
            $this->Cell(25, 8, number_format($v['bayar'], 0, ',', '.'), 1, 0, 'R');
            $this->Cell(25, 8, number_format($v['kembali'], 0, ',', '.'), 1, 0, 'R');
            $this->Cell(20, 8, $v['status_transaksi'], 1, 1);
        }

        $this->Cell(10, 8, "", 1, 0, 'C');
        $this->Cell(80, 8, "Total", 1, 0);
        $this->Cell(25, 8, number_format($this->data->sum('jumlah'), 0, ',', '.'), 1, 0, 'R');
        $this->Cell(20, 8, number_format($this->data->sum('ppn'), 0, ',', '.'), 1, 0, 'R');
        $this->Cell(25, 8, number_format($this->data->sum('jumlah_ppn'), 0, ',', '.'), 1, 0, 'R');
        $this->Cell(20, 8, number_format($this->data->sum('potongan'), 0, ',', '.'), 1, 0, 'R');
        $this->Cell(25, 8, number_format($this->data->sum('total'), 0, ',', '.'), 1, 0, 'R');
        $this->Cell(25, 8, "", 1, 0, 'R');
        $this->Cell(25, 8, "", 1, 0, 'R');
        $this->Cell(20, 8, "", 1, 1);

        $this->Output();
        exit();
    }

    public function Footer(){
        $this->SetY(-10);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,5,'Application by ahmadrizalafani@gmail.com',0,1,'C');
    }
}
