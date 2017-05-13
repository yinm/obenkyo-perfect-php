<?php

abstract class DbRepository
{
    protected $con;

    public function __construct()
    {
        $this->setConnection($con);
    }

    /**
     * @param PDO $con
     */
    public function setConnection($con)
    {
        $this->con = $con;
    }

    /**
     * @param string $sql
     * @param array $params
     * @return PDOStatement
     */
    public function execute($sql, $params = array())
    {
        $stmt = $this->con->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }

    /**
     * SELECT文の、1行のみの結果を返す
     *
     * @param string $sql
     * @param array $params
     * @return array|false
     */
    public function fetch($sql, $params = array())
    {
        return $this->execute($sql, $params)->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * SELECT文の、すべての行の結果を返す
     *
     * @param string $sql
     * @param array $params
     * @return array|false
     */
    public function fetchAll($sql, $params = array())
    {
        return $this->execute($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }
}