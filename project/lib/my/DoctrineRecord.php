<?php
abstract class myDoctrineRecord extends sfDoctrineRecord
{
    /* All save method are master,write
     * @see Doctrine_Record::save()
     */
    public function save(Doctrine_Connection $conn = null)
    {
	  if(sfConfig::get('sf_enviroment')=='prod')
        {
            $conn = Doctrine_Manager::getInstance()->getConnection('master');
        }
        /**
         *   设置编辑姓名
         */
        if($this->contains('editor_id'))
        {
            $this->editor_id = myUtils::UserId();
        }
        parent::save($conn);
    }
    public function readCache($name, $default=null)
    {
        $key = get_class($this) .'/'. $this->id .'_'. $name;
        if($data = myUtils::getCache()->get($key))
        {
            return unserialize($data);
        }
        return $default;
    }
    public function writeCache($name, $data, $lifetime=0)
    {
        $key = get_class($this) .'/'. $this->id .'_'. $name;
        myUtils::getCache()->set($key, serialize($data), $lifetime?$lifetime:86400);
    }
}