<?php
namespace Exam\Model\Entity;

use Zend\Form\Annotation;

/**
 * @Annotation\Name("test")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ClassMethods")
 *
 * @Entity @Table(name="tests")
 */
class Test
{
    /**
     * @Annotation\Exclude()
     *
     * @Id @GeneratedValue @Column(type="integer")
     */
    protected $id;



}
