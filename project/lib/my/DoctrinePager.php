<?php
class myDoctrinePager extends sfDoctrinePager
{
    /**
     * getCountQuery
     *
     * Returns the Doctrine_Query object that is used to make the count results to pager
     *
     * @return Doctrine_Query     Doctrine_Query object related to the pager
     */
    public function getCountQuery()
    {
        if($this->_countQuery == null)
        {
            $query = clone $this->getQuery();
            $query
              ->offset(0)
              ->limit(0)
            ;
            $this->_countQuery  = $query;
            die('not so good');
        }
        return $this->_countQuery;
    }

    /**
     * setCountQuery
     *
     * Defines the counter query to be used by pager
     *
     * @param Doctrine_Query  Accepts either a Doctrine_Query object or a string
     *                        (which does the Doctrine_Query class creation).
     * @param array           Optional params to be used by counter Doctrine_Query.
     *                        If not defined, the params passed to execute method will be used.
     * @return void
     */
    public function setCountQuery($query, $params = null)
    {
        if (is_string($query)) {
            $query = Doctrine_Query::create()
                ->parseDqlQuery($query);
        }

        $this->_countQuery = $query;

        $this->setCountQueryParams($params);

    }

    /**
     * getCountQueryParams
     *
     * Returns the params to be used by counter Doctrine_Query
     *
     * @return array     Doctrine_Query counter params
     */
    public function getCountQueryParams($defaultParams = array())
    {
        return ($this->_countQueryParams !== null) ? $this->_countQueryParams : $defaultParams;
    }

    /**
     * setCountQueryParams
     *
     * Defines the params to be used by counter Doctrine_Query
     *
     * @param array       Optional params to be used by counter Doctrine_Query.
     *                    If not defined, the params passed to execute method will be used.
     * @param boolean     Optional argument that append the query param instead of overriding the existent ones.
     * @return void
     */
    public function setCountQueryParams($params = array(), $append = false)
    {
        if ($append && is_array($this->_countQueryParams)) {
            $this->_countQueryParams = array_merge($this->_countQueryParams, $params);
        } else {
            if ($params !== null && !is_array($params)) {
                $params = array($params);
            }

            $this->_countQueryParams = $params;
        }
    }
}