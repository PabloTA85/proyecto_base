<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfGeneratorService
{
    private $dompdf;

    public function __construct()
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $this->dompdf = new Dompdf($options);
    }

    /**
     * Genera un PDF a partir del contenido HTML.
     *
     * @param string $html El contenido HTML que será convertido en PDF.
     * @param string|null $outputPath Ruta donde se guardará el archivo PDF (opcional).
     * @return string El contenido binario del PDF generado.
     */
    public function generatePdf(string $html, ?string $outputPath = null): string
    {
        // Cargar el HTML
        $this->dompdf->loadHtml($html);

        // (Opcional) Definir el tamaño del papel
        $this->dompdf->setPaper('A4', 'portrait');

        // Renderizar el PDF (convertir el HTML a PDF)
        $this->dompdf->render();

        // Guardar el archivo en la carpeta public/pdf
        if ($outputPath) {
            // Crear la carpeta si no existe
            if (!is_dir('public/pdf')) {
                mkdir('public/pdf', 0777, true);
            }
            
            // Guardar el archivo PDF en la ruta especificada
            file_put_contents($outputPath, $this->dompdf->output());
            return $outputPath;
        }

        // Devolver el contenido binario del PDF
        return $this->dompdf->output();
    }
}
