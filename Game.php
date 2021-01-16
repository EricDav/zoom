<?php 
    class Game {
        public function __construct($options=null) {
            
            if (is_array($options)) {
                $this->options = [];
                foreach($options as $option) {
                    if (!$this->isFixtureAlreadyExist($options, $option->fixture)) {
                        array_push($this->options, $option);
                    }
                }

            } else if (is_null($option)) {
                $this->options = [];
            } else {
                $this->options = [$option];
            }

            $this->probability = $this->getProbabilityStat();
        }

        public function isFixtureAlreadyExist($options, $fixture) {
            // var_dump($options); var_dump($fixture); exit;

            if (sizeof($options) == 0) return false;

            $count = 0;
            foreach($options as $option) {
                if ($count == 2) return true;

                if ($option->fixture == $fixture) {
                    $count+=1;
                }

            }
            return false;
        }

        public function addOption(Option $option) {
            if (!$this->isFixtureAlreadyExist($options, $option->fixture)) {
                array_push($this->options, $option);
                $this->probability = $this->getProbabilityStat();
            }

        }

        public function getOdd() {
            $odds = 1;
           // var_dump($this->options);
            foreach($this->options as $option) {
               // echo $option->odd; exit;
                $odds *=(float)$option->odd;
            }

           // var_dump($odds); exit;
            return $odds;
        }

        public function getNumOptions() {
            return sizeof($this->options);
        }

        public function getProbabilityStat() {
            $stat = 1;
            foreach($this->options as $option) {
                $stat*=$option->stat;
            }

            return $stat;
        }

        public function getPoint() {
            return $this->getOdd() * $this->getProbabilityStat();
        }
    }

?>