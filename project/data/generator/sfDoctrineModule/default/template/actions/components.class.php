[?php

/**
 * <?php echo $this->getModuleName() ?> components.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage <?php echo $this->getModuleName()."\n" ?>
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id:$
 */
class <?php echo $this->getGeneratedModuleName() ?>Components extends sfComponents
{
  public function executeList(sfWebRequest $request)
  {
    $query =  Doctrine_Core::getTable('<?php echo $this->getModelClass() ?>')->createQuery('a');
    $alias = $query->getRootAlias();
    if(empty($this->filters))
    {
        $this->filters = array();
    }
    foreach($this->filters as $field=>$param)
    {
        $query->andWhere(sprintf('%s=?', strpos($field, '.')?$field:"{$alias}.{$field}"), $param);
    }
    if(!empty($this->orderBy))
    {
        $this->order_by = strpos($this->orderBy, '.')?$this->orderBy:"{$alias}.{$this->orderBy}";
        $this->order_way = strtoupper(@$this->orderWay);
        if(!in_array($this->order_way, array('ASC', 'DESC')))
        {
          $this->order_way = 'DESC';
        }
        $query->addOrderBy($this->order_by .' ' . $this->order_way);
    }
    if(!($this->limit = intval(@$this->limit)))
    {
      $this->limit =10;
    }
    //$this->title = empty($this->title)?'<?php echo sfInflector::humanize($this->getPluralName()) ?> List':$this->title;
    $query->limit($this->limit);
    $this-><?php echo $this->getPluralName();?> = $query->execute();
  }
}