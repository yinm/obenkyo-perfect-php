<?php

class View
{
    protected $baseDir;
    protected $defaults;
    protected $layoutVariables = array();

    /**
     * View constructor.
     * @param string $baseDir
     * @param array $defaults
     */
    public function __construct($baseDir, $defaults = array())
    {
        $this->baseDir = $baseDir;
        $this->defaults = $defaults;
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function setLayoutVar($name, $value)
    {
        $this->layoutVariables[$name] = $value;
    }

    /**
     * @param string $_path
     * @param array $_variables
     * @param bool $_layout
     * @return string
     */
    public function render($_path, $_variables = array(), $_layout = false)
    {
        $_file = $this->baseDir . '/' . $_path . '.php';
        extract(array_merge($this->defaults, $_variables));

        ob_start();
        ob_implicit_flush(0);

        require $_file;
        $content = ob_get_clean();

        if ($_layout) {
            $content = $this->render($_layout,
                array_merge($this->layoutVariables, array(
                    '_content' => $content,
                )
            ));
        }

        return $content;
    }

    /**
     * @param string $string
     * @return string
     */
    public function escape($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}
