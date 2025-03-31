<?php
namespace App\Tests;

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Exception;

// Configurar el transporte desde el DSN
$transport = Transport::fromDsn('smtp://localhost:1025');
$mailer = new Mailer($transport);

// Crear el correo
$email = (new Email())
    ->from('no-reply@example.com')
    ->to('test@example.com')
    ->subject('Prueba desde Symfony Mailer')
    ->text('Este es un correo de prueba.')
    ->html('<p>Este es un correo de prueba.</p>');

// Enviar el correo
try {
    $mailer->send($email);
    echo "Correo enviado correctamente.\n";
} catch (Exception $e) {
    echo "Error al enviar correo: " . $e->getMessage() . "\n";
}
