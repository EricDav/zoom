<?php
    $subPath = $_SERVER['HTTP_HOST'] == 'localhost:8888' ? '/riskless' : '';
    $url = explode('?', $_SERVER['REQUEST_URI'])[0];
    include_once 'data.php';

    $competitions = array(
        1 => 'Zoom Premier League',
        2 => 'Zoom Laliga',
    );

    if ($url == '/get-data') {
        include_once 'api-get-data.php';
        exit;
    }

    // $data = json_decode(file_get_contents('data.json'));

    // foreach($data as $d) {
    //     $fixt = 'Z.Brighton - Z.Wolves';
    //     var_dump($d->$fixt);
    //     exit;
    // }

    // exit;

    // var_dump($data); exit;


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

                <?php foreach($data as $d): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Accordion Item #1
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
                                <tr>
                                    <td>Z.Brighton - Wolves</td>
                                    <td>2.52</td>
                                    <td>2.52</td>
                                    <td>2.52</td>
                                    <td>2.52</td>
                                    <td>2.52</td>
                                    <td>2.52</td>
                                    <td>1.23</td>
                                    <td>2.35</td>
                                    <td>1-0</td>
                                    <td>2-3</td>
                                </tr>
                                
                            </tbody>
                        </table>
                        </div>
                        </div>
                    </div>
                    <?php endforeach; ?>

                </div>
            </div>
            
         </div>
         <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
        <script src="<?=$subPath .  '/js/script.js?v=4'?>"></script>
    </body>
</html>
