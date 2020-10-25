<?php

// $Id: everyone.php,v 1.3 2005/06/03 15:53:41 eric_juden Exp $
require_once __DIR__ . '/header.php';

$photo_id = $_GET['photo_id'] ?? 0;

$GLOBALS['xoopsOption']['template_main'] = 'flickr_everyone.html';   // Set template
require XOOPS_ROOT_PATH . '/header.php';                     // Include the page header

// Create instance of Flickr API
$api = new Flickr_API(['api_key' => $aFlickrCfg['apiKey']]);

// Get contacts' photos
if ($photos = $_flickrSession->get('flickr_everyonePhotos')) {
    if ($photo_id == 0) {
        $photos =& flickrGetEveryoneRecentPhotos($api, $aFlickrCfg, 'owner_name', 10, 2);
    }
} else {
    $photos =& flickrGetEveryoneRecentPhotos($api, $aFlickrCfg, 'owner_name', 10, 2);
}

$photo_count = count($photos);
if ($photo_count > 0) {
    $_flickrSession->set('flickr_everyonePhotos', $photos);
}
$active_photo =& flickr_getActivePhoto($photos, $photo_id);

$xoopsTpl->assign('flickr_photoCount', $photo_count);
$xoopsTpl->assign('flickr_photos', $photos);
$xoopsTpl->assign('flickr_url', FLICKR_BASE_URL);
$xoopsTpl->assign('xoops_module_header', $flickr_module_header);
$xoopsTpl->assign('flickr_activePhoto', $active_photo);

require XOOPS_ROOT_PATH . '/footer.php';


