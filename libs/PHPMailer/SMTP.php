<?php
namespace PHPMailerLite;
class SMTP {
    private $socket;
    public $last_response = '';
    public function connect($host, $port=25, $timeout=10) {
        $this->socket = @fsockopen($host, $port, $errno, $errstr, $timeout);
        if (!$this->socket) {
            throw new Exception('SMTP connect failed: ' . $errstr . ' (' . $errno . ')');
        }
        stream_set_timeout($this->socket, $timeout);
        $this->getLine();
    }
    private function sendLine($line) {
        fputs($this->socket, $line . "\r\n");
    }
    private function getLine() {
        $data = '';
        while (!feof($this->socket)) {
            $line = fgets($this->socket, 515);
            $data .= $line;
            if (substr($line,3,1) == ' ') break;
        }
        $this->last_response = $data;
        return $data;
    }
    public function helo($domain='localhost') {
        $this->sendLine('EHLO ' . $domain);
        return $this->getLine();
    }
    public function authLogin($user, $pass) {
        $this->sendLine('AUTH LOGIN');
        $this->getLine();
        $this->sendLine(base64_encode($user));
        $this->getLine();
        $this->sendLine(base64_encode($pass));
        return $this->getLine();
    }
    public function mailFrom($from) { $this->sendLine('MAIL FROM:<'.$from.'>'); return $this->getLine(); }
    public function rcptTo($to) { $this->sendLine('RCPT TO:<'.$to.'>'); return $this->getLine(); }
    public function data($body) { $this->sendLine('DATA'); $this->getLine(); $this->sendLine($body . "\r\n."); return $this->getLine(); }
    public function quit() { $this->sendLine('QUIT'); return $this->getLine(); }
    public function close() { if ($this->socket) fclose($this->socket); }
}
?>