<?php
namespace Util\Model\Entity\Traits;


trait EntityBase
{
    /**
     * @Annotation\Exclude()
     *
     * @Column(name="add_id", type="integer")
     */
    protected $addId;
    /**
     * @Annotation\Exclude()
     *
     * @Column(name="add_ts", type="datetime")
     */
    protected $addTs;
    /**
     * @Annotation\Exclude()
     *
     * @Column(name="mod_id", type="integer")
     */
    protected $modId;
    /**
     * @Annotation\Exclude()
     *
     * @Column(name="mod_ts", type="datetime") @GeneratedValue
     */
    protected $modTs;
    /**
     * @Annotation\Exclude()
     *
     * @Column(name="stat_id", type="integer")
     */
    protected $statId;

    public function getAddId()
    {
        return $this->addId;
    }

    public function setAddId($id)
    {
        if(! isset($this->addId)){
           $this->addId = $id;
        }
    }

    public function getAddTs()
    {
        return $this->addTs;
    }

    public function setAddTs()
    {
        if(! isset($this->addTs)){
            $this->addTs= new \DateTime;
        }
    }

    public function getModId()
    {
        return $this->modId;
    }

    public function setModId($id)
    {
        $this->modId = $id;
    }

    public function getModTs()
    {
        return $this->modTs;
    }

    public function getStatId()
    {
        return $this->statId;
    }

    public function setStatId($statId)
    {
        $this->statId= $statId;
    }

    public function toArray()
    {
        return get_object_vars($this);
    }

    public function setAddStamp($id)
    {
        $this->setAddId($id);
        $this->setAddTs();
        $this->setStatId(10);
    }

    public function setModStamp($id)
    {
        $this->setModId($id);
    }

}
