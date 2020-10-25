<?php

require dirname(__DIR__, 2) . '/mainfile.php';

if (!defined('FLICKR_CONSTANTS_INCLUDED')) {
    require_once XOOPS_ROOT_PATH . '/modules/flickr/include/constants.php';
}

require_once FLICKR_CLASS_PATH . '/flickrSession.php';
require_once FLICKR_INCLUDE_PATH . '/functions.php';
require_once FLICKR_PEAR_PATH . '/Flickr/API.php';
require_once FLICKR_CLASS_PATH . '/phpFlickr.php';

$_flickrSession = new flickrSession();

$aFlickrCfg = [
    'apiKey' => $xoopsModuleConfig['flickr_apiKey'],
'email' => $xoopsModuleConfig['flickr_email'],
'user_id' => $xoopsModuleConfig['flickr_userID'],
'user_name' => $xoopsModuleConfig['flickr_name'],
'password' => $xoopsModuleConfig['flickr_password'],
];

$flickrCss = FLICKR_STYLE_URL . '/flickr.css';
$flickr_module_header = '<link rel="stylesheet" type="text/css" media="all" href="' . $flickrCss . '"><!--[if gte IE 5.5000]><script src="iepngfix.js" language="JavaScript" type="text/javascript"></script><![endif]-->';
