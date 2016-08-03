<?php
namespace Account\Model\Entity;

/**
 * @Annotation\Name("phone")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ClassMethods")
 *
 * @Entity @Table(name="a_user_phone")
 */
class Phone
{
    use \Util\Model\Entity\Traits\EntityBase;

    /**
     * @Annotation\Exclude()
     *
     * @Id @Column(type="integer") @GeneratedValue
     */
    protected $id;
    /**
     * @Column(type="integer")
     */
    protected $a_user_id;
    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\AllowEmpty({"true"})
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Options({"label":"Your Phone Number:"})
     * @Annotation\Validator({"name":"StringLength","options":{"min":5,"max":255}})
     * @Annotation\Validator({"name":"RegEx",
     *                          "options": {
     *                              "pattern": "/^[\d-\/]+$/",
     *                              "messages":
     *                                  {"regexNotMatch":"Only numbers, 0-9, and dashes '-'"}
     *                           }
     *                       })
     * @Annotation\Attributes({"type":"tel","pattern":"^[\d-/]+$","messages":{"regexNotMatch":"Only numbers, 0-9, and dashes '-'"}})
     * @Annotation\Flags({"priority": "100"})
     *
     * @Column(type="string")
     */
    protected $phone;
    /**
     * @Column(type="string")
     */
    protected $country_code;
    /**
     * @Column(type="string")
     */
    protected $area_code;
    /**
     * @Column(type="string")
     */
    protected $exchange_code;
    /**
     * @Column(type="string")
     */
    protected $line_code;
    /**
     * @Column(type="string")
     */
    protected $extension_code;
    /**
     * @Column(type="integer")
     */
    protected $context_id;
    /**
     * @ManyToOne(targetEntity="Context")
     * @JoinColumn(name="context_id", referencedColumnName="id")
     * @Column(type="integer")
     */
    protected $context;
    /**
     * @ManyToOne(targetEntity="User", inversedBy="phones")
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
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     *
     * @param unknown_type $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     *
     * @return the unknown_type
     */
    public function getCountryCode()
    {
        return $this->country_code;
    }

    /**
     *
     * @param unknown_type $country_code
     */
    public function setCountryCode($country_code)
    {
        $this->country_code = $country_code;
        return $this;
    }

    /**
     *
     * @return the unknown_type
     */
    public function getAreaCode()
    {
        return $this->area_code;
    }

    /**
     *
     * @param unknown_type $area_code
     */
    public function setAreaCode($area_code)
    {
        $this->area_code = $area_code;
        return $this;
    }

    /**
     *
     * @return the unknown_type
     */
    public function getExchangeCode()
    {
        return $this->exchange_code;
    }

    /**
     *
     * @param unknown_type $exchange_code
     */
    public function setExchangeCode($exchange_code)
    {
        $this->exchange_code = $exchange_code;
        return $this;
    }

    /**
     *
     * @return the unknown_type
     */
    public function getLineCode()
    {
        return $this->line_code;
    }

    /**
     *
     * @param unknown_type $line_code
     */
    public function setLineCode($line_code)
    {
        $this->line_code = $line_code;
        return $this;
    }

    /**
     *
     * @return the unknown_type
     */
    public function getExtensionCode()
    {
        return $this->extension_code;
    }

    /**
     *
     * @param unknown_type $extension_code
     */
    public function setExtensionCode($extension_code)
    {
        $this->extension_code = $extension_code;
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
