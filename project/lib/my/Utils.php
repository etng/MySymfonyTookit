<?php

class myUtils
{
    public static function UserId()
    {
        $user_id = 0;
        try
        {
            $user = sfContext::getInstance()->getUser();
            if ($user instanceof sfGuardSecurityUser && $user->isAuthenticated()) {
                $user_id = $user->getGuardUser()->id;
            }
        } catch (Exception $e) {
            //
        }
        return $user_id;
    }
    public static function getCache()
    {
        $context = sfContext::getInstance();
        if(!$context->has('cache'))
        {
            $options = array('lifetime'=> 86400);
            foreach(array(
                'sfXCacheCache',
                'sfAPCCache',
                'sfMemcacheCache',
                'sfFileCache'
            ) as $cls)
            {
                try
                {
                    $cache = new $cls(array_merge($options, (array)sfConfig::get('app_cache_' . $cls)));
                }catch(sfInitializationException $e)
                {
                    echo $e;
                }
                if($cache)break;
            }
            $context->set('cache', $cache);
        }
        return $context->get('cache');
    }
    /**
     * Log a message.
     *
     * @param   mixed   $subject
     * @param   string  $message
     * @param   string  $priority
    */
      static public function logMessage($subject, $message, $priority = 'info')
      {
        if (class_exists('ProjectConfiguration', false))
        {
          ProjectConfiguration::getActive()->getEventDispatcher()->notify(new sfEvent($subject, 'application.log', array($message, 'priority' => constant('sfLogger::'.strtoupper($priority)))));
        }
        else
        {
          $message = sprintf('{%s} %s', is_object($subject) ? get_class($subject) : $subject, $message);
          sfContext::getInstance()->getLogger()->log($message, constant('SF_LOG_'.strtoupper($priority)));
        }
      }
    public static function googleTranslate($sentence, $source='zh-CN', $dest='en')
    {
        $url = "http://ajax.googleapis.com/ajax/services/language/translate?v=1.0&q=" . urlencode($sentence) . "&langpair=" . $source . "%7C" . $dest;
        $data = self::urlGet($url);
        if(($json = json_decode($body)) && ($json->responseStatus == 200))
        {
            return $json->responseData->translatedText;
        }
        return false;
    }
  public static function urlize($sentence)
  {
    $translated = self::google_translate($sentence);
    return Doctrine_Inflector::urlize($translated?$translated:$sentence);
  }
  public static function slugUrl($url)
  {
      $slug = parse_url($url, PHP_URL_HOST);
      if(strpos($slug, 'www.')===0)
      {
         $slug = substr($slug, strlen('www.'));
      }
      $slug = preg_replace('/(.*?)\.(cn|com)$/im', '\1', $slug);
      return str_replace('.', '-', $slug);
  }

    public static function getCityWeather($city)
    {
        static $cities;
        if(!$cities)
        {
            $cities = include(sfConfig::get('sf_data_dir').'/weather_cities.php');
        }
        if($city_id = array_search($city, $cities))
        {
            $url = sprintf('http://m.weather.com.cn/data/%s.html', $city_id);
             $content =self::urlGet($url);
             if($data = json_decode($content, true))
            {
                return $data['weatherinfo'];
            }
        }
        return false;
    }
    public static function urlGet($url, $referer='')
    {
        $handle = curl_init();
        curl_setopt_array($handle, array(
                                        CURLOPT_USERAGENT => myUtils::ua(),
                                        CURLOPT_FOLLOWLOCATION => true,
                                        CURLOPT_HEADER => false,
                                        CURLOPT_HTTPGET => true,
                                        CURLOPT_RETURNTRANSFER => true,
                                        CURLOPT_TIMEOUT => 30,
                                        CURLOPT_URL => $url
                                   ));
        if ($referer) {
            curl_setopt($handle, CURLOPT_REFERER, $referer);
        }
        $source = curl_exec($handle);
        curl_close($handle);
        return $source;
    }
}
