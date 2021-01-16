<?php 
    class Option {
        public function __construct($fixture, $name, $odd, $totalCount, $altName=null, $stat=null) {
            $this->fixture = $fixture;
            $this->name = $name;
            $this->altName = $altName;
            $this->stat = $stat;
            $this->odd = $odd;
            $this->totalCount = $totalCount;
        }

        public function setStat($stat) {
            $this->stat = $stat;
        }

        public function setAltName($altName) {
            $this->stat = $altName;
        }

        public function setOdd($altName) {
            $this->stat = $altName;
        }

        public function getStat() {
            return $this->stat;
        }

        public function getFixture() {
            return $this->fixture;
        }

        public function getName() {
            return $this->name;
        }

        public function getPoints() {
            if ($this->odd && $this->stat) {
                return $this->odd * $this;
            }
        }

        public function getAltName() {
            return $this->altName;
        }
    }

?>
