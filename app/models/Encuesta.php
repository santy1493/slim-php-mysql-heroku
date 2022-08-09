<?php

use Fpdf\Fpdf;

class Encuesta
{
    public $id;
    public $cod_pedido;
    public $mesa;
    public $restaurante;
    public $mozo;
    public $cocinero;
    public $puntaje_total;
    public $comentario;

    public function crearEncuesta()
    {

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO encuestas (cod_pedido) VALUES (:cod_pedido)");
        $consulta->bindValue(':cod_pedido', $this->cod_pedido, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public function puntuarEncuesta()
    {

        $puntaje_total = (floatval($this->mesa) + floatval($this->restaurante) + floatval($this->mozo) + floatval($this->cocinero))/4;

        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE encuestas SET mesa = :mesa, restaurante = :restaurante, mozo = :mozo, cocinero = :cocinero, comentario = :comentario, puntaje_total = :puntaje_total WHERE id = :id");
        $consulta->bindValue(':mesa', $this->mesa, PDO::PARAM_STR);
        $consulta->bindValue(':restaurante', $this->restaurante, PDO::PARAM_STR);
        $consulta->bindValue(':mozo', $this->mozo, PDO::PARAM_STR);
        $consulta->bindValue(':cocinero', $this->cocinero, PDO::PARAM_STR);
        $consulta->bindValue(':puntaje_total', $puntaje_total, PDO::PARAM_STR);
        $consulta->bindValue(':comentario', $this->comentario, PDO::PARAM_STR);
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, descripcion, precio, sector FROM productos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
    }

    public static function obtenerProducto($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, descripcion, precio, sector FROM productos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Encuesta');
    }

    public static function obtenerEncuestaPorCodigo($cod_pedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM encuestas WHERE cod_pedido = :cod_pedido");
        $consulta->bindValue(':cod_pedido', $cod_pedido, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Encuesta');
    }

    public function modificarUsuario()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET usuario = :usuario, clave = :clave WHERE id = :id");
        $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarUsuario($usuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET fechaBaja = :fechaBaja WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $usuario, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }

    public static function DownloadPdf($directory, $amountPolls){
        $polls = self::getBestPolls($amountPolls);
        if ($polls) {
            if(!file_exists($directory)){
                mkdir($directory, 0777, true);
            }


            $pdf = new FPDF();
            $pdf->AddPage();

            // Letter type size
            $pdf->SetFont('Arial', 'B', 25);

            // Main title of the pdf
            $pdf->Cell(160, 15, 'Comanda', 1, 3, 'L');
            $pdf->Ln(3);

            $pdf->SetFont('Arial', '', 15);

            // Secondary title of the pdf
            $pdf->Cell(60, 4, 'TP Programacion III', 0, 1, 'L');
            $pdf->Cell(60, 0, '', 'T');
            $pdf->Ln(3);
            
            // Title of the table
            $pdf->Cell(60, 4, 'Santiago Fossa', 0, 1, 'L');
            $pdf->Cell(40, 0, '', 'T');
            $pdf->Ln(5);

            // Columns of Poll Class
            $header = array('ID', 'Pedido', 'Mesa', 'Resto', 'Mozo', 'Cocinero', 'Total', 'Comentario');
            
            // RGB colors of the table
            $pdf->SetFillColor(125, 0, 0);
            $pdf->SetTextColor(125);
            $pdf->SetDrawColor(50, 0, 0);
            $pdf->SetLineWidth(.3);
            $pdf->SetFont('Arial', 'B', 8);
            $w = array(10, 12, 15, 15, 15, 15, 15, 92);
            
            // Writes the header of the columns except the last one
            for ($i = 0; $i < count($header); $i++) {
                $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
            }
            $pdf->Ln();

            // Set the color of the text
            $pdf->SetFillColor(215, 209, 235);
            $pdf->SetTextColor(0);
            $pdf->SetFont('');
            // Data
            $fill = false;

            foreach ($polls as $poll) {
                //* Every column except the last one
                $pdf->Cell($w[0], 6, $poll->id, 'LR', 0, 'C', $fill);
                $pdf->Cell($w[1], 6, $poll->cod_pedido, 'LR', 0, 'C', $fill);
                $pdf->Cell($w[2], 6, $poll->mesa, 'LR', 0, 'C', $fill);
                $pdf->Cell($w[3], 6, $poll->restaurante, 'LR', 0, 'C', $fill);
                $pdf->Cell($w[4], 6, $poll->mozo, 'LR', 0, 'C', $fill);
                $pdf->Cell($w[5], 6, $poll->cocinero, 'LR', 0, 'C', $fill);
                $pdf->Cell($w[6], 6, $poll->puntaje_total, 'LR', 0, 'C', $fill);
                $pdf->Cell($w[7], 6, $poll->comentario, 'LR', 0, 'C', $fill);
                $pdf->Ln();
                $fill = !$fill;
            }

            $pdf->Cell(array_sum($w), 0, '', 'T');

            $newFilename = $directory.'Encuestas_' . date('Y_m_d') .'.pdf';
            $pdf->Output('F', $newFilename, 'I');

            $payload = json_encode(array("message" => 'pdf created ' . $newFilename));
        } else {
            $payload = json_encode(array("error" => 'error getting data'));
        }
        
        return $payload;
    }

    public static function getBestPolls($amount){
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta(
            'SELECT * FROM encuestas 
            ORDER BY puntaje_total DESC 
            LIMIT :amount');
        $query->bindParam(':amount', $amount);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
    }

}