<?php
namespace Exam\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;

class TestAttempt extends AbstractTableGateway
{
    public function __construct()
    {
        $this->table = 'x_users_tests_attempts';
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }
}