<?php
require_once('tcpdf/tcpdf.php');

class CustomTCPDF extends TCPDF {
    public function Footer() {
        // Establecer el pie de página
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Lucía & Pablo - Sistemas de Información Web - 2023/2024', 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

// Obtener datos del formulario
$titulo = $_POST['titulo'];
$fecha = $_POST['fecha'];
$imagen = $_POST['imagen'];
$descripcion = $_POST['explicacion'];
$copyright = $_POST['copyright'];

// Crear una nueva instancia de TCPDF
$pdf = new CustomTCPDF();

// Establecer el título del documento
$pdf->SetTitle($titulo);

// Establecer la orientación y el tamaño de la página
$pdf->AddPage();

// Desactivar el encabezado predeterminado de TCPDF
$pdf->setPrintHeader(false);

// Configurar el encabezado solo para la primera página
// Definir la ruta de la imagen del logo
$logo = 'img/nasa_icon.png';

// Configurar el encabezado con la imagen del logo
$pdf->Image($logo, 10, 10, 30, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);

// Establecer el título y el subtítulo a la derecha del logo
$pdf->SetY(15);
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'NASA APOD', 0, false, 'R', 0, '', 0, false, 'M', 'M');

$pdf->SetY(20);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 10, 'Astronomy Picture of the Day', 0, false, 'R', 0, '', 0, false, 'M', 'M');

// Establecer el espacio entre el encabezado y el contenido
$pdf->setY(50);

// Imprimir los datos del formulario en el PDF
$pdf->SetFont('Helvetica', 'B', 12);
$pdf->Cell(0, 10, $titulo, 0, 1);
$pdf->SetFont('Helvetica', '', 10);
$pdf->Cell(0, 10, 'Copyright: ' . $copyright, 0, 1);
$pdf->Cell(0, 10, $fecha, 0, 1);

// Verificar si la URL de la imagen es un enlace de YouTube
if (strpos($imagen, 'youtube') !== false) {
    // Mostrar el enlace de YouTube como texto
    $pdf->Cell(0, 10, 'Youtube link: ' . $imagen, 0, 1);
} else {
    // Manejo de imágenes normales
    // Poner la imagen centrada horizontalmente
    $image_width = 150; // Ancho máximo de la imagen
    $page_width = $pdf->getPageWidth();

    // Obtener dimensiones de la imagen
    list($original_width, $original_height) = getimagesize($imagen);

    // Calcular la altura proporcional
    $image_height = ($image_width / $original_width) * $original_height;

    // Verificar si la altura supera un límite máximo
    $max_height = 120; // Establecer el límite máximo de altura
    if ($image_height > $max_height) {
        $image_height = $max_height; // Limitar la altura al máximo
        // Calcular el ancho proporcional basado en la altura máxima
        $image_width = ($max_height / $original_height) * $original_width;
    }

    // Posicionar la imagen en el PDF
    $image_x = ($page_width - $image_width) / 2;
    $image_y = $pdf->GetY() + 5; // Ajusta este valor según tu diseño
    $pdf->Image($imagen, $image_x, $image_y, $image_width, $image_height, '');

    // Ajustar la posición Y después de la imagen
    $pdf->setY($image_y + $image_height + 20);
}

// Imprimir la descripcion
$pdf->MultiCell(0, 10, $descripcion, 0, 1);

// Salida del PDF al navegador
$pdf->Output($titulo . '.pdf', 'I');
?>
