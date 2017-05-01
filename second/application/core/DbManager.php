<?php

class DbManager
{
    protected $connections = array();
    protected $repositoryConnectionMap = array();

    /**
     * @param string $name
     * @param array $params
     */
    public function connect($name, $params)
    {
        $params = array_merge(array(
            'dsn'      => null,
            'user'     => '',
            'password' => '',
            'options'  => array(),
        ), $params);

        $con = new PDO(
            $params['dsn'],
            $params['user'],
            $params['password'],
            $params['options']
        );

        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->connections[$name] = $con;
    }

    /**
     * @param string|null $name
     * @return PDO
     */
    public function getConnection($name = null)
    {
        if (is_null($name)) {
            return current($this->connections);
        }

        return $this->connections[$name];
    }

    /**
     * @param string $repositoryName
     * @param string $name
     */
    public function setRepositoryConnectionMap($repositoryName, $name)
    {
        $this->repositoryConnectionMap[$repositoryName] = $name;
    }

    /**
     * @param string $repositoryName
     * @return PDO
     */
    public function getConnectionForRepository($repositoryName)
    {
        if (isset($this->repositoryConnectionMap[$repositoryName])) {
            $name = $this->repositoryConnectionMap[$repositoryName];
            $con  = $this->getConnection($name);
        } else {
            $con  = $this->getConnection();
        }

        return $con;
    }
}