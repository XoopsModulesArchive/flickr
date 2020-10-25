<?php

/**
 * A wrapper around PHP's session functions
 * @author  Harry Fuecks (PHP Anthology Volume II)
 */
class flickrSession
{
    /**
     * Session constructor<br>
     * Starts the session with session_start()
     * <b>Note:</b> that if the session has already started,
     * session_start() does nothing
     */
    public function __construct()
    {
        @session_start();
    }

    /**
     * Sets a session variable
     * @param mixed $name
     * @param mixed $value
     */
    public function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    /**
     * Fetches a session variable
     * @param mixed $name
     * @return mixed value of session variable
     */
    public function get($name)
    {
        return $_SESSION[$name] ?? false;
    }

    /**
     * Deletes a session variable
     * @param mixed $name
     */
    public function del($name)
    {
        unset($_SESSION[$name]);
    }

    /**
     * Destroys the whole session
     */
    public function destroy()
    {
        $_SESSION = [];

        session_destroy();
    }

    public function singleton()
    {
        static $_sess;

        if (!isset($_sess)) {
            $_sess = new self();
        }

        return $_sess;
    }
}
