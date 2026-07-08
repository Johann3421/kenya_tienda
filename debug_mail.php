<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Config MAIL_MAILER: " . config('mail.default') . "\n";
echo "Config MAIL_HOST: " . config('mail.mailers.smtp.host') . "\n";
echo "Config MAIL_PORT: " . config('mail.mailers.smtp.port') . "\n";
echo "Config MAIL_USERNAME: " . config('mail.mailers.smtp.username') . "\n";
echo "Config MAIL_PASSWORD: " . (config('mail.mailers.smtp.password') ? 'SET' : 'EMPTY') . "\n";
echo "Config MAIL_ENCRYPTION: " . config('mail.mailers.smtp.encryption') . "\n";
