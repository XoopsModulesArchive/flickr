<?php

// $Id: flickrBaseObjectHandler.php,v 1.2 2005/06/02 20:25:48 eric_juden Exp $

/**
 * flickrBaseObjectHandler class
 *
 * @author  Nazar Aziz <nazar@panthersoftware.com>
 */
class flickrBaseObjectHandler extends XoopsObjectHandler
{
    /**
     * Database connection
     *
     * @var    object
     */

    public $_db;

    /**
     * Autoincrementing DB fieldname
     * @var string
     */

    public $_idfield = 'id';

    /**
     * Constructor
     *
     * @param object $db reference to a xoopsDB object
     */
    public function init($db)
    {
        $this->_db = $db;
    }

    /**
     * create a new  object
     * @return object {@link flickrBaseObject}
     */
    public function &create()
    {
        return new $this->classname();
    }

    /**
     * retrieve an object from the database, based on. use in child classes
     * @param int $id ID
     * @return mixed object if id exists, false if not
     */
    public function get($id)
    {
        $id = (int)$id;

        if ($id > 0) {
            $sql = $this->_selectQuery(new Criteria($this->_idfield, $id));

            if (!$result = $this->_db->query($sql)) {
                return false;
            }

            $numrows = $this->_db->getRowsNum($result);

            if (1 == $numrows) {
                $obj = new $this->classname($this->_db->fetchArray($result));

                return $obj;
            }
        }

        return false;
    }

    /**
     * retrieve objects from the database
     *
     * @param null $criteria  {@link CriteriaElement} conditions to be met
     * @param bool $id_as_key Should the department ID be used as array key
     * @return array array of objects
     */
    public function &getObjects($criteria = null, $id_as_key = false)
    {
        $ret = [];

        $limit = $start = 0;

        $sql = $this->_selectQuery($criteria);

        $id = $this->_idfield;

        if (isset($criteria)) {
            $limit = $criteria->getLimit();

            $start = $criteria->getStart();
        }

        $result = $this->_db->query($sql, $limit, $start);

        // If no records from db, return empty array

        if (!$result) {
            return $ret;
        }

        // Add each returned record to the result array

        while (false !== ($myrow = $this->_db->fetchArray($result))) {
            $obj = new $this->classname($myrow);

            if (!$id_as_key) {
                $ret[] = &$obj;
            } else {
                $ret[$obj->getVar($id)] = &$obj;
            }

            unset($obj);
        }

        return $ret;
    }

    public function insert(XoopsObject $obj, $force = false)
    {
        // Make sure object is of correct type

        if (0 != strcasecmp($this->classname, get_class($obj))) {
            return false;
        }

        // Make sure object needs to be stored in DB

        if (!$obj->isDirty()) {
            return true;
        }

        // Make sure object fields are filled with valid values

        if (!$obj->cleanVars()) {
            return false;
        }

        // Create query for DB update

        if ($obj->isNew()) {
            // Determine next auto-gen ID for table

            $id = $this->_db->genId($this->_db->prefix($this->_dbtable) . '_uid_seq');

            $sql = $this->_insertQuery($obj);
        } else {
            $sql = $this->_updateQuery($obj);
        }

        // Update DB

        if (false !== $force) {
            $result = $this->_db->queryF($sql);
        } else {
            $result = $this->_db->query($sql);
        }

        if (!$result) {
            return false;
        }

        //Make sure auto-gen ID is stored correctly in object

        if ($obj->isNew()) {
            $obj->assignVar($this->_idfield, $this->_db->getInsertId());
        }

        return true;
    }

    /**
     * Create a "select" SQL query
     * @param null $criteria {@link CriteriaElement} to match
     * @return string SQL query
     */
    public function _selectQuery($criteria = null)
    {
        $sql = sprintf('SELECT * FROM %s', $this->_db->prefix($this->_dbtable));

        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();

            if ('' != $criteria->getSort()) {
                $sql .= ' ORDER BY ' . $criteria->getSort() . '
                    ' . $criteria->getOrder();
            }
        }

        return $sql;
    }

    /**
     * count objects matching a criteria
     *
     * @param null $criteria {@link CriteriaElement} to match
     * @return int count of objects
     */
    public function getCount($criteria = null)
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->_db->prefix($this->_dbtable);

        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }

        if (!$result = &$this->_db->query($sql)) {
            return 0;
        }

        [$count] = $this->_db->fetchRow($result);

        return $count;
    }

    /**
     * delete object based on id
     *
     * @param \XoopsObject $obj   {@link XoopsObject} to delete
     * @param bool         $force override XOOPS delete protection
     * @return bool deletion successful?
     */
    public function delete(XoopsObject $obj, $force = false)
    {
        if (0 != strcasecmp($this->classname, get_class($obj))) {
            return false;
        }

        $sql = $this->_deleteQuery($obj);

        if (false !== $force) {
            $result = $this->_db->queryF($sql);
        } else {
            $result = $this->_db->query($sql);
        }

        if (!$result) {
            return false;
        }

        return true;
    }

    /**
     * delete department matching a set of conditions
     *
     * @param null $criteria {@link CriteriaElement}
     * @return bool FALSE if deletion failed
     */
    public function deleteAll($criteria = null)
    {
        $sql = 'DELETE FROM ' . $this->_db->prefix($this->_dbtable);

        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }

        if (!$result = $this->_db->query($sql)) {
            return false;
        }

        return true;
    }

    /**
     * Assign a value to 1 field for tickets matching a set of conditions
     *
     * @param mixed $fieldname
     * @param mixed $fieldvalue
     * @param null  $criteria {@link CriteriaElement}
     * @return bool FALSE if update failed
     */
    public function updateAll($fieldname, $fieldvalue, $criteria = null)
    {
        $set_clause = is_numeric($fieldvalue) ? $fieldname . ' = ' . $fieldvalue : $fieldname . ' = ' . $this->_db->quoteString($fieldvalue);

        $sql = 'UPDATE ' . $this->_db->prefix($this->_dbtable) . ' SET ' . $set_clause;

        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }

        if (!$result = $this->_db->query($sql)) {
            return false;
        }

        return true;
    }

    public function _insertQuery(&$obj)
    {
        return false;
    }

    public function _updateQuery(&$obj)
    {
        return false;
    }

    public function _deleteQuery(&$obj)
    {
        return false;
    }

    /**
     * Singleton - prevent multiple instances of this class
     *
     * @param object &$db {@link XoopsHandlerFactory}
     * @return object {@link pagesCategoryHandler}
     */
    public function &getInstance($db)
    {
        static $instance;

        if (!isset($instance)) {
            $classname = $this->classname . 'Handler';

            $instance = new $classname($db);
        }

        return $instance;
    }
}
