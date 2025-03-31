<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Service\PdfGeneratorService;

class EmailService
{
    private $mailer;
    private $pdfGenerator;

    public function __construct(MailerInterface $mailer, PdfGeneratorService $pdfGenerator)
    {
        $this->mailer = $mailer;
        $this->pdfGenerator = $pdfGenerator;
    }

    public function sendEmailWithPdf(string $to, string $subject, string $htmlContent): void
    {
        // Agregar logs para depurar
        dump('Iniciando la generación del PDF...');
        
        // Definir la ruta donde se guardará el archivo PDF
        $pdfFilePath = 'public/pdf/confirmacion.pdf';
    
        // Generar el PDF desde el contenido HTML y guardarlo en el archivo
        $pdfContent = $this->pdfGenerator->generatePdf($htmlContent, $pdfFilePath);
    
        // Verificar si el PDF fue guardado correctamente
        if (!file_exists($pdfFilePath)) {
            throw new \RuntimeException('El archivo PDF no fue generado correctamente.');
        }
    
        dump('PDF generado correctamente, continuando con el envío del correo...');
    
        // Crear el correo electrónico
        $email = (new Email())
            ->from('noreply@miapp.com')
            ->to($to)
            ->subject($subject)
            ->html('<p>Por favor, revisa el archivo adjunto.</p>') // Contenido HTML
            ->attachFromPath($pdfContent, 'confirmacion.pdf', 'application/pdf'); // Adjuntar el PDF
    
        // Enviar el correo electrónico
        $this->mailer->send($email);
    
        dump('Correo enviado correctamente.');
    }
}
