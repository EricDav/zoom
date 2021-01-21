<?php 
    function getPDOConnection() {
        try {
            $options = [
                PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
            ];

            $env = file_get_contents(__DIR__ . '/env');

            if ($env == 'dev') {
                $pdo = new PDO('mysql:host=127.0.0.1;port=8889;dbname=zoom', 'root', 'root', $options);
            } else {
                $pdo = new PDO('mysql:host=localhost;port=3306;dbname=wiseinve_zoom', 'wiseinve_investment', 'Iloveodunayo123', $options);
            }

            return $pdo;
        } catch(Exception $e) {
            mail('pythonboss123@gmail.com', 'Zoom Cron Job Report', 'Database connection error: ' . $e->getMessage());
            die('Connection Failed with the following Error: ' . $e->getMessage());
        }
    }


?>