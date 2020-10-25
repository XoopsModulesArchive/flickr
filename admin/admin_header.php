<?php

if (!defined('FLICKR_CONSTANTS_INCLUDED')) {
    require_once XOOPS_ROOT_PATH . '/modules/flickr/include/constants.php';
}

require FLICKR_BASE_PATH . '/admin/admin_buttons.php';
require_once FLICKR_INCLUDE_PATH . '/functions.php';

if (file_exists(FLICKR_BASE_PATH . '/language/' . $xoopsConfig['language'] . '/main.php')) {
    include FLICKR_BASE_PATH . '/language/' . $xoopsConfig['language'] . '/main.php';
} else {
    include FLICKR_BASE_PATH . '/language/english/main.php';
}

if (file_exists(FLICKR_BASE_PATH . '/language/' . $xoopsConfig['language'] . '/modinfo.php')) {
    include FLICKR_BASE_PATH . '/language/' . $xoopsConfig['language'] . '/modinfo.php';
} else {
    include FLICKR_BASE_PATH . '/language/english/modinfo.php';
}

global $xoopsModule;
$module_id = $xoopsModule->getVar('mid');
$oAdminButton = new AdminButtons();
$oAdminButton->AddTitle(sprintf(_AM_FLICKR_ADMIN_TITLE, $xoopsModule->getVar('name')));
$oAdminButton->AddButton(_AM_FLICKR_ABOUT, FLICKR_ADMIN_URL . '/index.php?op=about', 'about');
//$oAdminButton->AddButton(_AM_FLICKR_INDEX, FLICKR_ADMIN_URL."/index.php", 'index');
$oAdminButton->AddTopLink(_AM_FLICKR_PREFERENCES, XOOPS_URL . '/modules/system/admin.php?fct=preferences&amp;op=showmod&amp;mod=' . $module_id);
//$oAdminButton->AddTopLink(_AM_FLICKR_BLOCK_TEXT, FLICKR_ADMIN_URL."/index.php?op=blocks");
$oAdminButton->addTopLink(_AM_FLICKR_UPDATE_MODULE, XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin&amp;op=update&amp;module=flickr');
//$oAdminButton->addTopLink(_MI_FLICKR_MENU_CHECK_TABLES, FLICKR_ADMIN_URL."/upgrade.php?op=checkTables");
$oAdminButton->AddTopLink(_AM_FLICKR_GOTOMODULE, FLICKR_BASE_URL . '/index.php');

$myts = MyTextSanitizer::getInstance();

$imagearray = [
    'editimg' => "<img src='" . FLICKR_IMAGE_URL . "/button_edit.png' alt='" . _AM_FLICKR_ICO_EDIT . "' title='" . _AM_FLICKR_ICO_EDIT . "' align='middle'>",
'deleteimg' => "<img src='" . FLICKR_IMAGE_URL . "/button_delete.png' alt='" . _AM_FLICKR_ICO_DELETE . "' title='" . _AM_FLICKR_ICO_DELETE . "' align='middle'>",
'online' => "<img src='" . FLICKR_IMAGE_URL . "/on.png' alt='" . _AM_FLICKR_ICO_ONLINE . "' title='" . _AM_FLICKR_ICO_ONLINE . "' align='middle'>",
'offline' => "<img src='" . FLICKR_IMAGE_URL . "/off.png' alt='" . _AM_FLICKR_ICO_OFFLINE . "' title='" . _AM_FLICKR_ICO_OFFLINE . "' align='middle'>",
];
