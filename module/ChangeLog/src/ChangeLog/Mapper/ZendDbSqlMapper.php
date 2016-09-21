<?php
namespace ChangeLog\Mapper;

use ChangeLog\Model\ChangeLogInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;
use Zend\Stdlib\Hydrator\HydratorInterface;


class ZendDbSqlMapper implements ChangeLogMapperInterface
{
    /**
     * @var \Zend\Db\Adapter\AdapterInterface
     */
    protected $dbAdapter;

    /**
     * @var \Zend\Db\Adapter\HydratorInterface
     */
    protected $hydrator;

    /**
     * @var \ChangeLog\Model\ChangeLogInterface
     */
    protected $changeLogPrototype;

    /**
     * @param AdapterInterface $dbAdapter
     * @param HydratorInterface $hydrator
     * @param ChangeLogInterface $changeLogPrototype
     */
    public function __construct(
        AdapterInterface $dbAdapter,
        HydratorInterface $hydrator,
        ChangeLogInterface $changeLogPrototype
    )
    {
        $this->dbAdapter            = $dbAdapter;
        $this->hydrator             = $hydrator;
        $this->changeLogPrototype   = $changeLogPrototype;
    }

    /**
     * @param int|string $id
     *
     * @return ChangeLogInterface
     * @throws \InvalidArgumentException
     */
    public function fetch($id)
    {
        $sql    = new Sql($this->dbAdapter);
         $select = $sql->select('cl_change_log');
         $select->where(array('id = ?' => $id));

         $stmt   = $sql->prepareStatementForSqlObject($select);
         $result = $stmt->execute();

         if ($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()) {
             return $this->hydrator->hydrate($result->current(), $this->changeLogPrototype);
         }

         throw new \InvalidArgumentException("Change Log with given ID:{$id} not found.");
     }

    /**
     * @return array|ChangeLogInterface[]
     */
    public function fetchAll($orderby = 'id', $order = 'asc')
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select('cl_change_log')->order("$orderby $order");

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new HydratingResultSet($this->hydrator, $this->changeLogPrototype);

          return $resultSet->initialize($result);
        }

        return [];
    }

    /**
     * Just need id, add_ts, description columns
     * @return array|ChangeLogInterface[]
     */
    public function fetchAllForBase3($orderby = 'id', $order = 'asc')
    {
        $sql = new Sql($this->dbAdapter);

        $select = $sql->select('cl_change_log')->columns(['id','add_ts','description'])->order("$orderby $order");

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new HydratingResultSet($this->hydrator, $this->changeLogPrototype);
            return $resultSet->initialize($result);
        }

        return [];
    }

    /**
     * @param ChangeLogInterface $changeLogObject
     *
     * @return ChangeLogInterface
     * @throws \Exception
     */
    public function save(ChangeLogInterface $changeLogObject)
    {
        $changeLogData = $this->hydrator->extract($changeLogObject);
          unset($changeLogData['id']);

          if ($changeLogObject->getId()) {
             $action = new Update('cl_change_log');
             $action->set($changeLogData);
             $action->where(array('id = ?' => $changeLogObject->getId()));
          } else {
             $action = new Insert('cl_change_log');
             $action->values($changeLogData);
          }

          $sql    = new Sql($this->dbAdapter);
          $stmt   = $sql->prepareStatementForSqlObject($action);
          $result = $stmt->execute();

          if ($result instanceof ResultInterface) {
             if ($newId = $result->getGeneratedValue()) {
                $changeLogObject->setId($newId);
             }

             return $changeLogObject;
          }

          throw new \Exception("Database error");
       }

    /**
     * {@inheritDoc}
     */
    public function delete(ChangeLogInterface $changeLogObject)
    {
        $action = new Delete('cl_change_log');
        $action->where(array('id = ?' => $changeLogObject->getId()));

        $sql    = new Sql($this->dbAdapter);
        $stmt   = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        return (bool)$result->getAffectedRows();
    }
}
