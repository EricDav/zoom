<?php 
    include 'computer-prediction.php';
    $pdo = getPDOConnection();
    $user = ['id' => 1];
    $pdo->query('INSERT INTO reports (user_id, betslip_id, date_played, game_begins) VALUES (' . $user['id'] . ',' . "'" . $result->data . "'" . ',' . "'" . gmdate('Y-m-d H:i:s') . "'" . ",'" . '02:34'. "')");
    mail('pythonboss123@gmail.com', 'Zoom Automate Game Report', 'I got here at ' .  gmdate('Y-m-d H:i:s'));
    exit;
    // $time = file_get_contents('last');
    // echo date("H:i");
    // var_dump(gmdate("Y/m/d"));
    // var_dump(DateTime::createFromFormat('Y/m/d H:i', gmdate("Y/m/d") . ' 08:00'));
    // exit;


    playGame();

    function play($username, $password, $minOdd, $amount, $email) {
        $data = getPredictionPostData($minOdd);
        if (!$data) {
            die('Did not find best game');
            mail($email, 'Zoom Automate Game Report', 'Did not find adequate game for this round at ' .  gmdate('Y-m-d H:i:s'));
        }

        if (file_get_contents('last-time.txt') == file_get_contents('last.txt')) {
            die('Game already played');
        }

        $predictionDataJson = json_encode($data);
        // var_dump($predictionDataJson);
        $bookingCodeDetails = fetchBookingCode(['data' => $predictionDataJson]);
        // var_dump($bookingCodeDetails); exit;

        $url = 'https://bet-odds.herokuapp.com/play?bookingCode=' . $bookingCodeDetails->data->bookingCode . '&username=' . $username . '&password=' . $password . '&amount=' . $amount;
        $result = file_get_contents($url);

        if ($result && json_decode($result)->success) {
            file_put_contents('last.txt', file_get_contents('last-time.txt'));
        }


        return $result;
    }

    function addTime($time) {
       // $minHr = 
    }


    function playGame() {
        $pdo = getPDOConnection();
        $users = $pdo->query('SELECT * FROM users WHERE id=1')->fetchAll();
        foreach($users as $user) {

            for ($i = 0; $i < 3; $i++) {
                $result = play($user['username'], $user['password'], $user['min_odds'], $user['amount'], $user['email']);
                if (!$result) {
                    continue;
                }

                break;
            }

            $result = json_decode($result);
            
            if (!$result->success) {
                mail($email, 'Zoom Automate Game Report', 'Error encountered trying to play game, it might be wrong password!' .  gmdate('Y-m-d H:i:s'));
                echo "Error encountered trying to play game, it might be wrong password!";
            } else {
                $pdo->query('INSERT INTO reports (user_id, betslip_id, date_played, game_begins) VALUES (' . $user['id'] . ',' . "'" . $result->data . "'" . ',' . "'" . gmdate('Y-m-d H:i:s') . "'" . ",'" . file_get_contents('last.txt'). "')");
                mail($users['email'], 'Zoom Automate Game Report', 'Game successfully played for this round at ' .  gmdate('Y-m-d H:i:s'));
                echo "Success playing";
            };
        }

    }

    function getNowTime() {
        $nowTime = explode(':',gmdate("H:i"));
        $hr = (string)((int)$nowTime[0] + 1);
        $hr = strlen($hr) == 1 ? '0' . $hr : $hr;
        $nowTime = $hr . ':' . $nowTime[1];

        return $nowTime;
    }

    function getPredictionPostData($minOdd) {
        $file = file_get_contents(__DIR__ . '/../investment/saved-e-content.json');
        $time = file_get_contents(__DIR__ . '/../investment/e-time.txt');
    
        $nowTime = getNowTime();
    
        if ($time > $nowTime) {
            $dataObj = json_decode($file);
            $fixtures = $dataObj->data;
            file_put_contents('last-time.txt',$dataObj->time);
        } else {
            $dataObj = getFixturesFromHeroku();
            $fixtures = $dataObj->data;

            file_put_contents(__DIR__ . '/../investment/saved-e-content.json', json_encode($dataObj));
            file_put_contents(__DIR__ . '/../investment/e-time.txt', $dataObj->time);
            file_put_contents('last-time.txt',$dataObj->time);
        }

    
        $bestGame = predict($minOdd, 5, 2, 7, 0.5);

        if (!$bestGame || sizeof($bestGame) == 0) {
            return null;
        }

        $postData = array();
        foreach($bestGame->options as $option) {
            $homeAway = explode(' - ', $option->fixture);
            $index = -1;
            for($i = 0; $i < sizeof($fixtures); $i++) {
                $fixture = $fixtures[$i];
                if ($fixture->home == $homeAway[0] && $fixture->away == $homeAway[1]) {
                    $index = $i;
                    break;
                }
            }
    
            array_push($postData, array('fixture' => $option->fixture, 'index' => $index, 'outcome' => $option->name));
        }

        return $postData;
    }


    /**
     * Try fetching fixtures from server. Try up to 
     * 5 times if fails
     */
    function getFixturesFromHeroku() {
        for ($i = 0;  $i < 5; $i++) {
            $jsonData = file_get_contents('https://bet-odds.herokuapp.com/zoom-fixtures?country=england');
            // var_dump($jsonData);

            if (!$jsonData) {
                continue;
            }

            $fixtures = json_decode($jsonData)->data;
            if ($fixtures) {
                return json_decode($jsonData);
            }
        }

        return null;
    }


    function fetchBookingCode($fields) {
        $url = 'https://bet-odds.herokuapp.com/play-code';
        $fields_string = http_build_query($fields);

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 

        //execute post
        $result = curl_exec($ch);
        return json_decode($result);
    }

    function formatTimeToWAT() {

    }

    function validateBookingCode($codeData, $options) {

        if (sizeof($codeData->fixtures) != sizeof($options)) {
            return false;
        }

        for($i = 0; $i < sizeof($options); $i++) {
           //  $homeAway =explode(' - ', $codeData->fixtures[$i]);

            if ($codeData->fixtures[$i] == $options[$i]->fixture) {
                continue;
            } else {
                return false;
            }
        }

        return true;
    }
?>