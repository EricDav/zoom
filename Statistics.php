<?php 

    class Statistics {
        const HOME = '1';
        const AWAY = '2';
        const DRAW = 'X';
        const HOME_DRAW = '1X';
        const AWAY_DRAW = 'X2';
        const OVER_2_POINT_5 = 'Over 2.5';
        const UNDER_2_POINT_5 = 'Under 2.5';
        const GOAL_GOAL = 'GG';
        const NO_GOAL_GOAL = 'NG';
        const ANY_BODY_WIN = '12';

        const OPTIONS_NAME = array (
            '1' => 'Home',
            'X' => 'Draw',
            '2' => 'Away',
            '1X' => 'Home or Draw',
            'X2' => 'Draw or Away',
            'Over 2.5' => null,
            'Under 2.5' => null,
            'GG' => 'Goal Goal',
            'NG' => 'No Goal Goal',
            '12' => 'Any body wins',
        );

        public function __construct($data) {
            $this->data = $data;
            $this->allStat = [];
            $this->stat = [];
        }

        public function loadAllStat() {
            // Loop through all the fixtures for a particular round
            foreach($this->data as $datum) {
                $optionCount = array (
                    Statistics::HOME => 0,
                    Statistics::AWAY => 0,
                    Statistics::DRAW => 0,
                    // Statistics::HOME_DRAW => 0,
                    // Statistics::AWAY_DRAW => 0,
                    Statistics::OVER_2_POINT_5 => 0,
                    Statistics::UNDER_2_POINT_5 => 0,
                    Statistics::GOAL_GOAL => 0,
                    // Statistics::NO_GOAL_GOAL => 0,
                    // Statistics::ANY_BODY_WIN => 0
                );

                $fixtureArr = explode(' - ', $datum->fixture);
                $home = $fixtureArr[0];
                $away = $fixtureArr[1];
                $totalCount = 0;
                $refStat = null;

                // loop through all the previous matches of a particular fixture
                foreach($datum->stat as $stat) {
                    if ($stat->home == $home && $stat->away == $away) {
                        if (!$refStat)
                            $refStat = $stat;
                        // var_dump($refStat); exit;
                        
                        foreach($optionCount as $option => $count) {
                            if ($this->evaluate($stat->ft_score, $option)) {
                                $optionCount[$option] = $optionCount[$option] + 1;
                            }
                        }
                        $totalCount+=1;
                    }
                }

                // loop throup the option count
                $allStat = array();
                foreach($optionCount as $option => $count) {
                    $probability = number_format($count/$totalCount, 2);
                    
                    $odd = $refStat->$option ? $refStat->$option : 1;
                    $optionObj = new Option($datum->fixture, $option, $odd, $totalCount, Statistics::OPTIONS_NAME[$option], $probability);
                    array_push(
                        $allStat,
                        $optionObj
                    );
                    if ($probability >= 0.7 && $totalCount >= 5 && $odd >= 1.2) {
                        array_push(
                            $this->stat,
                            $optionObj
                        );
                    }
                }

                $this->allStat[$datum->fixture] = $allStat;
            }
        }

        /**
         * This method evaluate an option given a result 
         * 
         * for instance lets say the result is 2 - 0 and option GG 
         * the method will return false because the result doesn't 
         * evaluate to GG
         * 
         * @result is a string seperated by -
         * 
         * @return boolean true | false
         */
        public function evaluate($result, $option) {
            $resultArray = explode('-', $result);
            $homeGoal = (int)$resultArray[0];
            $awayGoal = (int)$resultArray[1];
            switch ($option) {
                case Statistics::HOME:
                    if ($homeGoal > $awayGoal) {
                        return true;
                    }
                    return false;

                case Statistics::AWAY:
                    if ($awayGoal > $homeGoal) {
                        return true;
                    }
                    return false;

                case Statistics::DRAW:
                    if ($awayGoal == $homeGoal) {
                        return true;
                    }
                    return false;

                case Statistics::HOME_DRAW:
                    if ($homeGoal >= $awayGoal) {
                        return true;
                    }
                    return false;
                
                case Statistics::AWAY_DRAW:
                    if ($awayGoal >= $homeGoal) {
                        return true;
                    }
                    return false;
                
                case Statistics::OVER_2_POINT_5:
                    if (($awayGoal + $homeGoal) > 2) {
                        return true;
                    }
                    return false;

                case Statistics::UNDER_2_POINT_5:
                    if (($awayGoal + $homeGoal) < 3) {
                        return true;
                    }
                    return false;
                
                case Statistics::GOAL_GOAL:
                    if ($awayGoal > 0 && $homeGoal > 0) {
                        return true;
                    }
                    return false;

                case Statistics::NO_GOAL_GOAL:
                    if ($awayGoal == 0 || $homeGoal == 0) {
                        return true;
                    } 
                    return false;
                
                case Statistics::ANY_BODY_WIN:
                    if ($awayGoal > $homeGoal || $homeGoal > $awayGoal) {
                        return true;
                    } 
                    return false;
            }
        }
    }

?>