<?php
namespace App\Core;

class Mail {

  private array $HEADERS;
  private string $to;
  private const SMTP = 'smtp.free.fr';
  private const smtp_port = '25';
  private CONST sendmail_from = 'noreply-alaskastory@free.fr';

  //----------------------------------------------------------------------
  public function __construct(string $to)
  {
    // Dynamically set some php.ini parameters...
    ini_set('SMTP', self::SMTP);
    ini_set('smtp_port', self::smtp_port);
    ini_set('sendmail_from', self::sendmail_from);
    $this->to = $to;
    $this->HEADERS = array(
      'From' => 'noreply-alaskastory@free.fr',
      'Reply-To' => 'noreply-alaskastory@free.fr',
      'X-Mailer' => 'PHP/',
      'Content-type' => 'text/html; charset=iso-8859-1'
    );
  }
  //----------------------------------------------------------------------
  public function sendRegisterConfirmation(string $subject, $userpseudo) 
  {
    $selector = bin2hex(random_bytes(16));
    $token = random_bytes(32);
    $expires = date("U") + 1800;    // 30 minutes
    $url = 'http://p4.fr/users/registerconfirmed?selector='.$selector.'&validator='.bin2hex($token).'&pseudo='.$userpseudo;

    $hashedtoken = password_hash($token, PASSWORD_DEFAULT);    
    $message = "<p>We received a register request</p>";
    $message .= "<p>Click on this link to confirm</p>";
    $message .= "<a href='".$url."'>".$url."</a>";

    $result = mail($this->to, 
                    $subject, 
                    $message, 
                    $this->HEADERS);
    return $result;
  }
}

?>