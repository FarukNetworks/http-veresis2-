<?php
namespace App\Helpers;

class MailNotification 
{

    private $logger;
    private $settings;

    /**
     * @var MailSender
     */
    private $mailSender;

    function __construct(MailSender $mailSender, $logger, $settings) {
        $this->mailSender = $mailSender;
        $this->logger = $logger;
        $this->settings = $settings;
    }


    public function SendRegistrationNotification($recipientEmail, $userid)
    {
        error_reporting(E_ALL & ~E_STRICT);
                
        $link = APPBASEURL.'activateuser/'.$userid;
                               
        $subject = "Dobrodošli !";
                
        $body = "<p>Spoštovani !</p>
        <p>Hvala za registracijo na spletni strani pomocnik.meblosignalizacija.si.</p>
        <p>Za registracijo ste uporabili e-naslov:
        " . $recipientEmail ."</p>
        
        <p>Na vaš elektronski naslov ste prejeli povezavo za aktivacijo računa.</p>
        <p><a href=".$link.">".$link."</a></p>
        
        <p>Če boste imeli težave, nas kontaktirajte na e-mail naslov: info@meblosignalizacija.si</p>
        <p>Ekipa MebloSignalizacija d.o.o.</p>";
        
        try
        {
            $this->mailSender->SendMail($recipientEmail, $subject, $body);
        } 
        catch (\Exception $ex) {
            $this->logger->error($ex->getTraceAsString());
            throw new \Exception($ex->getMessage());
        }       
    }

    public function SendChangePasswordNotification($recipientEmail, $urlLink)
    {
        error_reporting(E_ALL & ~E_STRICT);
                               
        $subject = "Ponastavitev gesla";
                
        $body = "<p>Spoštovani !</p>
        <p>Nekdo je zahteval ponastavitev gesla za naslednji račun:</p>
        <p>
        " . $recipientEmail ."</p>
        
        <p>Če gre za pomoto, prezrite to e-pošto in nič se ne bo zgodilo.</p>
        <p>Za ponastavitev gesla obiščite naslednji naslov:</p>
        <p><a href=".$urlLink.">".$urlLink."</a></p>
        <p>Ponastavitev gesla je možna le v časovnem obdobju 24 ur od prejetega sporočila.</p>";
        
        try
        {
            $this->mailSender->SendMail($recipientEmail, $subject, $body);
        } 
        catch (\Exception $ex) {
            $this->logger->error($ex->getTraceAsString());
            throw new \Exception($ex->getMessage());
        }       
    }
    
    function notifyNewCartsOnServer() {
        error_reporting(E_ALL & ~E_STRICT);
                                         
        $subject = "Nova povpraševanja so na strežniku !";
                
        $body = "<p>Spoštovani !</p>
        <p>Nova povpraševanja so bila vnešena.</p>
        <p>Ekipa MebloSignalizacija d.o.o.</p>";
        
        try
        {
            $this->mailSender->SendMail($this->settings['mailMeblo'], $subject, $body);
        } 
        catch (\Exception $ex) {
            $this->logger->error($ex->getTraceAsString());
            throw new \Exception($ex->getMessage());
        } 
    }
    
    function notifyUserAboutSubmitCart($userEmail) {
        error_reporting(E_ALL & ~E_STRICT);
                                        
        $subject = "Sprejeto povpraševanje !";
                
        $body = "<p>Spoštovani !</p>
        <p>Hvala za vaše povpraševanje, v najkrajšem možnem času se vam bomo oglasili in vam pripravili ponudbo….</p>
        <p>Ekipa MebloSignalizacija d.o.o.</p>";
        
        try
        {
            $this->mailSender->SendMail($userEmail, $subject, $body);
        } 
        catch (\Exception $ex) {
            $this->logger->error($ex->getTraceAsString());
            throw new \Exception($ex->getMessage());
        } 
    }          
}