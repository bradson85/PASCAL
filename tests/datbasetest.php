<?php
use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;

class MySqlTest extends TestCase
{
    use TestCaseTrait;

    /**
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    public function getConnection()
    {
        $database = 'formassess';
        $host = 'localhost';
        $user = 'root';
        $password = 'root';
        $pdo = new PDO('mysql:host='. $host.';dbname='.$database, $user, $password);
        return $this->createDefaultDBConnection($pdo, $database);
    }

    public function testDB()
    {
        $dataSet = $this->getConnection()->createDataSet();
        
    }

    public function testFilteredTB()
    {
        // for this add/edit words funcionality i use trerms and categories from the db
        $tableNames = ['terms','categories'];
        $dataSet = $this->getConnection()->createDataSet($tableNames);
        
    }
}
?>