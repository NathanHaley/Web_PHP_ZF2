<?php
namespace Account\Model\Entity;

/**
 * @Entity @Table(name="a_user_email")
 */
class Email
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
    private $email;
    /**
     * @Column(type="integer")
     */
    private $context_id;
    /**
     * @ManyToOne(targetEntity="User", inversedBy="emails")
     * @JoinColumn(name="a_user_id", referencedColumnName="id")
     */
    private $user;


}
