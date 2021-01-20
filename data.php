<?php
    function jsonResponse($responseData, $status) {
        header('Content-Type: application/json');
        header("HTTP/1.0 " . $status . " ");
        echo json_encode($responseData);
        exit;
    }

    function getData($returnFixtures=false) {
        $GG = "GG";
        $NG = "NG";
        $over1 = "over1";
        $under1 = "under1";
        $over3 = "over3";
        $under3 = "under3";

        $file = file_get_contents(__DIR__ . '/../investment/saved-e-content.json');
        $time = file_get_contents(__DIR__ . '/../investment/e-time.txt');

        $nowTime = explode(':',date("H:i"));
        $hr = (string)((int)$nowTime[0] + 1);
        $hr = strlen($hr) == 1 ? '0' . $hr : $hr;
        $nowTime = $hr . ':' . $nowTime[1];

        if ($time && $time > $nowTime) {
            echo 'So what?';
            $fixtures = json_decode($file)->data;
        } else {
            $jsonData = file_get_contents('https://bet-odds.herokuapp.com/zoom-fixtures?country=england');

            $fixtures = json_decode($jsonData)->data;

            file_put_contents(__DIR__ . '/../investment/saved-e-content.json', $jsonData);
            file_put_contents(__DIR__ . '/../investment/e-time.txt', json_decode($jsonData)->time);
        }

        $data = array();
        $pdo = getPDOConnection();
    
        foreach($fixtures as $fixture) {
            $sql = 'SELECT * FROM fixtures INNER JOIN odds ON fixtures.id = odds.fixtures_id INNER JOIN results ON fixtures.id = results.fixtures_id WHERE fixtures.home =' . "'" . $fixture->home . "'" . ' AND fixtures.away = ' . "'" . $fixture->away . "'" . ' OR fixtures.home = ' . "'" . $fixture->away . "'" . ' AND fixtures.away = ' . "'" . $fixture->home . "'";
    
            try {
                $datum = $pdo->query($sql)->fetchAll();
                for($i = 0; $i < sizeof($datum); $i++) {
                    if ($datum[$i]['home'] == $fixture->home && $datum[$i]['away'] == $fixture->away) {
                        $datum[$i]['GG'] = $fixture->odds->$GG;
                        $datum[$i]['NG'] = $fixture->odds->$NG;
                        $datum[$i]['over1'] = $fixture->odds->$over1;
                        $datum[$i]['under1'] = $fixture->odds->$under1;
                        $datum[$i]['over3'] = $fixture->odds->$over3;
                        $datum[$i]['under3'] = $fixture->odds->$under3;
                    }
                }
                $datum = array('stat' => $datum, 'fixture' => $fixture->home . ' - ' . $fixture->away);
                // $datum['fixture'] = $fixture->home . ' - ' . $fixture->away;
            } catch(Exception $e) {
                jsonResponse(['message' => 'Server error', 'success' => false], 500);
            }

            array_push($data, $datum);
            // $data[$fixture->home . ' - ' . $fixture->away] = $datum;
        }

        return $data;
    }
?>
