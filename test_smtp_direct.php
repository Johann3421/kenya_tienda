<?php
require 'vendor/autoload.php';

use Swift_SmtpTransport;
use Swift_Mailer;
use Swift_Message;

try {
    // Intentar con SSL y 465
    $transport = (new Swift_SmtpTransport('mail.abadgroup.tech', 465, 'ssl'))
        ->setUsername('prueba@kenya.com.pe')
        ->setPassword('nY5g5nDhoqhha3Ah')
        ->setStreamOptions([
            'ssl' => [
                'allow_self_signed' => true,
                'verify_peer' => false,
                'verify_peer_name' => false,
            ]
        ]);

    $mailer = new Swift_Mailer($transport);

    $message = (new Swift_Message('Test Email Auth'))
        ->setFrom(['prueba@kenya.com.pe' => 'Kenya Tienda'])
        ->setTo(['loritox3421@gmail.com'])
        ->setBody('Prueba de envio');

    $result = $mailer->send($message);
    echo "SUCCESS SSL/465: $result correos enviados\n";
} catch (\Exception $e) {
    echo "ERROR SSL/465: " . $e->getMessage() . "\n";
}

try {
    // Intentar con TLS y 587
    $transport = (new Swift_SmtpTransport('mail.abadgroup.tech', 587, 'tls'))
        ->setUsername('prueba@kenya.com.pe')
        ->setPassword('nY5g5nDhoqhha3Ah')
        ->setStreamOptions([
            'ssl' => [
                'allow_self_signed' => true,
                'verify_peer' => false,
                'verify_peer_name' => false,
            ]
        ]);

    $mailer = new Swift_Mailer($transport);

    $message = (new Swift_Message('Test Email Auth 2'))
        ->setFrom(['prueba@kenya.com.pe' => 'Kenya Tienda'])
        ->setTo(['loritox3421@gmail.com'])
        ->setBody('Prueba de envio');

    $result = $mailer->send($message);
    echo "SUCCESS TLS/587: $result correos enviados\n";
} catch (\Exception $e) {
    echo "ERROR TLS/587: " . $e->getMessage() . "\n";
}
