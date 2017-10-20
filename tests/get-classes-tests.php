<?php
    use PHPUnit\Framework\Testcase;

    include('inc.get-classes.php');

    class GetClassesTests extends testcase
    {
        public function testEmptySchool(){
            $school = "";

            $data = Array("school" => "$school");
            $this->assertFalse(GetClasses($data));
        }

        public function testNullSchool(){
            $school = null;
            $data = Array("school" => null);

            $this->assertFalse(GetClasses($data));
        }
        //For this test case you will have to change the return statement 
        //in the function to return true and comment the exit statement
        //It will fail otherwise, not sure how to fix it
        public function testCorrectSchool(){
            $school = "School1";
            $data = Array("school" => "$school");

            $this->assertTrue(GetClasses($data));
        }
    }
?>