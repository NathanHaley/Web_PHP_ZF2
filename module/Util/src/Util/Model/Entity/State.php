<?php
namespace Util\Model\Entity;

/**
 * @Annotation\Name("state")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ClassMethods")
 *
 * @Entity @Table(name="u_country_state")
 */
class State
{
    use Util\Model\Entity\Traits\EntityBase;

    /**
     * @Annotation\Exclude()
     *
     * @Id @Column(type="integer") @GeneratedValue
     */
    protected $id;
    /**
     * @Column(type="integer")
     */
    protected $u_country_id;
    /**
     * @Column(type="string")
     */
    protected $code;
    /**
     * @Column(type="string")
     */
    protected $name;
    /**
     * @ManyToOne(targetEntity="Country", inversedBy="states")
     * @JoinColumn(name="u_country_id", referencedColumnName="id")
     */
    protected $country;

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
    public function getCode()
    {
        return $this->code;
    }

    /**
     *
     * @param unknown_type $code
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     *
     * @return the unknown_type
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * @param unknown_type $name
     */
    public function setName($name)
    {
        $this->name = $name;
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


}
