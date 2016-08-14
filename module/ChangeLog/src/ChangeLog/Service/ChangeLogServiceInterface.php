<?php

namespace ChangeLog\Service;

use ChangeLog\Model\ChangeLogInterface;

interface ChangeLogServiceInterface
{
    /**
     * Fetch all active change log entries.
     * 
     * @return array\ChangeLogInterface[]
     */
    public function fetchAllChangeLogs();
    
    /**
     * Fetch one post by id
     * 
     * @return ChangeLogInteface
     */
    public function fetchChangeLog($id);
    
    /**
     * Updates or adds the provided ChangeLog.
     * 
     * @param ChangeLogInterface $changeLog
     * @return ChangeLogInterface
     */
    public function saveChangeLog(ChangeLogInterface $changeLog);
    
    /**
     * Delete provided Change Log entry. Return true on success, otherwise false.
     * 
     * @param ChangeLogInterface $changeLog
     * @return bool
     */
    public function deleteChangeLog(ChangeLogInterface $changeLog);
}