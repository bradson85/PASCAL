<?php
use PHPUnit\Framework\TestCase;

class OutputTest extends TestCase
{
    public function testGetCategoriesQuery()
    {   
        require_once("../php/inc-addwords-getcategories.php");
        $queryTable = $this->getConnection()->createQueryTable(
            'getCategories', 'SELECT * From categories'
        );
        $expectedTable = $this->getDataSet()
                              ->getTable("category");
        $this->assertTablesEqual($expectedTable, $queryTable);
    }

    public function testReadQuery()
    {   
        require_once("../php/inc-addwords-read.php");
        $queryTable = $this->getConnection()->createQueryTable(
            'getterms', 'SELECT * From terms'
        );
        $expectedTable = $this->getDataSet()
                              ->getTable("terms");
        $this->assertTablesEqual($expectedTable, $queryTable);
    }

    
}
?>