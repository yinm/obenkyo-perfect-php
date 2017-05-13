<?php

class Session
{
    protected static $sessionStarted = false;
    protected static $sessionIdRegenerated = false;

    public function __construct()
    {
        if (!self::$sessionStarted) {
            session_start();

            self::$sessionStarted = true;
        }
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function get($name, $default = null)
    {
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        }

        return $default;
    }

    /**
     * @param string $name
     */
    public function remove($name)
    {
        unset($_SESSION[$name]);
    }

    public function clear()
    {
        $_SESSION = array();
    }

    /**
     * @param bool $destroy
     */
    public function regenerate($destroy = true)
    {
        if (!self::$sessionIdRegenerated) {
            session_regenerate_id($destroy);

            self::$sessionIdRegenerated = true;
        }
    }

    /**
     * @param bool $bool
     */
    public function setAuthenticated($bool)
    {
        $this->set('_authenticated', (bool)$bool);

        $this->regenerate();
    }

    /**
     * @return bool
     */
    public function isAuthenticated()
    {
        return $this->get('_authenticated', false);
    }
}