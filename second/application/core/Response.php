<?php

class Response
{
    protected $content;
    protected $statusCode = 200;
    protected $statusText = 'OK';
    protected $httpHeaders = array();

    public function send()
    {
        header('HTTP/1.1 ' . $this->statusCode . ' ' . $this->statusText);

        foreach ($this->httpHeaders as $name => $value) {
            header($name . ': ' . $value);
        }

        echo $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function setStatusCode($statusCode, $statusText = '')
    {
        $this->statusCode = $statusCode;
        $this->statusText = $statusText;
    }

    public function setHttpHeader($name, $value)
    {
        $this->httpHeaders[$name] = $value;
    }
}
