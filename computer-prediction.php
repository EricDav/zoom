<?php
   include 'Option.php';
   include 'Statistics.php';
   include 'Game.php';
   include 'data.php';

   define('DEFAULT_MIN_MATCHES', 11);
   define('DEFAULT_MAX_MATCHES', 12);
   define('MIN_STAT', 0.6);

   function predict($minOdd, $maxOdd, $minMatches, $maxMatches, $minStat=MIN_STAT) {
        if (!is_numeric($minOdd) || !is_numeric($maxOdd) || !is_numeric($minMatches) || !is_numeric($maxMatches)) {
            echo 'Here!!';
            return null;
        }

        $data = getData();
        $data = array('data' => $data, 'success' => true);
        $data = json_decode(json_encode($data))->data;
        $minMatches = $minMatches == DEFAULT_MIN_MATCHES ? 2 : $minMatches;
        $maxMatches = $maxMatches == DEFAULT_MAX_MATCHES ? 7 : $maxMatches;

        $r = new Statistics($data);
        $r->loadAllStat();
        $stats = $r->stat;
        $games = [];

        for ($i = $minMatches; $i <= $maxMatches; $i++) {
            $matchesCominations = getDynamicMatchesCombination($stats, $i);
            foreach($matchesCominations as $matchComb) {
                $game = new Game($matchComb);
                if ($game->getProbabilityStat() >= $minStat && $game->getOdd() >= $minOdd) {
                    array_push($games, $game);
                }
            }
        }

        return getBestGame($games); 
    }

    function getBestGame($games) {
        $bestGame = $games[0];

        foreach($games as $game) {
            // Priotize the probability of a game first. if the probability is bigger
            // than the best game replace. But if it is the same then check whose odds is bigger
            if ($game->probability > $bestGame->probability || ($game->probability == $bestGame->probability && $game->odd > $bestGame->odd)) {
                $bestGame = $game;
            }
        }

        return $bestGame;
    }


    function isUniqueGames($games, $game) {
        foreach($games as $g) {
            foreach($game->options as $opt) {
                foreach($g->options as $p) {
                    if ($opt->fixture == $p->fixture && $opt->name == $p->name) {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    function getDynamicMatchesCombination($matches, $numCombination) {
        if ($numCombination == 2) {
            return getMatchesCombination($matches, $numCombination);
        }

        $rangStartEnd = sizeof($matches) - ($numCombination -1);
        $rangStartValues = array();
        $fResults = [];

        for($i = 1; $i <= $rangStartEnd; $i++) {
            $results = getDynamicMatchesCombination(array_slice($matches, $i), $numCombination-1);
            foreach($results as $result) {
                array_unshift($result, $matches[$i-1]);
                array_push($fResults, $result);
            }
        }

        return cleanUp($fResults);
    }

    function getMatchesCombination($matches, $numCombination) {
        $resultArrLen = getCombinationLength(sizeof($matches), $numCombination);
        $results = [];
        $a =  sizeof($matches) - 1; // First term of the sequence(the number of index e.g for 4 element 1,1,1,2,2,3 => 3, 2, 1 AP)
        $d = -1;
        for($i = 1; $i <= $resultArrLen; $i++) {
            $firstIndex = (int)ceil(quadraticSolToNthTerm($a, $d, $i));
            $secondIndex = $firstIndex == 1 ? ($firstIndex + $i) : ($firstIndex + $i - sumOfAP($a, $firstIndex -1, $d));
            $elem1 = $matches[$firstIndex-1];
            $elem2 = $matches[$secondIndex - 1];
            $result = [$elem1, $elem2];
            
        //  if (!isSame($elem1, $elem2)) {
                array_push($results, $result);
        //   }
        }

        return cleanUp($results);
    }

    function isSame($elem1, $elem2) {
        if (is_numeric($elem1)) {
            return $elem1 == $elem2;
        }

        return $elem1->fixture == $elem2->fixture;
    }

    function isExist($matches, $match) {
        if (is_numeric($match[0])) {
            return isNumber($matches, $match);
        }

        return isOption($matches, $match);
    }

    function isNumber($matches, $match) {
        foreach($matches as $m) {
            $trueCount = 0;
            foreach($match as $ml) {
                if (in_array($ml, $m)) {
                    $trueCount+=1;
                }
            }

            if ($trueCount == sizeof($match)) {
                return true;
            }
            // if (in_array($match[0], $m) && in_array($match[1], $m)) {
            //     return true;
            // }
        }

        return false;
    }

    function cleanUp($options) {
        $results = [];
        foreach($options as $option) {
            $fixtures = [];
            $seenFixture = false;
            foreach($option as $p) {
                if (in_array($p->fixture, $fixtures) && $p->name ) {
                    $seenFixture = true;
                    break;
                }
                array_push($fixtures, $p->fixture);
            }

            if (!$seenFixture) {
                array_push($results, $option);
            }
        }

        return $results;
    }

    function isOption($matches, $match) {
        foreach($matches as $m) {

            $trueCount = 0;
            foreach($match as $ml) {
                if (inArrayOption($ml, $m)) {
                $trueCount+=1;
                }
            }

            if ($trueCount == sizeof($match)) {
                return true;
            }
            // if (inArrayOption($match[0], $m) && inArrayOption($match[1], $m)) {
            //     return true;
            // }
        } 

        return false;
    }

    function generalInArray($arr, $elem) {
        if (is_numeric($elem)) {
            return in_array($elem, $arr);
        }

        return inArrayOption($arr, $elem, $isGeneral=true);
    }

    function inArrayOption($options, $option, $isGeneral=false) {
        foreach($options as $op) {
            if ($option->fixture == $op->fixture) {
                return true;
            }
        }

        return false;
    }

    function getCombinationLength($len, $numCombination) {
        return (permutate($len))/(permutate($numCombination) * permutate($len - $numCombination));
    }

    function permutate($num) {
        $result = 1;
        for($i = $num; $num > 1; $num--) {
            $result*=$num;
        }

        return $result;
    }

    function quadraticSolToNthTerm($a, $d, $s) {
        return (-(2*$a - $d) + sqrt(pow((2*$a - $d), 2) + (8*$d*$s)))/2*$d;
    }

    function sumOfAP($a, $n, $d) {
        $sum = (2*$a + ($n-1)*$d)*($n/2);
        return $sum;
    }


?>