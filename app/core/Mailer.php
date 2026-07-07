<?php

class Mailer {
    public static function send(string $to, string $subject, string $htmlBody): bool {
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: Camagru <noreply@camagru.local>\r\n";

        return mail($to, $subject, $htmlBody, $headers);
    }
}
