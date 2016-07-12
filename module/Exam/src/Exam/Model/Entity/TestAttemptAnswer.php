<?php
namespace Exam\Model\Entity;

use Zend\Form\Annotation;

/**
 * @Annotation\Name("testAttemptAnswer")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ClassMethods")
 *
 * @Entity @Table(name="x_users_tests_attempts_answers")
 */
class TestAttemptAnswer
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
     * @Xuta_id @GeneratedValue @Column(type="integer")
     */
    protected $xuta_id;

    /**
     * @Annotation\Exclude()
     *
     * @Xeq_id @GeneratedValue @Column(type="integer")
     */
    protected $xeq_id;

    /**
     * @Annotation\Exclude()
     *
     * @Answer @GeneratedValue @Column(type="integer")
     */
    protected $answer;

    /**
     * @Annotation\Exclude()
     *
     * @Valid @GeneratedValue @Column(type="boolean")
     */
    protected $valid;

    /**
     * @Annotation\Exclude()
     *
     * @Cdate @GeneratedValue @Column(type="timestamp")
     */
    protected $cdate;

    /**
     * @Annotation\Exclude()
     *
     * @Mdate @GeneratedValue @Column(type="timestamp")
     */
    protected $mdate;

    /**
     * @Annotation\Exclude()
     *
     * @Muser_id @GeneratedValue @Column(type="integer")
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
     * @return the $xuta_id
     */
    public function getXuta_id()
    {
        return $this->xuta_id;
    }

    /**
     * @param field_type $xuta_id
     */
    public function setXuta_id($xuta_id)
    {
        $this->xuta_id = $xuta_id;
    }

    /**
     * @return the $xeq_id
     */
    public function getXeq_id()
    {
        return $this->xeq_id;
    }

    /**
     * @param field_type $xeq_id
     */
    public function setXeq_id($xeq_id)
    {
        $this->xeq_id = $xeq_id;
    }

    /**
     * @return the $answer
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * @param field_type $answer
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;
    }

    /**
     * @return the $valid
     */
    public function getValid()
    {
        return $this->valid;
    }

    /**
     * @param field_type $valid
     */
    public function setValid($valid)
    {
        $this->valid = $valid;
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
