 
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
        $this->Cell(30, 10, 'Listagem de Categorias', 0, 0, 'C');

        // Linha
        $this->Line(0, 20, $this->GetPageWidth(), 20);

        // Quebra de linha
        $this->Ln(20);
    }
    
    // Page footer
    function Footer()
    {
        date_default_timezone_set("America/Sao_Paulo");
        // Posição a 1,5 cm do fim
        $this->SetY(-15);
        // Arial itálico 8
        $this->SetFont('Arial', 'I', 8);
        // Número da página
        $this->Cell(0, 10, 'Página ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
        $this->Cell(0, 10, 'Data: ' . date("d/m/Y - H:i:s"), 0, 0, 'R');
    }

    // Tabela simples
    function listagem()
    {
        try {
            $cabecalho = ["ID", "Peso", "Altura", "Data"];
            $dados = listarCategoria();

            // Cabeçalho
            foreach ($cabecalho as $col)
                $this->Cell(40, 9, $col, 1);
            $this->Ln();

            // Dados
            foreach ($dados as $linha) {
                $this->Cell(40, 6, $linha['id'], 1);
                $this->Cell(40, 6, $linha['peso'], 1);
                $this->Cell(40, 6, $linha['altura'], 1);
                $this->Cell(40, 6, $linha['data'], 1);
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
