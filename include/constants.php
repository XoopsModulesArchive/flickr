<?php

//$Id: constants.php,v 1.2 2005/06/02 20:25:48 eric_juden Exp $

/**
 *Global Application Constants
 */
define('FLICKR_DIR_NAME', 'flickr');

//Application Folders
define('FLICKR_BASE_PATH', XOOPS_ROOT_PATH . '/modules/' . FLICKR_DIR_NAME);
define('FLICKR_CLASS_PATH', FLICKR_BASE_PATH . '/class');
define('FLICKR_BASE_URL', XOOPS_URL . '/modules/' . FLICKR_DIR_NAME);
define('FLICKR_UPLOAD_PATH', XOOPS_ROOT_PATH . '/uploads/' . FLICKR_DIR_NAME);
define('FLICKR_INCLUDE_PATH', FLICKR_BASE_PATH . '/include');
define('FLICKR_IMAGE_PATH', FLICKR_BASE_PATH . '/images');
define('FLICKR_IMAGE_URL', FLICKR_BASE_URL . '/images');
define('FLICKR_ADMIN_URL', FLICKR_BASE_URL . '/admin');
define('FLICKR_ADMIN_PATH', FLICKR_BASE_PATH . '/admin');
define('FLICKR_PEAR_PATH', FLICKR_CLASS_PATH . '/pear');
define('FLICKR_STYLE_URL', FLICKR_BASE_URL . '/styles');

// Service urls
define('FLICKR_SERVICES_URL', 'http://www.flickr.com/services');
define('FLICKR_REST_URL', FLICKR_SERVICES_URL . '/rest');
define('FLICKR_METHOD_URL', FLICKR_REST_URL . '/?');

define('FLICKR_CONSTANTS_INCLUDED', 1);
