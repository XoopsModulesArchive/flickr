<?php

//
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2002 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.02 of the PHP license,      |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Bernd Römer <berndr@bonn.edu>                               |
// |          Sebastian Bergmann <sb@sebastian-bergmann.de>               |
// |          Christian Kühn <ck@chkuehn.de> (escape xml entities)        |
// +----------------------------------------------------------------------+
//
// $Id: Node.php,v 1.2 2005/06/02 20:25:48 eric_juden Exp $
//

/**
 * PEAR::XML_Tree_Node
 *
 * @author  Bernd Römer <berndr@bonn.edu>
 * @version 1.0  16-Aug-2001
 */
class XML_Tree_Node
{
    /**
     * Attributes of this node
     *
     * @var  array
     */

    public $attributes;

    /**
     * Children of this node
     *
     * @var  array
     */

    public $children;

    /**
     * Content
     *
     * @var  string
     */

    public $content;

    /**
     * Name
     *
     * @var  string
     */

    public $name;

    /**
     * Constructor
     *
     * @param mixed $name
     * @param mixed $content
     * @param mixed $attributes
     */
    public function __construct($name, $content = '', $attributes = [])
    {
        $this->attributes = $attributes;

        $this->children = [];

        $this->set_content($content);

        $this->name = $name;
    }

    /**
     * Adds a child node to this node.
     *
     * @param mixed $child
     * @param mixed $content
     * @param mixed $attributes
     * @return object  reference to new child node
     */
    public function &addChild($child, $content = '', $attributes = [])
    {
        $index = count($this->children);

        if (is_object($child)) {
            if ('xml_tree_node' == mb_strtolower(get_class($child))) {
                $this->children[$index] = $child;
            }

            if ('xml_tree' == mb_strtolower(get_class($child)) && isset($child->root)) {
                $this->children[$index] = $child->root->get_element();
            }
        } else {
            $this->children[$index] = new self($child, $content, $attributes);
        }

        return $this->children[$index];
    }

    /**
     * @param mixed $child
     * @param mixed $content
     * @param mixed $attributes
     * @return object|\XML_Tree_Node
     * @return object|\XML_Tree_Node
     * @deprecated
     */
    public function &add_child($child, $content = '', $attributes = [])
    {
        return $this->addChild($child, $content, $attributes);
    }

    /**
     * clone node and all its children (recursive)
     *
     * @return object reference to the clone-node
     */
    public function clone()
    {
        $clone = new self($this->name, $this->content, $this->attributes);

        $max_child = count($this->children);

        for ($i = 0; $i < $max_child; $i++) {
            $clone->children[] = $this->children[$i]->clone();
        }

        /* for future use....
            // clone all other vars
            $temp=get_object_vars($this);
            foreach($temp as $varname => $value)
                if (!in_array($varname,array('name','content','attributes','children')))
                    $clone->$varname=$value;
        */

        return ($clone);
    }

    /**
     * inserts child ($child) to a specified child-position ($pos)
     *
     * @param mixed $path
     * @param mixed $pos
     * @param mixed $child
     * @param mixed $content
     * @param mixed $attributes
     * @return \XML_Tree_Node node
     */
    public function insertChild($path, $pos, &$child, $content = '', $attributes = [])
    {
        // direct insert of objects useing array_splice() faild :(

        array_splice($this->children, $pos, 0, 'dummy');

        if (is_object($child)) { // child offered is not instanziated
            // insert a single node

            if ('xml_tree_node' == mb_strtolower(get_class($child))) {
                $this->children[$pos] = &$child;
            }

            // insert a tree i.e insert root-element

            if ('xml_tree' == mb_strtolower(get_class($child)) && isset($child->root)) {
                $this->children[$pos] = $child->root->get_element();
            }
        } else { // child offered is not instanziated
            $this->children[$pos] = new self($child, $content, $attributes);
        }

        return ($this);
    }

    /**
     * @param mixed $path
     * @param mixed $pos
     * @param mixed $child
     * @param mixed $content
     * @param mixed $attributes
     * @return \XML_Tree_Node
     * @return \XML_Tree_Node
     * @deprecated
     */
    public function insert_child($path, $pos, &$child, $content = '', $attributes = [])
    {
        return $this->insertChild($path, $pos, $child, $content, $attributes);
    }

