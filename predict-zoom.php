<?php 
   include 'computer-prediction.php';

   $minOdd = $_GET['min_odd'];
   $maxOdd = $_GET['max_odd'];
   $minMatches = $_GET['min_match'];
   $maxMatches = $_GET['max_match'];
   $bestGame = predict($minOdd, $maxOdd, $minMatches, $maxMatches);

   if (!$bestGame) {
    jsonResponse(array('success' => false, 'message' => 'Search did not return any game'), 200);
   }

   jsonResponse(array('success' => true, 'data' => $bestGame), 200);
?>
