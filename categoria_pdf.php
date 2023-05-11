<?php
require_once("./fpdf184/fpdf.php");
require_once("categoria_crud.php");

class CategoriaPDF extends FPDF
{
    // Page header
    function Header()
    {
        // Arial bold 15
        $this->SetFont('Arial', 'B', 15);
        // Move to the right
        $this->Cell(80);

        // Title
        $this->Cell(30, 10, 'Listagem de ', 0, 0, 'C');

        //Linha
        $this->Line(0, 20, $this->GetPageWidth(), 20);

        // Line break
        $this->Ln(20);
    }
    // Page footer
    function Footer()
    {
        date_default_timezone_set("america/sao_paulo");
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, iconv("utf-8", "cp1252", "Página: ") . $this->PageNo() . "/{nb}", 0, 0, "C");
        $this->Cell(0, 10, iconv("utf-8", "cp1252", "Data: ") . date("d/m/Y - H:i:s"), 0, 0, "R");
    }

    // Simple table
    function listagem()
    {
        try {
            $cabecalho = ["ID", "peso", "altura", "data"];
            $dados = listarCategoria();

            // Cabeçalho
            foreach ($cabecalho as $col)
                $this->Cell(55, 9, iconv("utf-8", "cp1252", $col), 1);
            $this->Ln();

            // Dados
            foreach ($dados as $linha) {
                foreach ($linha as $chave => $valor) {
                    if ($chave != "usuario_id") {
                        if ($chave == "tipo") {
                            if ($valor == 1) {
                                $this->Cell(55, 6, iconv("utf-8", "cp1252", "Entrada"), 1);
                            } else {
                                $this->Cell(55, 6, iconv("utf-8", "cp1252", "Saída"), 1);
                            }
                        } else {
                            $this->Cell(55, 6, iconv("utf-8", "cp1252", $valor), 1);
                        }
                    }
                }
                $this->Ln();
            }
        } catch (Exception $e) {
            echo "Erro: " . $e->getMessage() . "<br>";
        }
    }
}

$pdf = new CategoriaPDF("P","mm","A4");
$pdf->AliasNbPages();
$pdf->SetFont("Arial", "", 12);
$pdf->AddPage();
$pdf->listagem();
$pdf->Output();
