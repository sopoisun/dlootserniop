<?php

namespace App\Libraries;

use App\Libraries\Fpdf\Fpdf;

class SoldItem extends Fpdf{
    public function __construct($data=[])
    {
        parent::__construct('P', 'mm', 'A4');
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
        $this->Cell(100, 8, "Nama Produk", 1, 0);
        $this->Cell(30, 8, "Harga", 1, 0);
        $this->Cell(15, 8, "Terjual", 1, 0);
        $this->Cell(30, 8, "Subtotal", 1, 1);

        $no = 0;
        foreach($this->data as $v)
        {
            $this->SetFont('Arial', '', 11);
            $no++;

            $this->Cell(10, 8, $no, 1, 0, 'C');
            $this->Cell(100, 8, $v['nama_produk'], 1, 0);
            $this->Cell(30, 8, number_format($v['harga'], 0, ',', '.'), 1, 0, 'R');
            $this->Cell(15, 8, $v['jumlah'], 1, 0, 'C');
            $this->Cell(30, 8, number_format($v['subtotal'], 0, ',', '.'), 1, 1, 'R');
        }

        $this->Cell(10, 8, "", 1, 0, 'C');
        $this->Cell(145, 8, "Total", 1, 0);
        $this->Cell(30, 8, number_format(collect($this->data)->sum('subtotal'), 0, ',', '.'), 1, 1, 'R');

        $this->Output();
        exit();
    }

    public function Footer(){
        $this->SetY(-10);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,5,'Application by ahmadrizalafani@gmail.com',0,1,'C');
    }
}
