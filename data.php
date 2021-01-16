<?php
    function jsonResponse($responseData, $status) {
        header('Content-Type: application/json');
        header("HTTP/1.0 " . $status . " ");
        echo json_encode($responseData);
        exit;
    }

    function getData() {
        $GG = "GG";
        $NG = "NG";
        $fixtures = json_decode(file_get_contents('https://bet-odds.herokuapp.com/zoom-fixtures?country=england'))->data;

        $data = array();
        try {
            $options = [
                PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
            ];
        
            $pdo = new PDO('mysql:host=localhost;port=3306;dbname=wiseinve_zoom', 'wiseinve_investment', 'Iloveodunayo123', $options);
        } catch(Exception $e) {
           mail('pythonboss123@gmail.com', 'Zoom Cron Job Report', 'Database connection error: ' . $e->getMessage());
           exit(0);
        }
    
        foreach($fixtures as $fixture) {
            $sql = 'SELECT * FROM fixtures INNER JOIN odds ON fixtures.id = odds.fixtures_id INNER JOIN results ON fixtures.id = results.fixtures_id WHERE fixtures.home =' . "'" . $fixture->home . "'" . ' AND fixtures.away = ' . "'" . $fixture->away . "'" . ' OR fixtures.home = ' . "'" . $fixture->away . "'" . ' AND fixtures.away = ' . "'" . $fixture->home . "'";
    
            try {
                $datum = $pdo->query($sql)->fetchAll();
                for($i = 0; $i < sizeof($datum); $i++) {
                    $d = $datum[0];
                    if ($d['home'] == $fixture->home && $d['away'] == $fixture->away) {
                        $d['GG'] = $fixture->odds->$GG;
                        $d['NG'] = $fixture->odds->$NG;
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
