<?php
namespace Util\Model\Entity;

/**
 * @Annotation\Name("country")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ClassMethods")
 *
 * @Entity @Table(name="u_country")
 */
class Country
{
    use Util\Model\Entity\Traits\EntityBase;

    /**
     * @Annotation\Exclude()
     *
     * @Id @Column(type="integer") @GeneratedValue
     */
    protected $id;
    /**
     * @Column(type="string")
     */
    protected $code;
    /**
     * @Column(type="string")
     */
    protected $name;
    /**
     *
     * @OneToMany(targetEntity="u_country_state", mappedBy="country")
     */
    protected $states;

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
    public function getStates()
    {
        return $this->states;
    }

    /**
     *
     * @param unknown_type $states
     */
    public function setStates($states)
    {
        $this->states = $states;
        return $this;
    }


}
