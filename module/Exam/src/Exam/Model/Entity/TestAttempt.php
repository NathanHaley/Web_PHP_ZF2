<?php
namespace Exam\Model\Entity;

use Zend\Form\Annotation;

/**
 * @Annotation\Name("testAttempt")
 * @Annotation\Hydrator({"type": "Zend\Stdlib\Hydrator\ClassMethods", "options": {"underscoreSeparatedKeys": false}})
 *
 * @Entity @Table(name="e_exam_attempt")
 */
class TestAttempt
{
    use \Util\Model\Entity\Traits\EntityBase;

    /**
     * @Annotation\Exclude()
     *
     * @Id @GeneratedValue
     * @Column(name="id", type="integer")
     */
    protected $id;

    /**
     * @Annotation\Exclude()
     *
     * @Column(name="a_user_id", type="integer")
     */
    protected $aUserId;

    /**
     * @Annotation\Exclude()
     *
     * @Column(name="ee_id", type="integer")
     */
    protected $eeId;

    /**
     * @Annotation\Exclude()
     *
     * @Column(name="score_pct", type="integer")
     */
    protected $scorePct;

    /**
     * @Annotation\Exclude()
     *
     * @Column(name="pass", type="integer")
     */
    protected $pass;

    /**
     * @Annotation\Exclude()
     *
     * @Column(name="start_ts", type="datetime")
     */
    protected $startTs;
    /**
     * @Annotation\Exclude()
     *
     * @Column(name="duration", type="integer")
     */
    protected $duration;

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
        return $this->aUserId;
    }

    /**
     *
     * @param unknown_type $aUserId
     */
    public function setAUserId($aUserId)
    {
        $this->aUserId = $aUserId;
        return $this;
    }

    /**
     *
     * @return the unknown_type
     */
    public function getEetId()
    {
        return $this->eeId;
    }

    /**
     *
     * @param unknown_type $testId
     */
    public function setEeId($eeId)
    {
        $this->eeId = $eeId;
        return $this;
    }

    /**
     *
     * @return the unknown_type
     */
    public function getScorePct()
    {
        return $this->scorePct;
    }

    /**
     *
     * @param unknown_type $scorePct
     */
    public function setScorePct($scorePct)
    {
        $this->scorePct = $scorePct;
        return $this;
    }

    /**
     *
     * @return the unknown_type
     */
    public function getPass()
    {
        return $this->pass;
    }

    /**
     *
     * @param unknown_type $pass
     */
    public function setPass($pass)
    {
        $this->pass = $pass;
        return $this;
    }

    /**
     *
     * @return the unknown_type
     */
    public function getStartTs()
    {
        return $this->startTs;
    }

    /**
     *
     * @param unknown_type $startTs
     */
    public function setStartTs($startTs)
    {
        $this->startTs = $startTs;
        return $this;
    }

    /**
     *
     * @return the unknown_type
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     *
     * @param unknown_type $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
        return $this;
    }


}
