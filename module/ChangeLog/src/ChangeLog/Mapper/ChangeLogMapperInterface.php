<?php
namespace ChangeLog\Mapper;

use ChangeLog\Model\ChangeLogInterface;

interface ChangeLogMapperInterface
{
    /**
     * @param int|string $id
     * @return ChangeLogInterface
     * @throws \InvaldArgumentException
     */
    public function fetch($id);
    
    /**
     * @return array|ChangeLogInterface[]
     */
    public function fetchAll();
    
    /**
     * @param ChangeLogInterface $changeLogObject
     * 
     * @param ChangeLogInterface $changeLogObject
     * @return ChangeLogInterface
     * @throws \Exception
     */
    public function save(ChangeLogInterface $changeLogObject);
    
    /**
     * @param ChangeLogInterface $changeLogObject
     *
     * @return bool
     * @throws \Exception
     */
    public function delete(ChangeLogInterface $changeLogObject);
    
}