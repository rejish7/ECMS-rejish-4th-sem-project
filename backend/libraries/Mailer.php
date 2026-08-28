<?php
class Mailer {
    private $host;
    private $port;
    private $username;
    private $password;
    private $from;
    private $fromName;
    private $encryption;

    public function __construct() {
        $this->host = getenv('MAIL_HOST') ?: '';
        $this->port = getenv('MAIL_PORT') ?: '587';
        $this->username = getenv('MAIL_USERNAME') ?: '';
        $this->password = getenv('MAIL_PASSWORD') ?: '';
        $this->from = getenv('MAIL_FROM') ?: 'no-reply@ecms.local';
        $this->fromName = getenv('MAIL_FROM_NAME') ?: 'ECMS';
        $this->encryption = strtolower(getenv('MAIL_ENCRYPTION') ?: 'tls');
    }

    public function isConfigured() {
        return $this->host !== '';
    }

    public function send($to, $subject, $body) {
        $message = $this->buildMessage($to, $subject, $body);

        if ($this->host === '') {
            return $this->sendWithMail($to, $subject, $body);
        }

        return $this->sendWithSmtp($to, $message);
    }

    private function buildMessage($to, $subject, $body) {
        $host = $this->host !== '' ? $this->host : (gethostname() ?: 'localhost');

        $headers = "From: {$this->fromName} <{$this->from}>\r\n"
            . "To: <{$to}>\r\n"
            . "Subject: {$subject}\r\n"
            . "Date: " . date('r') . "\r\n"
            . "Message-ID: <" . uniqid('', true) . "@{$host}>\r\n"
            . "MIME-Version: 1.0\r\n"
            . "Content-Type: text/html; charset=UTF-8\r\n"
            . "Content-Transfer-Encoding: base64\r\n";

        return $headers . "\r\n" . rtrim(chunk_split(base64_encode($body)));
    }

    private function sendWithMail($to, $subject, $body) {
        $headers = "From: {$this->fromName} <{$this->from}>\r\n"
            . "MIME-Version: 1.0\r\n"
            . "Content-Type: text/html; charset=UTF-8";

        return @mail($to, $subject, $body, $headers);
    }

    private function sendWithSmtp($to, $message) {
        $remote = ($this->encryption === 'ssl' ? 'ssl://' : 'tcp://') . $this->host . ':' . $this->port;
        $conn = @stream_socket_client($remote, $errno, $errstr, 30);
        if (!$conn) {
            return false;
        }

        if (!$this->expect($conn, '220')) {
            fclose($conn);
            return false;
        }

        fwrite($conn, "EHLO {$this->host}\r\n");
        if (!$this->expect($conn, '250')) {
            fclose($conn);
            return false;
        }

        if ($this->encryption === 'tls') {
            fwrite($conn, "STARTTLS\r\n");
            if (!$this->expect($conn, '220')) {
                fclose($conn);
                return false;
            }
            if (!stream_socket_enable_crypto($conn, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                fclose($conn);
                return false;
            }
            fwrite($conn, "EHLO {$this->host}\r\n");
            if (!$this->expect($conn, '250')) {
                fclose($conn);
                return false;
            }
        }

        if ($this->username !== '') {
            fwrite($conn, "AUTH LOGIN\r\n");
            if (!$this->expect($conn, '334')) {
                fclose($conn);
                return false;
            }
            fwrite($conn, base64_encode($this->username) . "\r\n");
            if (!$this->expect($conn, '334')) {
                fclose($conn);
                return false;
            }
            fwrite($conn, base64_encode($this->password) . "\r\n");
            if (!$this->expect($conn, '235')) {
                fclose($conn);
                return false;
            }
        }

        fwrite($conn, "MAIL FROM: <{$this->from}>\r\n");
        if (!$this->expect($conn, '250')) {
            fclose($conn);
            return false;
        }

        fwrite($conn, "RCPT TO: <{$to}>\r\n");
        if (!$this->expect($conn, '250')) {
            fclose($conn);
            return false;
        }

        fwrite($conn, "DATA\r\n");
        if (!$this->expect($conn, '354')) {
            fclose($conn);
            return false;
        }

        fwrite($conn, $message . "\r\n.\r\n");
        if (!$this->expect($conn, '250')) {
            fclose($conn);
            return false;
        }

        fwrite($conn, "QUIT\r\n");
        fclose($conn);
        return true;
    }

    private function expect($conn, $code) {
        $response = '';
        while ($line = fgets($conn, 512)) {
            $response .= $line;
            if (isset($line[3]) && $line[3] === ' ') {
                break;
            }
        }
        return strpos($response, $code) === 0;
    }
}