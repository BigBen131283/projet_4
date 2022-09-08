<?php
namespace App\Core;

class Mailo2 extends Mail {

  private const SMTP = 'caracal.o2switch.net';
  private const SMTP_PORT = '465';
  private array $HEADERS;

  //----------------------------------------------------------------------
  public function __construct(string $to)
  {
    parent::__construct($to, __CLASS__);
  }
  //----------------------------------------------------------------------
  public function sendRegisterConfirmation(string $subject, $userpseudo) {

    // Get a token + selector object
    $tks = $this->createToken('/users/registerconfirmed'); 
    // Free mailer
    // Dynamically set some php.ini parameters...
    ini_set('SMTP', self::SMTP);
    ini_set('smtp_port', self::SMTP_PORT);
    ini_set('sendmail_from', $this->from);
    $this->HEADERS = array(
      'From' => 'aventure.alaska@blog.big-ben.fr',
      'Reply-To' => 'noreply@blog.big-ben.fr',
      'X-Mailer' => 'PHP/',
      'Content-type' => 'text/html; charset=iso-8859-1'
    );
    $result = mail($this->to, 
                    $subject, 
                    $this->buildMessage($subject, $tks), 
                    $this->HEADERS); 
    if($result) {
      $this->logger->db('email URL :'.$tks->getUrl());
      // Memorize a request record used later for confirmation
      $this->storeMailRequest($userpseudo, $tks);
      return true;
    }
    else {
      return false;
    }
  }
}

?>