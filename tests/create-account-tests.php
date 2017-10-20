<?php
    use PHPUnit\Framework\Testcase;
    include('inc.create-account.php');

    class CreateAccountTests extends Testcase
    {
        public function testEmptyName()
        {
            $name = "";
            $email = "email@email.com";
            $type = "Administrator";
            $school = "School1";
            $class = "Class1";
            $this->assertFalse(insert_to_db($name, $email, $type, $school, $class));
        }

        public function testNullName(){
            $name = null;
            $email = "email@email.com";
            $type = "Administrator";
            $school = "School1";
            $class = "Class1";
            $this->assertFalse(insert_to_db($name, $email, $type, $school, $class));
        }

        public function testCorrectName(){
            $name = "Name";
            $email = "email@email.com";
            $type = "Administrator";
            $school = "School1";
            $class = "Class1";
            $this->assertTrue(insert_to_db($name, $email, $type, $school, $class));
        }

        public function testEmptyEmail(){
            $name = "Name";
            $email = "";
            $type = "Administrator";
            $school = "School1";
            $class = "Class1";
            $this->assertFalse(insert_to_db($name, $email, $type, $school, $class));
        }

        public function testNullEmail(){
            $name = "Name";
            $email = null;
            $type = "Administrator";
            $school = "School1";
            $class = "Class1";
            $this->assertFalse(insert_to_db($name, $email, $type, $school, $class));
        }

        public function testCorrectEmail(){
            $name = "Name";
            $email = "email@email.com";
            $type = "Administrator";
            $school = "School1";
            $class = "Class1";
            $this->assertTrue(insert_to_db($name, $email, $type, $school, $class));
        }

        public function testIncorrectEmail(){
            $name = "Name";
            $email = "emailemailcom";
            $type = "Administrator";
            $school = "School1";
            $class = "Class1";
            $this->assertFalse(insert_to_db($name, $email, $type, $school, $class));
        }
    }
?>