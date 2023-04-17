<?php

namespace app\core;

use PDO;

class DataBase
{
    public PDO $pdo;
    private string $dsn;
    private string $user;
    private string $password;

    public function __construct()
    {
        $this->dsn = 'mysql:host=juliomatadamas.com;port=3306;dbname=juliomat_database';
        $this->user = 'juliomat_dba';
        $this->password = 'Kgn[Jvte9LLv';

        $this->pdo = new PDO($this->dsn, $this->user, $this->password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}