<?php
namespace PHPMailerLite;
require_once __DIR__ . '/Exception.php';
require_once __DIR__ . '/SMTP.php';

class PHPMailerLite {
    public $is_smtp = false;
    public $Host, $Username, $Password, $SMTPSecure, $Port;
    public $From, $FromName;
    public $to = [];
    public $Subject = '';
    public $Body = '';
    public $ErrorInfo = '';

    public function __construct($exceptions = null) {}

    public function isSMTP() { $this->is_smtp = true; }

    public function setFrom($addr, $name='') { $this->From = $addr; $this->FromName = $name; }
    public function addAddress($addr, $name='') { $this->to[] = $addr; }

    public function isHTML($bool) { /* ignored for simplicity */ }

    public function send() {
        if ($this->is_smtp) {
            $smtp = new SMTP();
            // parse host: may include port
            $host = $this->Host;
            $port = $this->Port ?: 25;
            // try to connect
            $smtp->connect($host, $port);
            $smtp->helo('localhost');
            if ($this->Username) {
                $smtp->authLogin($this->Username, $this->Password);
            }
            $from = $this->From ?: $this->Username;
            $smtp->mailFrom($from);
            foreach ($this->to as $t) {
                $smtp->rcptTo($t);
            }
            $headers = "From: {$this->From}\r\nSubject: {$this->Subject}\r\n\r\n";
            $body = $this->Body;
            $resp = $smtp->data($headers . $body);
            $smtp->quit();
            $smtp->close();
            if (strpos($resp, '250') === false && strpos($resp, '354') === false) {
                $this->ErrorInfo = 'SMTP send failed: ' . substr($resp,0,200);
                throw new Exception($this->ErrorInfo);
            }
            return true;
        } else {
            // fallback to PHP mail()
            $headers = 'From: ' . ($this->From ?: 'no-reply@example.com') . "\r\n" . 'Content-type: text/html; charset=utf-8';
            $ok = mail(implode(',', $this->to), $this->Subject, $this->Body, $headers);
            if (!$ok) {
                $this->ErrorInfo = 'mail() returned false';
                throw new Exception($this->ErrorInfo);
            }
            return true;
        }
    }
}
?>