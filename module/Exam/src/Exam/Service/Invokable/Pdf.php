<?php
namespace Exam\Service\Invokable;

use Zend\ServiceManager\ServiceLocatorAwareInterface;

use ZendPdf\PdfDocument;
use ZendPdf\Font as PdfFont;
use ZendPdf\Page as PdfPage;
use Exam\Model\Test;
use Exam\Model\TestManager;

class Pdf implements ServiceLocatorAwareInterface
{
    /**
     *
     * @var \Zend\ServiceManager\ServiceManager
     */
    protected $services;

    /**
     * Generates certificate of exellence and
     * triggers an event when the file is generated
     * @param \User\Model\Entity\User $user
     * @param string $examName
     */
    public function generateCertificate($user, $examName, $logo)
    {
        //@todo add category like ZF2, PHP, etc to embed. Just ZF2 currently

        $testManager = $this->services->get('test-manager');

        //$exam = $testManager->get($examId);

        $config = $this->services->get('config');
        $pdf = PdfDocument::load($config['pdf']['exam_certificate']);

        // get the first page
        $page = $pdf->pages[0];

        // Extract the AdineKirnberg-Script font included in the PDF sample
        $font = $page->extractFont('AdineKirnberg-Script');
        $page->setFont($font, 80);
        // and write the name of the user with it
        $page->drawText($user->getFirstName().' '.$user->getLastName(), 200, 280);

        // after that use Time Bold to write the name of the exam
        $font = PdfFont::fontWithName(PdfFont::FONT_TIMES_BOLD);
        $page->setFont($font, 40);
        $page->drawText($examName, 200, 120);

        // get the right size to do some calculations
        $size = getimagesize($logo);
        // load the image
        $image = \ZendPdf\Image::imageWithPath($logo);
        $x = 580;
        $y = 440;
        // and finally draw the image
        $page->drawImage($image, $x, $y, $x+$size[0], $y+$size[1]);

        return $pdf;
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