    /**
     * removes child ($pos)
     *
     * @param mixed $pos
     *
     * @return array node
     */
    public function removeChild($pos)
    {
        // array_splice() instead of a simple unset() to maintain index-integrity

        return (array_splice($this->children, $pos, 1));
    }

    /**
     * @param mixed $pos
     * @return array
     * @return array
     * @deprecated
     */
    public function remove_child($pos)
    {
        return $this->removeChild($pos);
    }

    /**
     * Returns text representation of this node.
     *
     * @return  string  xml
     */
    public function &get()
    {
        static $deep = -1;

        static $do_ident = true;

        $deep++;

        if (null !== $this->name) {
            $ident = str_repeat('  ', $deep);

            if ($do_ident) {
                $out = $ident . '<' . $this->name;
            } else {
                $out = '<' . $this->name;
            }

            foreach ($this->attributes as $name => $value) {
                $out .= ' ' . $name . '="' . $value . '"';
            }

            $out .= '>' . $this->content;

            if (count($this->children) > 0) {
                $out .= "\n";

                foreach ($this->children as $child) {
                    $out .= $child->get();
                }
            } else {
                $ident = '';
            }

            if ($do_ident) {
                $out .= $ident . '</' . $this->name . ">\n";
            } else {
                $out .= '</' . $this->name . '>';
            }

            $do_ident = true;
        } else {
            $out = $this->content;

            $do_ident = false;
        }

        $deep--;

        return $out;
    }

    /**
     * Gets an attribute by its name.
     *
     * @param mixed $name
     * @return string  attribute
     */
    public function getAttribute($name)
    {
        return $this->attributes[mb_strtolower($name)];
    }

    /**
     * @param mixed $name
     * @return string
     * @return string
     * @deprecated
     */
    public function get_attribute($name)
    {
        return $this->getAttribute($name);
    }

    /**
     * Gets an element by its 'path'.
     *
     * @param mixed $path
     * @return object  element
     */
    public function &getElement($path)
    {
        if (0 == count($path)) {
            return $this;
        }

        $next = array_shift($path);

        return $this->children[$next]->get_element($path);
    }

    /**
     * @param mixed $path
     * @return object|\XML_Tree_Node
     * @return object|\XML_Tree_Node
     * @deprecated
     */
    public function &get_element($path)
    {
        return $this->getElement($path);
    }

    /**
     * Sets an attribute.
     *
     * @param mixed $name
     * @param mixed $value
     */
    public function setAttribute($name, $value = '')
    {
        $this->attributes[mb_strtolower($name)] = $value;
    }

    /**
     * @deprecated
     * @param mixed $name
     * @param mixed $value
     */
    public function set_attribute($name, $value = '')
    {
        return $this->setAttribute($name, $value);
    }

    /**
     * Unsets an attribute.
     *
     * @param mixed $name
     */
    public function unsetAttribute($name)
    {
        unset($this->attributes[mb_strtolower($name)]);
    }

    /**
     * @deprecated
     * @param mixed $name
     */
    public function unset_attribute($name)
    {
        return $this->unsetAttribute($name);
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        # WHAT THE FUCK IS WRONG WITH PEOPLE?

        #$this->content = $this->_xml_entities($content);

        $this->content = $content;
    }

    public function set_content($content)
    {
        return $this->setContent($content);
    }

    /**
     * Escape XML entities.
     *
     * @param mixed $xml
     * @return  string  xml
     */
    public function _xml_entities($xml)
    {
        $xml = str_replace(
            [
                'ü',
                'Ü',
                'ö',
                'Ö',
                'ä',
                'Ä',
                'ß',
            ],
            [
                '&#252;',
                '&#220;',
                '&#246;',
                '&#214;',
                '&#228;',
                '&#196;',
                '&#223;',
            ],
            $xml
        );

        $xml = preg_replace(
            [
                "/\&([a-z\d\#]+)\;/i",
                "/\&/",
                "/\#\|\|([a-z\d\#]+)\|\|\#/i",
                "/([^a-zA-Z\d\s\<\>\&\;\.\:\=\"\-\/\%\?\!\'\(\)\[\]\{\}\$\#\+\,\@_])/e",
            ],
            [
                '#||\\1||#',
                '&amp;',
                '&\\1;',
                "'&#'.ord('\\1').';'",
            ],
            $xml
        );

        return $xml;
    }

    /**
     * Print text representation of XML tree.
     */
    public function dump()
    {
        echo $this->get();
    }
}
