<?php
// $Id: functions.php,v 1.4 2005/06/06 20:50:27 eric_juden Exp $

/*
 * Strictly used for printing out an array/object to the screen
 *
 */
function flickrDebug($arr)
{
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
}

function &flickrGetHandler($handler)
{
    $handler = xoops_getModuleHandler($handler, FLICKR_DIR_NAME);
    return $handler;
}

function flickr_adminFooter()
{
    echo "<br><center><a target='_BLANK' href='http://www.3dev.org'><img src='" . FLICKR_IMAGE_URL . "/3Dev_flickr.png'></a></center>";
}

function flickrGetUserContacts($api, $aFlickrCfg)
{
    // get list of contacts
    $response = $api->callMethod('flickr.contacts.getList', ['email' => $aFlickrCfg['email'], 'password' => $aFlickrCfg['password']]);

    // check the response
    $hasResponse = flickrCheckResponse($api, $response);

    $contacts = [];
    if ($hasResponse === true) {
        $contacts =& flickrParseContacts($response, $aFlickrCfg);
    }
    return $contacts;
}

function &flickrParseContacts($response, $aFlickrCfg)
{
    $aContacts = [];
    $contacts  = $response->children[0]->children;
    foreach ($contacts as $contact) {
        $contactAttribs                     = $contact->attributes;
        $galleryUrl                         = flickrGetUserGalleryUrl($contactAttribs['nsid'], $aFlickrCfg['apiKey']);
        $profileUrl                         = flickrGetUserProfileUrl($contactAttribs['nsid'], $aFlickrCfg['apiKey']);
        $aContacts[$contactAttribs['nsid']] = [
            'username'   => $contactAttribs['username'],
            'realname'   => $contactAttribs['realname'],
            'friend'     => $contactAttribs['friend'],
            'family'     => $contactAttribs['family'],
            'ignored'    => $contactAttribs['ignored'],
            'galleryUrl' => $galleryUrl,
            'profileUrl' => $profileUrl,
        ];
    }
    return $aContacts;
}

function flickrCheckResponse($api, $response)
{
    if (!$response) {
        $code    = $api->getErrorCode();
        $message = $api->getErrorMessage();
        return "$code - $message";
    } else {
        return true;
    }
}

function flickrGetUserGalleryUrl($user_id, $apiKey)
{
    $api         = new Flickr_API(['api_key' => $apiKey]);
    $response    = $api->callMethod('flickr.urls.getUserPhotos', ['user_id' => $user_id]);
    $hasResponse = flickrCheckResponse($api, $response);
    if ($hasResponse === true) {
        return $response->children[0]->attributes['url'];
    } else {
        return '';
    }
}

function flickrGetUserProfileUrl($user_id, $apiKey)
{
    $api         = new Flickr_API(['api_key' => $apiKey]);
    $response    = $api->callMethod('flickr.urls.getUserProfile', ['user_id' => $user_id]);
    $hasResponse = flickrCheckResponse($api, $response);
    if ($hasResponse === true) {
        return $response->children[0]->attributes['url'];
    } else {
        return '';
    }
}

function &flickrGetUserPhotos($api, $aFlickrCfg, $count = 10, $just_friends = 0, $single_photo = 0, $include_self = 0)
{
    $count        = (int)$count;    // Only returns 6 - count seems kind of pointless
    $just_friends = (int)$just_friends;
    $single_photo = (int)$single_photo;
    $include_self = (int)$include_self;

    $response =& $api->callMethod(
        'flickr.photos.getContactsPublicPhotos',
        [
            'email'        => $aFlickrCfg['email'],
            'password'     => $aFlickrCfg['password'],
            'user_id'      => $aFlickrCfg['user_id'],
            'count'        => $count,
            'just_friends' => $just_friends,
            'single_photo' => $single_photo,
            'include_self' => $include_self,
        ]
    );

    $hasResponse = flickrCheckResponse($api, $response);
    $photos      = [];
    if ($hasResponse === true) {
        $photos =& flickrParsePhotos($response, $aFlickrCfg);
    }
    return $photos;
}

