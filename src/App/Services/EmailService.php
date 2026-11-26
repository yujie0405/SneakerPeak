<?php

namespace SneakerPeak\App\Services;

final class EmailService
{
    public static function send(string $to, string $subject, string $message): bool
    {
        $from = \sneakerpeakSetting('MAIL_FROM');
        if (!filter_var($from, FILTER_VALIDATE_EMAIL) || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        if ($host = \sneakerpeakSetting('MAIL_SMTP_HOST')) {
            ini_set('SMTP', $host);
        }
        if ($port = \sneakerpeakSetting('MAIL_SMTP_PORT')) {
            ini_set('smtp_port', $port);
        }

        return @mail($to, $subject, $message, ['From' => $from]);
    }
}
