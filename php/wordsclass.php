<?php 

class WordClass {
        private $id;
        private $word;
        private $definition;
        private $catID;
        private $catName;
        private $level;
        
        
        function __construct($r) {
            $this->id = ($r['ID']);
            $this->word = ($r['name']);
            $this->definition = ($r['definition']);
            $this->catID =($r['catID']);
            $this->catName = ($r['category']);
            $this->level = ($r['level']);
            }

            public function getID()
            { return $this->id; }
            
            public function getWord()
            { return $this->word; }
           
            public function getDefinition()
            { return $this->definition; }
            
            public function getCatID()
            { return $this->catID; }

            public function getCatName()
            { return $this->catName; }

            public function getLevel()
            { return $this->level; }
        
           
            public function setID($id) {
            $this->id = $id;
            }
           
            public function setWord($word) {
            $this->word = $word;
            }
           
            public function setDefinition($definition) {
            $this->definition = $definition;
            }
           
            public function setCatID($catid) {
            $this->catID = $catid;
            }

            public function setCatName($catName) {
                $this->catName = $catName;
                }
               
            public function setLevel($level) {
                $this->level = $level;
                }

            public function __toString() {
                $str = $this->getID() .
                "," .
                $this->getWord() .
                "," .
                $this->getDefinition() .
                "," .
                $this->getCatID() .
                "," .
                $this->getCatName() .
                "," .
                $this->getLevel();
                return $str;
                }

          
    }  ?>