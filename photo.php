<?php

// $Id: photo.php,v 1.3 2005/06/03 15:53:41 eric_juden Exp $
require_once __DIR__ . '/header.php';

if (isset($_REQUEST['id'])) {
    $photo_id = $_REQUEST['id'];
} else {
    redirect_header(FLICKR_BASE_URL, 3, _FLICKR_MSG_NO_PHOTO);
}

if (isset($_REQUEST['size']) && '' != $_REQUEST['size']) {
    $photo_size = $_REQUEST['size'];
} else {
    $photo_size = 'Small';
}

$GLOBALS['xoopsOption']['template_main'] = 'flickr_photo.html';   // Set template
require XOOPS_ROOT_PATH . '/header.php';                     // Include the page header

$f = new phpFlickr($aFlickrCfg['apiKey'], $aFlickrCfg['email'], $aFlickrCfg['password']);
$photo = $f->photos_getInfo($photo_id);
$sizes = $f->photos_getSizes($photo_id);

$aSizes = [];
foreach ($sizes as $name => $size) {
    $aSizes[$name] = [
        'name' => $name,
'source' => $size['_attributes']['source'],
    ];
}

$xoopsTpl->assign('flickr_url', FLICKR_BASE_URL);
$xoopsTpl->assign('xoops_module_header', $flickr_module_header);
$xoopsTpl->assign('flickr_photoSizes', $aSizes);
$xoopsTpl->assign('flickr_photo', $photo);
$xoopsTpl->assign('flickr_defaultSize', $aSizes[$photo_size]['source']);

require XOOPS_ROOT_PATH . '/footer.php';
