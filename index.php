<?php
    $subPath = $_SERVER['HTTP_HOST'] == 'localhost:8888' ? '/zoom' : '';
    $url = explode('?', $_SERVER['REQUEST_URI'])[0];

    $home = '1';
    $away = '2';
    $homeWinDraw = '1X';
    $draw = 'X';
    $awayWinDraw = 'X2';
    $anybody = '12';
    $over2 = 'Over 2.5';
    $under2 = 'Under 2.5';

    $competitions = array(
        1 => 'Zoom Premier League',
        2 => 'Zoom Laliga',
    );

    if ($url == '/get-data') {
        include_once 'data.php';
        include_once 'api-get-data.php';
        exit;
    } else if ($url == '/predict-zoom') {
        include_once 'predict-zoom.php';
    } else if ($url == '/predict') {
        include_once 'predict.php';
        exit;
    }


    include_once 'data.php';
    $data = getData();

   // var_dump($data);
?>


<html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <link rel="stylesheet" href="<?=$subPath .  '/css/style.css'?>">
    </head>
    <style>
      td {
        text-transform: capitalize;
      }
      #empty {
          text-align: center;
          display: none;
      }
    </style>
    <body>
        <nav class="uk-navbar-container uk-margin uk-navbar" data-uk-navbar="true">
            <div class="uk-navbar-center">
                <div class="uk-navbar-center-left">
                    <div>
                        <ul class="uk-navbar-nav">
                            <li class="uk-active"><a href="#">Home</a></li>
                        </ul>
                    </div>
                </div>
                <a class="uk-navbar-item uk-logo uk-text-primary" href="#"><span>💰</span>ZOOM<span>💰</span></a>
                <div class="uk-navbar-center-right">
                    <div>
                        <ul class="uk-navbar-nav">
                            <li><a href="#">Results</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        <div class="uk-section uk-section-muted">
            <div id="t-wrapper" class="uk-container">
               
                  <table class="uk-table uk-table-divider uk-table-middle uk-table-justify">
                     <thead>
                        <tr>
                           <!-- <th>Supported Betting Platforms</th> -->
                           <th class="uk-text-success">COMPETITIONS</th>
                           <!-- <th class="uk-text-emphasis">AMOUNT TO INVEST</th> -->
                           <!-- <th class="uk-text-danger">LOOSE OODS</th> -->
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <td>
                               <select id="competition" type="text" class="uk-input">
                                   <option value="0">-- Select Competition --</option>
                                   <?php foreach($competitions as $competionId => $competition ): ?>
                                    <option value="<?=$competionId?>"><?=$competition?></option>
                                   <?php endforeach; ?>
                                <select>
                            </td>
                           <!-- <td><input id="amount" type="text" class="uk-input" placeholder="0"></td> -->
                        </tr>
                        
                     </tbody>
                  </table>
                  
                  <button id="submit" type="button" class="uk-button uk-button-secondary uk-width-1-1 uk-margin-small-bottom">Submit</button>
                <div class="accordion" id="accordionExample">
                <?php $counter = 0; ?>
                <?php foreach($data as $d): ?>
            
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="<?="headingOne" . $counter ?>">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            <td><?=$d['fixture']?></td>
                        </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                        <table class="uk-table uk-table-divider uk-table-middle uk-table-justify">
                            <thead>
                                <tr>
                                    <th class="">Fixture</th>
                                    <th class="">1</th>
                                    <th class="">X</th>
                                    <th class="">2</th>
                                    <th class="">1x</th>
                                    <th class="">12</th>
                                    <th class="">X2</th>
                                    <th class="">Over 2.5</th>
                                    <th class="">Under 2.5</th>
                                    <th class="">HT Score</th>
                                    <th class="">FT Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($d['stat'] as $stat): ?>
                                    <tr>
                                        <td><?=$stat['home'] . ' - ' . $stat['away']?></td>
                                        <td><?=$stat[$home]?></td>
                                        <td><?=$stat[$draw]?></td>
                                        <td><?=$stat[$away]?></td>
                                        <td><?=$stat[$homeWinDraw]?></td>
                                        <td><?=$stat[$anybody]?></td>
                                        <td><?=$stat[$awayWinDraw]?></td>
                                        <td><?=$stat[$over2]?></td>
                                        <td><?=$stat[$under2]?></td>
                                        <td><?=$stat['ht_score']?></td>
                                        <td><?=$stat['ft_score']?></td>
                                    </tr>
                                <?php endforeach; ?>
                                
                            </tbody>
                        </table>
                        </div>
                        </div>
                    </div>
                    <?php $counter+=1; ?>
                    
                    <?php endforeach; ?>

                </div>
            </div>
            
         </div>
         <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
        <script src="<?=$subPath .  '/js/script.js?v=4'?>"></script>
    </body>
</html>
