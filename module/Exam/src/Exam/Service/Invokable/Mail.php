<?php
namespace Exam\Service\Invokable;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\Mail\Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

class Mail implements ServiceLocatorAwareInterface
{
    /**
     *
     * @var \Zend\ServiceManager\ServiceManager
     */
    protected $services;

    /**
     * Sends award certificate
     *
     * @param \User\Model\Entity\User $user
     * @param array $exam
     * @param \ZendPdf\Document $pdf
     */
    public function sendCertificate($user, $examId, $pdf)
    {
        $translator = $this->services->get('translator');
        $mail = new Message();
        $mail->addTo($user->getEmail());

        $testManager = $this->services->get('test-manager');
        $exam = $testManager->get($examId);

        $config = $this->services->get('config');
        $adminEmail = $config['application']['admin-email'];

$text = 'You are genius!
For exam '.$exam['name'].' you answered all the questions correctly.
Therefore we are sending you as a gratitude this free award certificate. NOTE: not attaching PDF at this time.

';
        // we create a new mime message
        $mimeMessage = new MimeMessage();
        // create the original body as part
        $textPart = new MimePart($text);
        $textPart->type = "text/plain";
        // add the pdf document as a second part NOTE: disabled for now
        //$pdfPart = new MimePart($pdf->render());
        //$pdfPart->type = 'application/pdf';
        //$mimeMessage->setParts(array($textPart, $pdfPart));

        $mimeMessage->setParts(array($textPart));

        $mail->setBody($mimeMessage);

        $mail->setFrom($adminEmail);
        $mail->setSubject($translator->translate('Congratulations'));

        $transport = $this->services->get('mail-transport');
        $transport->send($mail);
    }

    /* (non-PHPdoc)
     * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::setServiceLocator()
    */
    public function setServiceLocator(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $this->services = $serviceLocator;
    }

    /* (non-PHPdoc)
     * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::getServiceLocator()
    */
    public function getServiceLocator()
    {
        return $this->services;
    }
}