function &flickrGetMyPhotos($api, $aFlickrCfg, $extras = 'owner_name', $per_page = 10, $page = 1)
{
    $per_page = (int)$per_page;
    $page     = (int)$page;

    $response =& $api->callMethod(
        'flickr.people.getPublicPhotos',
        [
            'email'    => $aFlickrCfg['email'],
            'password' => $aFlickrCfg['password'],
            'user_id'  => $aFlickrCfg['user_id'],
            'extras'   => $extras,
        ]
    );

    $hasResponse = flickrCheckResponse($api, $response);
    $photos      = [];
    if ($hasResponse === true) {
        $photos =& flickrParsePhotos($response, $aFlickrCfg);
    }
    return $photos;
}

function &flickrGetEveryoneRecentPhotos($api, $aFlickrCfg, $extras = 'owner_name', $per_page = 10, $page = 1)
{
    $per_page = (int)$per_page;
    $page     = (int)$page;

    $response =& $api->callMethod(
        'flickr.photos.getRecent',
        [
            'email'    => $aFlickrCfg['email'],
            'password' => $aFlickrCfg['password'],
            'extras'   => $extras,
            'per_page' => $per_page,
            'page'     => $page,
        ]
    );

    $hasResponse = flickrCheckResponse($api, $response);
    $photos      = [];
    if ($hasResponse === true) {
        $photos =& flickrParsePhotos($response, $aFlickrCfg);
    }
    return $photos;
}

function &flickrParsePhotos($response, $aFlickrCfg)
{
    $aPhotos = [];
    $photos  = $response->children[0]->children;

    if (count($photos) > 0) {
        foreach ($photos as $photo) {
            $photoAttribs = $photo->attributes;
            $aPhotoUrls   = flickrGetPhotoUrl($photoAttribs['id'], $aFlickrCfg);
            $description  = flickrGetPhotoDescription($photoAttribs['id'], $aFlickrCfg, $photoAttribs['secret']);
            $aPhotos[]    = [
                'id'          => $photoAttribs['id'],
                'secret'      => $photoAttribs['secret'],
                'server'      => $photoAttribs['server'],
                'owner'       => $photoAttribs['owner'],
                'username'    => $photoAttribs['username'] ?? '',
                'title'       => $photoAttribs['title'],
                'square'      => (isset($aPhotoUrls['Square'])) ? $aPhotoUrls['Square']['source'] : '',
                'thumbnail'   => (isset($aPhotoUrls['Thumbnail'])) ? $aPhotoUrls['Thumbnail']['source'] : '',
                'small'       => (isset($aPhotoUrls['Small'])) ? $aPhotoUrls['Small']['source'] : '',
                'medium'      => (isset($aPhotoUrls['Medium'])) ? $aPhotoUrls['Medium']['source'] : '',
                'large'       => (isset($aPhotoUrls['Large'])) ? $aPhotoUrls['Large']['source'] : '',
                'description' => $description,
            ];
        }
    }
    return $aPhotos;
}

function flickrGetPhotoUrl($photo_id, $aFlickrCfg)
{
    $api         = new Flickr_API(['api_key' => $aFlickrCfg['apiKey']]);
    $response    = $api->callMethod('flickr.photos.getSizes', ['photo_id' => $photo_id, 'email' => $aFlickrCfg['email'], 'password' => $aFlickrCfg['password']]);
    $hasResponse = flickrCheckResponse($api, $response);
    if ($hasResponse === true) {
        $aPhotoUrls = [];
        $photosInfo = $response->children[0]->children;
        foreach ($photosInfo as $key => $photo) {
            $aPhotoUrls[$photo->attributes['label']] = [
                'source' => $photo->attributes['source'],
                'url'    => $photo->attributes['url'],
            ];
        }
    }
    return $aPhotoUrls;
}

function flickrGetPhotoDescription($photo_id, $aFlickr, $secret)
{
    $api         = new Flickr_API(['api_key' => $aFlickr['apiKey']]);
    $response    = $api->callMethod('flickr.photos.getInfo', ['photo_id' => $photo_id, 'secret' => $secret, 'email' => $aFlickr['email'], 'password' => $aFlickr['password']]);
    $hasResponse = flickrCheckResponse($api, $response);
    if ($hasResponse === true) {
        return $response->children[0]->children[2]->content;
    } else {
        return '';
    }
}

function &flickr_getActivePhoto($photos, $photo_id)
{
    $active_photo = $photos[0];
    foreach ($photos as $photo) {
        if ($photo_id == $photo['id']) {
            $active_photo = $photo;
            break;
        }
    }
    return $active_photo;
}


