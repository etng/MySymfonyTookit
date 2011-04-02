<h1><?php echo sfInflector::humanize($this->getPluralName()) ?> List</h1>
 <?php if (isset($this->params['route_prefix']) && $this->params['route_prefix']): ?>
[?php include_partial('<?php echo $this->getModuleName() ?>/list', array('<?php echo $this->getPluralName() ?>'=>$<?php echo $this->getPluralName() ?>))?]
  <a href="[?php echo url_for('<?php echo $this->getUrlForAction('new') ?>') ?]">New</a>
<?php else: ?>
[?php include_partial('<?php echo $this->getModuleName() ?>/list', array('<?php echo $this->getPluralName() ?>'=>$<?php echo $this->getSingularName() ?>_pager->getResults()))?]
  <div class="pager">
    <div class="fleft">[?php include_pager_header($<?php echo $this->getSingularName() ?>_pager);?]</div>
    <div class="fright">[?php include_pager_navigation($<?php echo $this->getSingularName() ?>_pager, url_for('<?php echo $this->getModuleName() ?>/index'));?]</div>
  </div>
  <a href="[?php echo url_for('<?php echo $this->getModuleName() ?>/new') ?]">New</a>
<?php endif; ?>
