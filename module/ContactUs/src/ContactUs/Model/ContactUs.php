<?php
namespace ContactUs\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;

class ContactUs extends AbstractTableGateway
{
    public function __construct()
    {
        $this->table = 'contact_us_form';
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }

}
