<?php

//
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2003 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.02 of the PHP license,      |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Author: Stig Bakken <ssb@fast.no>                                    |
// +----------------------------------------------------------------------+
//
// $Id: Parser.php,v 1.2 2005/06/02 20:25:48 eric_juden Exp $

require_once FLICKR_PEAR_PATH . '/PEAR.php';

/**
 * XML Parser class.  This is an XML parser based on PHP's "xml" extension,
 * based on the bundled expat library.
 *
 * @author  Stig Bakken <ssb@fast.no>
 * @todo    Tests that need to be made:
 *          - error class
 *          - mixing character encodings
 *          - a test using all expat handlers
 *          - options (folding, output charset)
 *          - different parsing modes
 *
 * @notes   - It requires PHP 4.0.4pl1 or greater
 *          - From revision 1.17, the function names used by the 'func' mode
 *            are in the format "xmltag_$elem", for example: use "xmltag_name"
 *            to handle the <name></name> tags of your xml file.
 */
class XML_Parser extends PEAR
{
    // {{{ properties

    /**
     * @var  resource  XML parser handle
     */

    public $parser;

    /**
     * @var  resource  File handle if parsing from a file
     */

    public $fp;

    /**
     * @var  bool  Whether to do case folding
     */

    public $folding = true;

    /**
     * @var  string  Mode of operation, one of "event" or "func"
     */

    public $mode;

    /**
     * Mapping from expat handler function to class method.
     *
     * @var  array
     */

    public $handler = [
        'character_dataHandler' => 'cdataHandler',
'defaultHandler' => 'defaultHandler',
'processing_instructionHandler' => 'piHandler',
'unparsed_entity_declHandler' => 'unparsedHandler',
'notation_declHandler' => 'notationHandler',
'external_entity_refHandler' => 'entityrefHandler',
    ];

    /**
     * @var string source encoding
     */

    public $srcenc;

    /**
     * @var string target encoding
     */

    public $tgtenc;

    /*
     * Use call_user_func when php >= 4.0.7
     * @var boolean
     * @see setMode()
     */

    public $use_call_user_func = true;

    // }}}

    // {{{ constructor

    /**
     * Creates an XML parser.
     *
     * @param null|mixed $srcenc
     * @param mixed      $mode
     * @param null|mixed $tgtenc
     *
     * @see xml_parser_create
     */
    public function __construct($srcenc = null, $mode = 'event', $tgtenc = null)
    {
        parent::__construct('XML_Parser_Error');

        if (null === $srcenc) {
            $xp = @xml_parser_create();
        } else {
            $xp = @xml_parser_create($srcenc);
        }

        if (is_resource($xp)) {
            if (null !== $tgtenc) {
                if (!@xml_parser_set_option(
                    $xp,
                    XML_OPTION_TARGET_ENCODING,
                    $tgtenc
                )) {
                    return $this->raiseError('invalid target encoding');
                }
            }

            $this->parser = $xp;

            $this->setMode($mode);

            xml_parser_set_option($xp, XML_OPTION_CASE_FOLDING, $this->folding);
        }

        $this->srcenc = $srcenc;

        $this->tgtenc = $tgtenc;
    }

    // }}}

    // {{{ setMode()

    /**
     * Sets the mode and all handler.
     *
     * @param mixed $mode
     * @see      $handler
     */
    public function setMode($mode)
    {
        $this->mode = $mode;

        xml_set_object($this->parser, $this);

        switch ($mode) {
            case 'func':
                // use call_user_func() when php >= 4.0.7
                // or call_user_method() if not
                if (version_compare(phpversion(), '4.0.7', 'lt')) {
                    $this->use_call_user_func = false;
                } else {
                    $this->use_call_user_func = true;
                }

                xml_set_elementHandler($this->parser, 'funcStartHandler', 'funcEndHandler');
                break;
            case 'event':
                xml_set_elementHandler($this->parser, 'startHandler', 'endHandler');
                break;
        }

        foreach ($this->handler as $xml_func => $method) {
            if (method_exists($this, $method)) {
                $xml_func = 'xml_set_' . $xml_func;

                $xml_func($this->parser, $method);
            }
        }
    }

