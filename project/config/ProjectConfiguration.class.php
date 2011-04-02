<?php
require_once dirname(__file__) .'/../lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
	public function setup()
	{
        date_default_timezone_set('Asia/Chongqing');
		defined('DATE_MYSQL') || define('DATE_MYSQL','Y-m-d H:i:s');
        set_time_limit(0);
        ini_set('memory_limit', -1);
        if(isset($_SERVER['REQUEST_URI']))
        {
            $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $qs = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
            $_SERVER['REQUEST_URI'] =  rtrim($path, '/') . '?' . $qs;
        }
		$this->enablePlugins('sfDoctrinePlugin');
		$this->enablePlugins('sfDoctrineGuardPlugin');
		$this->enablePlugins('sfAdminDashPlugin', 'csSettingsPlugin');
		$this->enablePlugins('sfFormExtraPlugin', 'dcReloadedFormExtraPlugin', 'sfDatePickerTimePlugin');
		$this->enablePlugins('sfWebBrowserPlugin');
		$this->enablePlugins('fzTagPlugin');
		$this->enablePlugins('vjCommentPlugin');
		$this->enablePlugins('prestaSitemapPlugin', 'sfGoogleAnalyticsPlugin');
		$this->enablePlugins('csDoctrineActAsGeolocatablePlugin', 'csDoctrineActAsSortablePlugin');
		$this->enablePlugins('sfMinifyPlugin');
        if(sfConfig::get('sf_environment')=='dev')
        {
            $this->enablePlugins('sfFirePHPPlugin');
        }
	}
	public function setupPlugins()
	{
	}
	public function configureDoctrine(Doctrine_Manager $manager)
	{
		if(sfConfig::get('sf_enviroment')=='prod'){
			$servers = array(
                'host' => 'localhost', 'port' => 11211, 'persistent' => true
			);
			$cacheDriver = new Doctrine_Cache_Memcache(array(
                'servers' => $servers, 'compression' => false
			));
			$manager->setAttribute(Doctrine::ATTR_QUERY_CACHE, $cacheDriver);
			$manager->setAttribute(Doctrine::ATTR_RESULT_CACHE, $cacheDriver);
			$manager->setAttribute(Doctrine_Core::ATTR_QUERY_CACHE_LIFESPAN, 3600);
			$manager->setAttribute(Doctrine_Core::ATTR_RESULT_CACHE_LIFESPAN, 3600);
		}
		$manager->setAttribute(Doctrine_Core::ATTR_QUERY_CLASS, 'myDoctrineQuery');
		sfConfig::set('doctrine_model_builder_options', array('baseClassName' => 'myDoctrineRecord'));
	}
}
