<?php
namespace ChangeLog\Model;

interface ChangeLogInterface
{
    /**
     * Will retrun the Id of the change log
     *
     * @return int
     */
    public function getId();

    /**
     * Return the description
     *
     * @return string
     */
    public function getDescription();

    /**
     * Return the a_user_id
     *
     * @return int
     */
    public function getAUserId();

    /**
     * Return add_id for the create by user id
     *
     * @return int
     */
    public function getAddId();

    /**
     * Return add_ts for the create date stamp
     *
     * @return timestamp
     */
    public function getAddTs();

    /**
    * Return mod_id for the create by user id
    *
    * @return int
    */
    public function getModId();

    /**
     * Return mod_ts for the create date stamp
     *
     * @return timestamp
     */
    public function getModTs();

    /**
     * Return stat_id for the status id
     *
     * @return int
     */
    public function getStatId();

}
