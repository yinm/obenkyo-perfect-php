<?php

class UserRepository extends DbRepository
{
    public function insert($user_name, $password)
    {
        $password = $this->hashpassword($password);
        $now = new DateTime();

        $sql = "
            INSERT INTO user(user_name, password, created_at)
            VALUES(:user_name, :password, :created_at)
        ";

        $stmt = $this->execute($sql, array(
            ':user_name'  => $user_name,
            ':password'   => $password,
            ':created_at' => $now->format('Y-m-d H:i:s'),
        ));
    }

    public function hashpassword($password)
    {
        return sha1($password . 'SecretKey');
    }
}