<?php
class myDoctrineQuery extends Doctrine_Query
{
    function __construct(Doctrine_Connection $connection = null, Doctrine_Hydrator_Abstract $hydrator = null)
    {
        parent::__construct($connection, $hydrator);
        $this->_resultCache = $this->_conn->getAttribute(Doctrine_Core::ATTR_RESULT_CACHE);
        $this->_queryCache = $this->_conn->getAttribute(Doctrine_Core::ATTR_QUERY_CACHE);
        $this->_resultCacheTTL = $this->_conn->getAttribute(Doctrine_Core::ATTR_RESULT_CACHE_LIFESPAN);
        $this->_queryCacheTTL = $this->_conn->getAttribute(Doctrine_Core::ATTR_QUERY_CACHE_LIFESPAN);
    }
  public function preQuery()
  {
      if(sfConfig::get('sf_enviroment')=='prod')
      {
          if (Doctrine_Query::SELECT == $this->getType()) {
              $this->_conn = Doctrine_Manager::getInstance()->getConnection('slave');
          } else {
              $this->_conn = Doctrine_Manager::getInstance()->getConnection('master');
          }
      }
  }
}
?>