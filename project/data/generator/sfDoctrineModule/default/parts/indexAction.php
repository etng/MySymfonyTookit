  public function executeIndex(sfWebRequest $request)
  {
<?php if (isset($this->params['with_doctrine_route']) && $this->params['with_doctrine_route']): ?>
    $this-><?php echo $this->getPluralName() ?> = $this->getRoute()->getObjects();
<?php else: ?>
    $query =  Doctrine_Core::getTable('<?php echo $this->getModelClass() ?>')->createQuery('a');
    $this-><?php echo $this->getSingularName();?>_pager = new sfDoctrinePager('<?php echo $this->getModelClass() ?>', sfConfig::get('app_pager_<?php echo $this->getSingularName();?>_limit', 10));
    $this-><?php echo $this->getSingularName();?>_pager->setQuery($query);
    $this-><?php echo $this->getSingularName();?>_pager->setPage($request->getParameter(sfConfig::get('app_pager_<?php echo $this->getSingularName();?>_param', 'page'), 1));
    $this-><?php echo $this->getSingularName();?>_pager->init();
<?php endif; ?>
  }



