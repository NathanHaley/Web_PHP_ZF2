<?php
namespace Exam\Model\Entity;

use Zend\Form\Annotation;

/**
 * @Annotation\Name("testAttemptAnswer")
 * @Annotation\Hydrator({"type": "Zend\Stdlib\Hydrator\ClassMethods", "options": {"underscoreSeparatedKeys": false}})
 *
 * @Entity @Table(name="e_exam_attempt_answer")
 */
class TestAttemptAnswer
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
     * @Column(name="eea_id", type="integer")
     */
    protected $EeaId;

    /**
     * @Annotation\Exclude()
     *
     * @Column(name="eeq_id", type="integer")
     */
    protected $EeqId;

    /**
     * @Annotation\Exclude()
     *
     * @Column(name="answer", type="string")
     */
    protected $answer;

    /**
     * @Annotation\Exclude()
     *
     * @Column(name="isvalid", type="integer")
     */
    protected $IsValid;

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
    public function getEeaId()
    {
        return $this->EeaId;
    }

    /**
     *
     * @param unknown_type $EeaId
     */
    public function setEeaId($EeaId)
    {
        $this->EeaId = $EeaId;
        return $this;
    }

    /**
     *
     * @return the unknown_type
     */
    public function getEeqId()
    {
        return $this->EeqId;
    }

    /**
     *
     * @param unknown_type $EeqId
     */
    public function setEeqId($EeqId)
    {
        $this->EeqId = $EeqId;
        return $this;
    }

    /**
     *
     * @return the unknown_type
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     *
     * @param unknown_type $answer
     */
    public function setAnswer($answer)
    {

        $answer = json_encode($answer);

        $this->answer = $answer;
        return $this;
    }

    /**
     *
     * @return the unknown_type
     */
    public function getIsValid()
    {
        return $this->IsValid;
    }

    /**
     *
     * @param unknown_type $IsValid
     */
    public function setIsValid($IsValid)
    {
        $this->IsValid = $IsValid;
        return $this;
    }


}
