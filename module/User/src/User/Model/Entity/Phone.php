<?php
namespace Account\Model\Entity;

/**
 * @Entity @Table(name="a_user_phone")
 */
class Phone
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
    private $phone;
    /**
     * @Column(type="string")
     */
    private $country_code;
    /**
     * @Column(type="string")
     */
    private $area_code;
    /**
     * @Column(type="string")
     */
    private $exchange_code;
    /**
     * @Column(type="string")
     */
    private $line_code;
    /**
     * @Column(type="string")
     */
    private $extension_code;
    /**
     * @Column(type="integer")
     */
    private $context_id;
    /**
     * @ManyToOne(targetEntity="User", inversedBy="phones")
     * @JoinColumn(name="a_user_id", referencedColumnName="id")
     */
    private $user;


}
