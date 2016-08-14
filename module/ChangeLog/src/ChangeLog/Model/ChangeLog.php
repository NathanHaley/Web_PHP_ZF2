<?php
namespace ChangeLog\Model;

class ChangeLog implements ChangeLogInterface
{
    /**
     * @var int
     */
    protected $id;
    
    /**
     * @var int
     */
    protected $aUserId;
    
    /**
     * @var string
     */
    protected $description;
    
    /**
     * @var int
     */
    protected $addId;
    
    /**
     * @var timestamp
     */
    protected $addTs;
    
    /**
     * @var int
     */
    protected $modId;
    
    /**
     * @var timestamp
     */
    protected $modTs;
    
    /**
     * @var int
     */
    protected $statId;
    
    /**
     * {@inheritDoc}
     * @see \ChangeLog\Model\ChangeLogInterface::getId()
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @param int $id
     */
    public function setId($id)
    {
        if (! is_int($id)) {
            //throw new \InvalidArgumentException(__METHOD__.' only accepts integers. Input was a: '.get_class($id));
        }
        
        $this->id = $id;
    }
    
    /**
     * {@inheritDoc}
     * @see \ChangeLog\Model\ChangeLogInterface::getAUserId()
     */
    public function getAUserId()
    {
        return $this->aUserId;
    }
    
    /**
     * @param int $aUserId
     */
    public function setAUserId($aUserId)
    {
        if (! is_int($aUserId)) {
            //throw new \InvalidArgumentException(__METHOD__.' only accepts integers. Input was: '.get_class($aUserId));
        }
    
        $this->aUserId = $aUserId;
    }
    
    /**
     * {@inheritDoc}
     * @see \ChangeLog\Model\ChangeLogInterface::getDescription()
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * @param int $description
     */
    public function setDescription($description)
    {
        if (! is_string($description)) {
            throw new \InvalidArgumentException(__METHOD__.' only accepts strings. Input was a: '.get_class($description));
        }
    
        $this->description = $description;
    }
    
    /**
     * {@inheritDoc}
     * @see \ChangeLog\Model\ChangeLogInterface::getAddId()
     */
    public function getAddId()
    {
        return $this->addId;
    }
    
    /**
     * {@inheritDoc}
     */
    public function setAddId($aUserId)
    {
        if (! is_int($aUserId)) {
            //throw new \InvalidArgumentException(__METHOD__.' only accepts integers. Input was: '.get_class($aUserId));
        }
        $this->addId = $aUserId;
    }
    
    /**
     * {@inheritDoc}
     * @see \ChangeLog\Model\ChangeLogInterface::getAddTs()
     */
    public function getAddTs()
    {
        return $this->addTs;
    }
    
    /**
     * {@inheritDoc}
     */
    public function setAddTs($addTs)
    {
        
        //Leave orginal timestamp if set
        if ($this->addTs === null) {
            if ($addTs === null) {
                $addTs = date('Y-m-d H:i:s');
            } else {
            
                if (is_string($addTs)) {
                    
                    $tempTs = \DateTime::createFromFormat('Y-m-d H:i:s', $addTs);
                    
                    $errors = \DateTime::getLastErrors();
                    
                    if (!empty($errors['warning_count'])){
                        throw new \InvalidArgumentException('AddTs need to be a valid DateTime instance or string in format Y-m-d H:i:s T.');
                    }
                    
                    
                }
                
            } 
            $this->addTs = $addTs;
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \ChangeLog\Model\ChangeLogInterface::getModId()
     */
    public function getModId()
    {
        return $this->modId;
    }
    
    /**
     * {@inheritDoc}
     */
    public function setModId($aUserId)
    {
        if (! is_int($aUserId)) {
            //throw new \InvalidArgumentException(__METHOD__.' only accepts integers. Input was: '.get_class($aUserId));
        }
        
        $this->modId = $aUserId;
    }
    
    /**
     * {@inheritDoc}
     * @see \ChangeLog\Model\ChangeLogInterface::getModTs()
     */
    public function getModTs()
    {
        return $this->modTs;
    }
    
    /**
     * {@inheritDoc}
     */
    public function setModTs($modTs = null)
    {
        if ($modTs === null) {
            $modTs = date('Y-m-d H:i:s');
        } else {
        
            if (is_string($modTs)) {
                
                $tempTs = \DateTime::createFromFormat('Y-m-d H:i:s', $modTs);
                
                $errors = \DateTime::getLastErrors();
                
                if (!empty($errors['warning_count'])){
                    throw new \InvalidArgumentException('ModTs need to be a valid DateTime instance or string in format Y-m-d H:i:s T.');
                }
         
            }
            
        }        

        $this->modTs = $modTs;
    }
    
    /**
     * {@inheritDoc}
     * @see \ChangeLog\Model\ChangeLogInterface::getStatId()
     */
    public function getStatId()
    {
        return $this->statId;
    }
    
    /**
     * {@inheritDoc}
     */
    public function setStatId($statId)
    {
        if (! is_int($statId)) {
            //throw new \InvalidArgumentException(__METHOD__.' only accepts integers. Input was: '.get_class($statId));
        }
        
        $this->statId = $statId;
    }
    
    
    
    
}
