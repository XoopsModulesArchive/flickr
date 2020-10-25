<?php

// $Id: index.php,v 1.2 2005/06/02 20:25:48 eric_juden Exp $
require dirname(__DIR__, 3) . '/include/cp_header.php';
require_once __DIR__ . '/admin_header.php';
require_once XOOPS_ROOT_PATH . '/class/pagenav.php';

global $xoopsModule;
$module_id = $xoopsModule->getVar('mid');

$op = $_REQUEST['op'] ?? 'default';

switch ($op) {
    case 'about':
        about();
        break;
    default:
        flickr_default();
        break;
}

function about()
{
    global $oAdminButton;

    xoops_cp_header();

    echo $oAdminButton->renderButtons('about');

    require_once FLICKR_ADMIN_PATH . '/about.php';
}

function flickr_default()
{
    about();
}
