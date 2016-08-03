<?php
namespace Account\Model\Entity;

/**
 * @Annotation\Name("address")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ClassMethods")
 *
 * @Entity @Table(name="a_user_address")
 */
class Address
{
    use \Util\Model\Entity\Traits\EntityBase;

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
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Options({"label":"Line 1:"})
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Attributes({"required": true,"placeholder":"Line 1..."})
     * @Annotation\Validator({"name":"StringLength","options":{"min":1,"max":255}})
     * @Annotation\Flags({"priority": "500"})
     *
     * @Column(type="string")
     */
    protected $line_1;

    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Options({"label":"Line 2:"})
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Attributes({"required": true,"placeholder":"Line 2..."})
     * @Annotation\Validator({"name":"StringLength","options":{"min":1,"max":255}})
     * @Annotation\Flags({"priority": "500"})
     *
     * @Column(type="string")
     */
    protected $line_2;

    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Options({"label":"Line 3:"})
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Attributes({"placeholder":"Line 3..."})
     * @Annotation\Validator({"name":"StringLength","options":{"min":1,"max":255}})
     * @Annotation\Flags({"priority": "500"})
     *
     * @Column(type="string")
     */
    protected $line_3;

    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Options({"label":"Line 4:"})
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Attributes({"placeholder":"Line 4..."})
     * @Annotation\Validator({"name":"StringLength","options":{"min":1,"max":255}})
     * @Annotation\Flags({"priority": "500"})
     *
     * @Column(type="string")
     */
    protected $line_4;

    /**
     * @Column(type="integer")
     */
    protected $u_country_id;

    /**
     * @ManyToOne(targetEntity="Country")
     * @JoinColumn(name="u_country_id", referencedColumnName="id")
     */
    protected $country;

    /**
     * @Column(type="string")
     */
    protected $city;

    /**
     * @Column(type="integer")
     */
    protected $postal_code;

    /**
     * @Column(type="integer")
     */
    protected $u_country_state_id;

    /**
     * @ManyToOne(targetEntity="State")
     * @JoinColumn(name="$u_country_state_id", referencedColumnName="id")
     */
    protected $state;

    /**
     * @Annotation\Exclude()
     *
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
     * @ManyToOne(targetEntity="User", inversedBy="adresses")
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
    public function getLine1()
    {
        return $this->line_1;
    }

    /**
     *
     * @param unknown_type $line_1
     */
    public function setLine1($line_1)
    {
        $this->line_1 = $line_1;
        return $this;
    }

    /**
     *
     * @return the unknown_type
     */
    public function getLine2()
    {
        return $this->line_2;
    }

    /**
     *
     * @param unknown_type $line_2
     */
    public function setLine2($line_2)
    {
        $this->line_2 = $line_2;
        return $this;
    }

    /**
     *
     * @return the unknown_type
     */
    public function getLine3()
    {
        return $this->line_3;
    }

    /**
     *
     * @param unknown_type $line_3
     */
    public function setLine3($line_3)
    {
        $this->line_3 = $line_3;
        return $this;
    }

    /**
     *
     * @return the unknown_type
     */
    public function getLine4()
    {
        return $this->line_4;
    }

    /**
     *
     * @param unknown_type $line_4
     */
    public function setLine4($line_4)
    {
        $this->line_4 = $line_4;
        return $this;
    }

    /**
     *
     * @return the unknown_type
     */
    public function getUCountryId()
    {
        return $this->u_country_id;
    }

    /**
     *
     * @param unknown_type $u_country_id
     */
    public function setUCountryId($u_country_id)
    {
        $this->u_country_id = $u_country_id;
        return $this;
    }

    /**
     *
     * @return the unknown_type
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     *
     * @param unknown_type $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     *
     * @return the unknown_type
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     *
     * @param unknown_type $city
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     *
     * @return the unknown_type
     */
    public function getPostalCode()
    {
        return $this->postal_code;
    }

    /**
     *
     * @param unknown_type $postal_code
     */
    public function setPostalCode($postal_code)
    {
        $this->postal_code = $postal_code;
        return $this;
    }

    /**
     *
     * @return the unknown_type
     */
    public function getUCountryStateId()
    {
        return $this->u_country_state_id;
    }

    /**
     *
     * @param unknown_type $u_country_state_id
     */
    public function setUCountryStateId($u_country_state_id)
    {
        $this->u_country_state_id = $u_country_state_id;
        return $this;
    }

    /**
     *
     * @return the unknown_type
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     *
     * @param unknown_type $state
     */
    public function setState($state)
    {
        $this->state = $state;
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
