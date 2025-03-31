<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\EmailService;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'app:enviarEmail', description: 'Enviar un correo electrónico')]
class EnviarEmailCommand extends Command
{
    private EmailService $emailService;

    public function __construct(EmailService $emailService)
    {
        parent::__construct();
        $this->emailService = $emailService;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Interactivamente pide datos para enviar un correo electrónico');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Envío de correo electrónico');

        // Pedir el destinatario
        $to = $io->ask('¿Cuál es el correo del destinatario?', null, function ($email) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new \RuntimeException('Debe proporcionar un correo electrónico válido.');
            }
            return $email;
        });

        // Pedir el asunto del correo
        $subject = $io->ask('¿Cuál es el asunto del correo?', 'Sin asunto'); // "Sin asunto" como valor predeterminado

        // Pedir el contenido HTML del correo
        $htmlContent = $io->ask('¿Cuál es el contenido del correo en formato HTML?', '<p>Contenido predeterminado</p>');

        $io->text(sprintf('Enviando correo electrónico a %s con asunto "%s"', $to, $subject));

        try {
            // Enviar el correo
            $this->emailService->sendEmailWithPdf($to, $subject, $htmlContent);
            $io->success('Correo electrónico enviado correctamente');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('Error al enviar el correo: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
