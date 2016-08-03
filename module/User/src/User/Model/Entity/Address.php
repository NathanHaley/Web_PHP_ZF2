<?php
namespace Account\Model\Entity;

/**
 * @Entity @Table(name="a_user_address")
 */
class Address
{
    use Util\Model\Entity\Traits\EntityBase;

    /**
     * @Id @Column(type="integer") @GeneratedValue
     */
    private $id;
    /**
     * @Column(type="integer")
     */
    private $a_user_id;
    /**
     * @Column(type="string")
     */
    private $line_1;
    /**
     * @Column(type="string")
     */
    private $line_2;
    /**
     * @Column(type="string")
     */
    private $line_3;
    /**
     * @Column(type="string")
     */
    private $line_4;
    /**
     * @Column(type="string")
     */
    private $line_code;
    /**
     * @Column(type="integer")
     */
    private $u_country_id;
    /**
     * @Column(type="integer")
     */
    private $postal_code;
    /**
     * @Column(type="integer")
     */
    private $context_id;
    /**
     * @ManyToOne(targetEntity="User", inversedBy="adresses")
     * @JoinColumn(name="a_user_id", referencedColumnName="id")
     */
    private $user;


}
