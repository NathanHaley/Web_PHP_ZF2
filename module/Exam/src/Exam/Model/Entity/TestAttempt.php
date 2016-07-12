<?php
namespace Exam\Model\Entity;

use Zend\Form\Annotation;

/**
 * @Annotation\Name("testAttempt")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ClassMethods")
 *
 * @Entity @Table(name="x_users_tests_attempts")
 */
class TestAttempt
{
    /**
     * @Annotation\Exclude()
     *
     * @Id @GeneratedValue @Column(type="integer")
     */
    protected $id;

    /**
     * @Annotation\Exclude()
     *
     * @User_id @GeneratedValue @Column(type="integer")
     */
    protected $user_id;

    /**
     * @Annotation\Exclude()
     *
     * @Test_id @GeneratedValue @Column(type="integer")
     */
    protected $test_id;

    /**
     * @Annotation\Exclude()
     *
     * @Score_pct @GeneratedValue @Column(type="integer")
     */
    protected $score_pct;

    /**
     * @Annotation\Exclude()
     *
     * @Pass @GeneratedValue @Column(type="boolean")
     */
    protected $pass;

    /**
     * @Annotation\Exclude()
     *
     * @Stime @GeneratedValue @Column(type="integer")
     */
    protected $stime;
    /**
     * @Annotation\Exclude()
     *
     * @Duration @GeneratedValue @Column(type="integer")
     */
    protected $duration;

    /**
     * @Annotation\Exclude()
     *
     * @Cdate @GeneratedValue @Column(type="integer")
     */
    protected $cdate;

    /**
     * @Annotation\Exclude()
     *
     * @Mdate @GeneratedValue @Column(type="integer")
     */
    protected $mdate;

    /**
     * @Annotation\Exclude()
     *
     * @Muser_id @Column(type="integer")
     */
    protected $muser_id;


    /**
     * @return the $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param field_type $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return the $user_id
     */
    public function getUser_Id()
    {
        return $this->user_id;
    }

    /**
     * @param field_type $user_id
     */
    public function setUser_Id($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return the $test_id
     */
    public function getTest_id()
    {
        return $this->test_id;
    }

    /**
     * @param field_type $test_id
     */
    public function setTest_id($test_id)
    {
        $this->test_id = $test_id;
    }

    /**
     * @return the $score_pct
     */
    public function getScore_pct()
    {
        return $this->score_pct;
    }

    /**
     * @param field_type $score_pct
     */
    public function setScore_pct($score_pct)
    {
        $this->score_pct = $score_pct;
    }

    /**
     * @return the $pass
     */
    public function getPass()
    {
        return $this->pass;
    }

    /**
     * @param field_type $pass
     */
    public function setPass($pass)
    {
        $this->pass = $pass;
    }

    /**
     * @return the $stime
     */
    public function getStime()
    {
        return $this->stime;
    }

    /**
     * @param field_type $stime
     */
    public function setStime($stime)
    {
        $this->stime = $stime;
    }

    /**
     * @return the $duration
     */
    public function getDuration()
    {
        return $this->id;
    }

    /**
     * @param field_type $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * @return the $cdate
     */
    public function getCdate()
    {
        return $this->cdate;
    }

    /**
     * @param field_type $cdate
     */
    public function setCdate($cdate)
    {
        $this->cdate = $cdate;
    }

    /**
     * @return the $mdate
     */
    public function getMdate()
    {
        return $this->mdate;
    }

    /**
     * @param field_type $mdate
     */
    public function setMdate($mdate)
    {
        $this->mdate = $mdate;
    }

    /**
     * @return the $muser_id
     */
    public function getMuser_id()
    {
        return $this->muser_id;
    }

    /**
     * @param field_type $muser_id
     */
    public function setMuser_id($muser_id)
    {
        $this->muser_id = $muser_id;
    }

    public function toArray()
    {
        return get_object_vars($this);
    }



}
