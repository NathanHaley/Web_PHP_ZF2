<?php
namespace ContactUs\Model\Entity;

use Zend\Form\Annotation;
use User\Model\PasswordAwareInterface;
use Zend\Crypt\Password\PasswordInterface;

/**
 * @Annotation\Name("contactUs")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ClassMethods")
 *
 * @Entity @Table(name="contact_us_form")
 */
class ContactUs implements PasswordAwareInterface
{
    /**
     * @Annotation\Exclude()
     *
     * @Id @GeneratedValue @Column(type="integer")
     */
    protected $id;

    /**
    * @Annotation\Type("Zend\Form\Element\Email")
    * @Annotation\Validator({"name":"EmailAddress"})
    * @Annotation\Options({"label":"Email:"})
    * @Annotation\Attributes({"type":"email","required": true,"placeholder": "Email Address..."})
    * @Annotation\Flags({"priority": "500"})
    *
    * @Column(type="string")
    */
    protected $email;

    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Options({"label":"Name:"})
     * @Annotation\Attributes({"required": true,"placeholder":"Type name..."})
     * @Annotation\Flags({"priority": "300"})
     * @Annotation\Validator({"name":"Regex", "options":{"pattern":"/^[a-zA-Z][a-zA-Z0-9_-]{0,100}$/"}})
     *
     * @Column(type="string")
     */
    protected $name;

    /**
     * @Annotation\Type("Zend\Form\Element\Textarea")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Options({"label":"Comments:"})
     * @Annotation\Attributes({"required": true,"placeholder":"Type comments here..."})
     * @Annotation\Flags({"priority": "600"})
     * @Annotation\Validator({"name":"StringLength", "options""{"max":500}})
     *
     * @Column(type="string")
     */
    protected $comment;


    /**
     * @return the $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return the $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param field_type $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param field_type $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return the $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param field_type $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return the $comment
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param field_type $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

}
