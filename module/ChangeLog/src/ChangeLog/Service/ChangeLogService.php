<?php

namespace ChangeLog\Service;

use ChangeLog\Mapper\ChangeLogMapperInterface;
use ChangeLog\Model\ChangeLogInterface;

class ChangeLogService implements ChangeLogServiceInterface
{
    /**
     * @var \ChangeLog\Mapper\ChangeLogMapperInterface
     */
    protected $changeLogMapper;

    /**
     * @param ChangeLogMapperInterface $changeLogMapper
     */
    public function __construct(ChangeLogMapperInterface $changeLogMapper)
    {
        $this->changeLogMapper = $changeLogMapper;
    }

    /**
     * {@inheritDoc}
     */
    public function fetchAllChangeLogs($orderby = 'id', $order = 'asc')
    {
        return $this->changeLogMapper->fetchAll($orderby, $order);
    }

    /**
     * {@inheritDoc}
     */
    public function fetchAllChangeLogsBase3($orderby = 'id', $order = 'desc')
    {
        return $this->changeLogMapper->fetchAllForBase3($orderby, $order);
    }

    /**
     * {@inheritDoc}
     * @param unknown $id
     */
    public function fetchChangeLog($id)
    {
        return $this->changeLogMapper->fetch($id);
    }

    /**
     * {@inheretDoc}
     */
    public function saveChangeLog(ChangeLogInterface $changeLog)
    {
        return $this->changeLogMapper->save($changeLog);
    }

    /**
     * {@inheritDoc}
     */
    public function deleteChangeLog(ChangeLogInterface $changeLog)
    {
        return $this->changeLogMapper->delete($changeLog);
    }


}
