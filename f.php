<?php 
    var_dump(rand(1000000000,9999999999)); exit;
   include 'Option.php';
   include 'data.php';
   include 'Statistics.php';
   include 'Game.php';

   $data = json_decode(file_get_contents('data.json'))->data;
   // var_dump(array_slice([1,2,3,4], 2)); exit;

//    $r = new Statistics($data);
//    $r->loadAllStat();

//    var_dump($r->stat);

    function getDynamicMatchesCombination($matches, $numCombination) {
        if ($numCombination == 2) {
            return getMatchesCombination($matches, $numCombination);
        }

        $rangStartEnd = sizeof($matches) - ($numCombination -1);
        $rangStartValues = array();
        $fResults = [];

        for($i = 1; $i <= $rangStartEnd; $i++) {
            $results = getDynamicMatchesCombination(array_slice($matches, $i), $numCombination-1);
           // var_dump($results);
            //var_dump(array_unshift($results[0], $i)); exit;
            foreach($results as $result) {
                array_unshift($result, $matches[$i-1]);
                array_push($fResults, $result);
            }

           // var_dump($fResults); exit;
            
        }

        return $fResults;
    }

    function getMatchesCombination($matches, $numCombination) {
        $resultArrLen = getCombinationLength(sizeof($matches), $numCombination);
        $results = [];
        $a =  sizeof($matches) - 1; // First term of the sequence(the number of index e.g for 4 element 1,1,1,2,2,3 => 3, 2, 1 AP)
        $d = -1;
        for($i = 1; $i <= $resultArrLen; $i++) {
            $firstIndex = (int)ceil(quadraticSolToNthTerm($a, $d, $i));
            $secondIndex = $firstIndex == 1 ? ($firstIndex + $i) : ($firstIndex + $i - sumOfAP($a, $firstIndex -1, $d));
            var_dump($firstIndex . ',' . $secondIndex);
            array_push($results, [$matches[$firstIndex-1], $matches[$secondIndex - 1]]);
        }

        return $results;
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

    var_dump(getDynamicMatchesCombination([1,2,3,4,5,6], 3));
    //var_dump(sumOfAP(3, 3, -1));
    // var_dump(quadraticSolToNthTerm(3, -1, 6));
?>