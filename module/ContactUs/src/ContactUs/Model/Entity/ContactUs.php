<?php
namespace ContactUs\Model\Entity;

use Zend\Form\Annotation;

/**
 * @Annotation\Name("contactUs")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ClassMethods")
 *
 * @Entity @Table(name="contact_us_form")
 */
class ContactUs
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
    * @Annotation\Flags({"priority": "200"})
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
     *
     * @Column(type="string")
     */
    protected $fullname;

    /**
     * @Annotation\Type("Zend\Form\Element\Textarea")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Options({"label":"Comments:"})
     * @Annotation\Attributes({"required": true,"placeholder":"Type comments here...","maxlength":"500"})
     * @Annotation\Flags({"priority": "100"})
     * @Annotation\Validator({"name":"StringLength", "options":{"max":500}})
     *
     * @Column(type="string")
     */
    protected $comments;

    /**
     * @Annotation\Exclude()
     *
     * @cdate @GeneratedValue @Column(type="datetime")
     */
    protected $cdate;


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
     * @return the $full_name
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * @param field_type $full_name
     */
    public function setFullname($name)
    {
        $this->fullname = $name;
    }

    /**
     * @return the $comments
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param field_type $comments
     */
    public function setComments($comment)
    {
        $this->comments = $comment;
    }

    /**
     * @return the $cdate
     */
    public function getCdate()
    {
        return $this->cdate;
    }

    /**
     * @param field_type $comments
     */
    public function setCdate($cdate)
    {
        $this->cdate = $cdate;
    }

}
