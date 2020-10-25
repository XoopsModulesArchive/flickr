<?php

// $Id: xoops_version.php,v 1.4 2005/06/03 16:47:27 eric_juden Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <https://www.xoops.org>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
$modversion['name'] = _MI_FLICKR_NAME;
$modversion['version'] = '0.1';
$modversion['description'] = _MI_FLICKR_DESC;
$modversion['credits'] = '3Dev Computers';
$modversion['author'] = 'Eric Juden';
$modversion['help'] = 'not yet';
$modversion['license'] = 'GPL see LICENSE';
$modversion['official'] = 1;
$modversion['image'] = 'images/flickr_logo.png';
$modversion['dirname'] = 'flickr';

// Extra stuff for about page
$modversion['release_date'] = '06/03/2005';
$modversion['version_info'] = 'Beta';
$modversion['creator'] = '3Dev';
$modversion['demo_site'] = 'htp://demo.3dev.org';
$modversion['official_site'] = 'http://www.3dev.org';
$modversion['bug_url'] = 'http://dev.xoops.org/modules/xfmod/tracker/?group_id=1246&atid=1161';
$modversion['feature_url'] = 'http://dev.xoops.org/modules/xfmod/tracker/?group_id=1246&atid=1164';
$modversion['questions_email'] = 'eric@3dev.org';

// Developers
$modversion['contributors']['developers'][0]['name'] = 'Eric Juden';
$modversion['contributors']['developers'][0]['uname'] = 'eric_juden';
$modversion['contributors']['developers'][0]['email'] = 'eric@3dev.org';
$modversion['contributors']['developers'][0]['website'] = 'http://www.3dev.org';

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';

// Templates
$modversion['templates'][1]['file'] = 'flickr_index.html';
$modversion['templates'][1]['description'] = _MI_FLICKR_TEMP_INDEX;
$modversion['templates'][2]['file'] = 'flickr_header.html';
$modversion['templates'][2]['description'] = _MI_FLICKR_TEMP_HEADER;
$modversion['templates'][3]['file'] = 'flickr_contacts.html';
$modversion['templates'][3]['description'] = _MI_FLICKR_TEMP_CONTACTS;
$modversion['templates'][4]['file'] = 'flickr_everyone.html';
$modversion['templates'][4]['description'] = _MI_FLICKR_TEMP_EVERYONE;
$modversion['templates'][5]['file'] = 'flickr_photo.html';
$modversion['templates'][5]['description'] = _MI_FLICKR_TEMP_PHOTO;

// Menu
$modversion['hasMain'] = 1;

// Search
$modversion['hasSearch'] = 0;

// On Install
//$modversion['onInstall'] = "install.php";

// Config
$modversion['config'][1]['name'] = 'flickr_email';
$modversion['config'][1]['title'] = '_MI_FLICKR_EMAIL';
$modversion['config'][1]['description'] = '_MI_FLICKR_EMAIL_DSC';
$modversion['config'][1]['formtype'] = 'textbox';
$modversion['config'][1]['valuetype'] = 'string';
$modversion['config'][1]['default'] = '';

$modversion['config'][2]['name'] = 'flickr_userID';
$modversion['config'][2]['title'] = '_MI_FLICKR_USERID';
$modversion['config'][2]['description'] = '_MI_FLICKR_USERID_DSC';
$modversion['config'][2]['formtype'] = 'textbox';
$modversion['config'][2]['valuetype'] = 'string';
$modversion['config'][2]['default'] = '';

$modversion['config'][3]['name'] = 'flickr_name';
$modversion['config'][3]['title'] = '_MI_FLICKR_FNAME';
$modversion['config'][3]['description'] = '_MI_FLICKR_FNAME_DSC';
$modversion['config'][3]['formtype'] = 'textbox';
$modversion['config'][3]['valuetype'] = 'string';
$modversion['config'][3]['default'] = '';

$modversion['config'][4]['name'] = 'flickr_password';
$modversion['config'][4]['title'] = '_MI_FLICKR_PASSWORD';
$modversion['config'][4]['description'] = '_MI_FLICKR_PASSWORD_DSC';
$modversion['config'][4]['formtype'] = 'textbox';
$modversion['config'][4]['valuetype'] = 'string';
$modversion['config'][4]['default'] = '';

$modversion['config'][5]['name'] = 'flickr_apiKey';
$modversion['config'][5]['title'] = '_MI_FLICKR_API_KEY';
$modversion['config'][5]['description'] = '_MI_FLICKR_API_KEY_DSC';
$modversion['config'][5]['formtype'] = 'textbox';
$modversion['config'][5]['valuetype'] = 'string';
$modversion['config'][5]['default'] = '';

// Blocks

// Display recently uploaded friend & family pictures
// Display my recently uploaded pics
// Display <ul> of sets
// Display <ul> of groups

/*
// Block displays random photos I have uploaded
$modversion['blocks'][1]['file'] = "flickr_blocks.php";
$modversion['blocks'][1]['name'] = _MI_FLICKR_BLOCK_RANDOM;
$modversion['blocks'][1]['description'] = _MI_FLICKR_BLOCK_RANDOM_DSC;
$modversion['blocks'][1]['show_func'] = "b_flickr_random_show";
$modversion['blocks'][1]['template'] = 'flickr_block_random.html';
*/
