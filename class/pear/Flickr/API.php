<?php

#
# PEAR::Flickr_API
#
# Author: Cal Henderson
# Version: $Revision: 1.2 $
# CVS: $Id: API.php,v 1.2 2005/06/02 20:25:48 eric_juden Exp $
#
require_once FLICKR_PEAR_PATH . '/XML/Tree.php';
require_once FLICKR_PEAR_PATH . '/HTTP/Request.php';

class Flickr_API
{
    public $_cfg = [
        'api_key' => '',
'endpoint' => 'http://www.flickr.com/services/rest/',
'conn_timeout' => 5,
'io_timeout' => 5,
    ];

    public $_err_code = 0;

    public $_err_msg = '';

    public function __construct($params = [])
    {
        foreach ($params as $k => $v) {
            $this->_cfg[$k] = $v;
        }
    }

    public function callMethod($method, $params = [])
    {
        $this->_err_code = 0;

        $this->_err_msg = '';

        # create the POST body

        $p = $params;

        $p['method'] = $method;

        $p['api_key'] = $this->_cfg['api_key'];

        $p2 = [];

        foreach ($p as $k => $v) {
            $p2[] = urlencode($k) . '=' . urlencode($v);
        }

        $body = implode('&', $p2);

        # create the http request

        $req = new HTTP_Request($this->_cfg['endpoint'], ['timeout' => $this->_cfg['conn_timeout']]);

        $req->_readTimeout = [$this->_cfg['io_timeout'], 0];

        $req->setMethod(HTTP_REQUEST_METHOD_POST);

        $req->addRawPostData($body);

        $req->sendRequest();

        $this->_http_code = $req->getResponseCode();

        $this->_http_head = $req->getResponseHeader();

        $this->_http_body = $req->getResponseBody();

        if (200 != $this->_http_code) {
            $this->_err_code = 0;

            if ($this->_http_code) {
                $this->_err_msg = "Bad response from remote server: HTTP status code $this->_http_code";
            } else {
                $this->_err_msg = "Couldn't connect to remote server";
            }

            return 0;
        }

        # create xml tree

        $tree = new XML_Tree();

        $tree->getTreeFromString($this->_http_body);

        # check we got an <rsp> element at the root

        if ('rsp' != $tree->root->name) {
            $this->_err_code = 0;

            $this->_err_msg = 'Bad XML response';

            return 0;
        }

        # stat="fail" ?

        if ('fail' == $tree->root->attributes['stat']) {
            $n = $tree->root->children[0]->attributes;

            $this->_err_code = $n['code'];

            $this->_err_msg = $n['msg'];

            return 0;
        }

        # weird status

        if ('ok' != $tree->root->attributes['stat']) {
            $this->_err_code = 0;

            $this->_err_msg = 'Unrecognised REST response status';

            return 0;
        }

        # return the tree

        return $tree->root;
    }

    public function getErrorCode()
    {
        return $this->_err_code;
    }

    public function getErrorMessage()
    {
        return $this->_err_msg;
    }
}
