<?php
namespace App\Helpers;

use PHPMailer;
use SMTP;

/**
 * Description of MailSender
 *
 * @author b.pelko
 */
class MailSender 
{
    private $mail;
    
    private $isdebugmode = false;
    private $settings;

    public function __construct($settings) 
    {
        $this->createMailInstance($settings);
        $this->settings = $settings;
    }
    
    private function createMailInstance($settings)
    {
        $mail = new PHPMailer();
        $mail->CharSet = "UTF-8";
        $mail->SMTPDebug = 0;                                          // Enable verbose debug output
        $mail->isSMTP();                                                 // Set mailer to use SMTP
        $mail->Host = $settings['mailServerHost'];  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $settings['mailServerAuthUser'];                 // SMTP username
        $mail->Password = $settings['mailServerAuthPassword'];                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        //$mail->AuthType = 'LOGIN';
        $mail->Port = $settings['mailServerPort'];                                    // TCP port to connect to
        
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );                                                    // Set email format to HTML
        
        $this->mail = $mail;
    }
    
    /**
     * Send mail
     * @param type $to
     * @param type $subject
     * @param type $body
     * @param type $toName
     * @param type $from
     * @param type $fromName
     * @throws Exception
     */
    public function SendMail($to, $subject, $body, $toName = '', $from='', $fromName = '')
    {
        if (empty($from) || empty($fromName)) {
            $this->mail->setFrom($this->settings['mailFrom']);
        } else {
            $this->mail->setFrom($from, $fromName);
        }        
        
        $this->mail->addAddress($to, $toName);
        
        //$this->mail->addAddress($to, $toName);
        $this->mail->isHTML(true);        
        $this->mail->Subject = $subject;
        $this->mail->Body    = $body;
        
        if(!$this->mail->send()) {
            throw new \Exception ("Error on sending mail: ".$this->mail->ErrorInfo);
        }
    }    
}