    // }}}

    // {{{ setInputFile()

    /**
     * Defines
     *
     * @param mixed $file
     * @return   resource    fopen handle of the given file
     * @see      setInput(), parse()
     */
    public function setInputFile($file)
    {
        $fp = @fopen($file, 'rb');

        if (is_resource($fp)) {
            $this->fp = $fp;

            return $fp;
        }

        return $this->raiseError($php_errormsg);
    }

    // }}}

    // {{{ setInput()

    /**
     * Sets the file handle to use with parse().
     *
     * @param mixed $fp
     * @return bool|object|\PEAR_Error|\XML_Parser_Error
     * @see      parse(), setInputFile()
     */
    public function setInput($fp)
    {
        if (is_resource($fp)) {
            $this->fp = $fp;

            return true;
        }

        return $this->raiseError('not a file resource');
    }

    // }}}

    // {{{ parse()

    /**
     * Central parsing function.
     *
     * @throws   XML_Parser_Error
     * @return   bool true on success
     * @see      parseString()
     */
    public function parse()
    {
        if (!is_resource($this->fp)) {
            return $this->raiseError('no input');
        }

        while ($data = fread($this->fp, 2048)) {
            $err = $this->parseString($data, feof($this->fp));

            if (PEAR::isError($err)) {
                fclose($this->fp);

                return $err;
            }
        }

        fclose($this->fp);

        return true;
    }

    // }}}

    // {{{ parseString()

    /**
     * Parses a string.
     *
     * @param mixed $data
     * @param mixed $eof
     * @return   mixed   true on success or a string with the xml parser error
     */
    public function parseString($data, $eof = false)
    {
        if (!xml_parse($this->parser, $data, $eof)) {
            $err = $this->raiseError($this->parser);

            xml_parser_free($this->parser);

            return $err;
        }

        return true;
    }

    // }}}

    // {{{ funcStartHandler()

    public function funcStartHandler($xp, $elem, $attribs)
    {
        $func = 'xmltag_' . $elem;

        if (method_exists($this, $func)) {
            if ($this->use_call_user_func) {
                call_user_func([&$this, $func], $xp, $elem, $attribs);
            } else {
                call_user_method($func, $this, $xp, $elem, $attribs);
            }
        }
    }

    // }}}

    // {{{ funcEndHandler()

    public function funcEndHandler($xp, $elem)
    {
        $func = 'xmltag_' . $elem . '_';

        if (method_exists($this, $func)) {
            if ($this->use_call_user_func) {
                call_user_func([&$this, $func], $xp, $elem);
            } else {
                call_user_method($func, $this, $xp, $elem);
            }
        }
    }

    // }}}

    // {{{ startHandler()

    /**
     * @abstract
     * @param mixed $xp
     * @param mixed $elem
     * @param mixed $attribs
     * @return null
     * @return null
     */
    public function startHandler($xp, $elem, &$attribs)
    {
        return null;
    }

    // }}}

    // {{{ endHandler()

    /**
     * @abstract
     * @param mixed $xp
     * @param mixed $elem
     * @return null
     * @return null
     */
    public function endHandler($xp, $elem)
    {
        return null;
    }

    // }}}
}

class XML_Parser_Error extends PEAR_Error
{
    // {{{ properties

    public $error_message_prefix = 'XML_Parser: ';

    // }}}

    // {{{ constructor()

    public function __construct($msgorparser = 'unknown error', $code = 0, $mode = PEAR_ERROR_RETURN, $level = E_USER_NOTICE)
    {
        if (is_resource($msgorparser)) {
            $code = xml_get_error_code($msgorparser);

            $msgorparser = sprintf(
                '%s at XML input line %d',
                xml_error_string($code),
                xml_get_current_line_number($msgorparser)
            );
        }

        parent::__construct($msgorparser, $code, $mode, $level);
    }

    // }}}
}
