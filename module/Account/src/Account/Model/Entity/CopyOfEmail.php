<?php
namespace Account\Model\Entity;

/**
 * @Annotation\Name("email")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ClassMethods")
 *
 * @Entity @Table(name="a_user_email")
 */
class CopyOfEmail
{
    use Util\Model\Entity\Traits\EntityBase;

    /**
     * @Annotation\Exclude()
     *
     * @Id @Column(type="integer") @GeneratedValue
     */
    protected $id;
    /**
     * @Annotation\Exclude()
     *
     * @Column(type="integer")
     */
    protected $a_user_id;
    /**
    * @Annotation\Type("Zend\Form\Element\Email")
    * @Annotation\Validator({"name":"EmailAddress"})
    * @Annotation\Options({"label":"Email:"})
    * @Annotation\Validator({"name":"StringLength","options":{"min":6,"max":255}})
    * @Annotation\Attributes({"type":"email","required": true,"placeholder": "Email Address..."})
    * @Annotation\Flags({"priority": "600"})
    *
    * @Column(type="string")
    * @ManyToOne(targetEntity="User", inversedBy="emails")
    */
    protected $email;
    /**
     * @Annotation\Exclude()
     *
     * @Column(type="integer")
     */
    protected $context_id;
    /**
     * @Annotation\Exclude()
     *
     * @ManyToOne(targetEntity="Context")
     * @JoinColumn(name="context_id", referencedColumnName="id")
     * @Column(type="integer")
     */
    protected $context;
    /**
     * @Annotation\Exclude()
     *
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="a_user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     *
     * @return the unknown_type
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @param unknown_type $id
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     *
     * @return the unknown_type
     */
    public function getAUserId()
    {
        return $this->a_user_id;
    }

    /**
     *
     * @param unknown_type $a_user_id
     */
    public function setAUserId($a_user_id)
    {
        $this->a_user_id = $a_user_id;
        return $this;
    }

    /**
     *
     * @return the unknown_type
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     *
     * @param unknown_type $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     *
     * @return the unknown_type
     */
    public function getContextId()
    {
        return $this->context_id;
    }

    /**
     *
     * @param unknown_type $context_id
     */
    public function setContextId($context_id)
    {
        $this->context_id = $context_id;
        return $this;
    }

    /**
     *
     * @return the unknown_type
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     *
     * @param unknown_type $context
     */
    public function setContext($context)
    {
        $this->context = $context;
        return $this;
    }

    /**
     *
     * @return the unknown_type
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     *
     * @param unknown_type $user
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }



}
