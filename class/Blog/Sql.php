<?php

namespace Blog;

use PDO;

Class Sql
{

    public $pdo;
    public $server;
    public $database;
    public $id;
    public $password;

    /**
     * Initialise la classe
     * @param string $server   addresse du serveur
     * @param string $database nom de la bdd
     * @param string $id       utilisateur mysql
     * @param string $password password de l'utilisateur
     */
    public function __construct($server, $database, $id, $password)
    {
        $this->server = $server;
        $this->database = $database;
        $this->id = $id;
        $this->password = $password;

        $dsn = 'mysql:dbname='.$database.';host='.$server;
        $this->pdo = new PDO($dsn, $id, $password);
    }

    /**
     * Execute une requete sql
     * ATTENTION : PDO query ne sécurise pas la requete
     * C'est à vous de le faire
     *
     * @param  string $sql        Requete à éxécuter
     * @return PDO:Statement      Objet résultat
     */
    public function query($sql)
    {
        return $this->pdo->query($sql);
    }

    /**
     * [prepareExec description]
     * @param  [type] $sql      [description]
     * @param  [type] $argument [description]
     * @return [type]           [description]
     */
    public function prepareExec($sql, $argument)
    {
        $statement = $this->pdo->prepare($sql);
        $statement->execute($argument);

        return $statement;
    }

    public function lastId(){
        return $this->pdo->lastInsertId();
    }
}
