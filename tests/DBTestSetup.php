<?php 
use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\CsvDataSet;

require_once("../dbconfig.php");

class DBTestFixtures extends DatabaseTestCase
{

use TestCaseTrait;

public function getDataSet()
{
    /**
     * Prepare data set for database tests
     *
     * @return \PHPUnit_Extensions_Database_DataSet_AbstractDataSet
     */
    $dataSet = new \PHPUnit_Extensions_Database_DataSet_CsvDataSet();
    $dataSet->addTable( 'terms', __DIR__ . 'testtableterms.csv' );
    $dataSet->addTable( 'categories', __DIR__ . 'testtablecategories.csv' );
    
    return $dataSet;
}
 
    /**
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    public function getConnection()
    {
    
        $pdo = new PDO(DB_CONNECTION_STRING,
        DB_USER, DB_PWD);
        return $this->createDefaultDBConnection($pdo, DB_NAME);
    }

}
?>