<?php
function get_gravatar_url($email, $size = 40)
{
    $default = sfConfig::get('gravatar_default_image');
    $default = image_path($default, true);
    $url = 'http://www.gravatar.com/avatar.php?gravatar_id=' . md5(strtolower($email)) . '&default=' . urlencode($default) . '&size=' . $size;
    return $url;
}
function gravatar($email, $size =40)
{
    image_tag(get_gravatar_url($email, $size =40));
}

function url_to_app($site, $path = null)
{
    $request = sfContext::getInstance()->getRequest();
    $env = sfConfig::get('sf_environment');
    $file = $env == 'dev' ? $site . '_dev.php' : ($site . '.php');
    return $request->getRelativeUrlRoot() . '/' . $file . ($path ? '/' . $path : null);
}
function include_page_title()
{
    $title = sfContext::getInstance()->getResponse()->getTitle();
    if (empty($title)) {
        $title = sfConfig::get('app_title_site_name');
    }
    if (sfConfig::get('app_title_show_bread', true)) {
        $sep = sfConfig::get('app_title_sep', '&gt;');
        $site_name_pos = sfConfig::get('app_title_site_name_pos', 'last');
        $path = array();
        foreach (get_breadscrumbs() as $breadscrumb)
        {
            $path[] = $breadscrumb['title'];
        }
        if ($site_name_pos == 'last') {
            array_push($path, $title);
        } else
        {
            array_unshift($path, $title);
        }
        $title = implode($sep, $path);
    }
    echo content_tag('title', $title) . "\n";
}

function add_breadscrumb($title, $url, $pos = 'last')
{
    if(empty($title))return false;
    $breadscrumbs = sfConfig::get('breadscrumbs', array());
    $breadscrumb = compact('title', 'url');
    if ($pos == 'last') {
        array_push($breadscrumbs, $breadscrumb);
    } else
    {
        array_unshift($breadscrumbs, $breadscrumb);
    }
    sfConfig::set('breadscrumbs', $breadscrumbs);
}

function get_breadscrumbs()
{
    return sfConfig::get('breadscrumbs', array());
}