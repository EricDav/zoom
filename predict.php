<?php
    $matches = array(1,2,3,4,5,6,7,8,9,10);
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
                            <li class="uk-active"><a href="/">Statistics</a></li>
                        </ul>
                    </div>
                </div>
                <a class="uk-navbar-item uk-logo uk-text-primary" href="#"><span>💰</span>ZOOM<span>💰</span></a>
                <div class="uk-navbar-center-right">
                    <div>
                        <ul class="uk-navbar-nav">
                            <li><a href="/predict">Predictions</a></li>
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
                           <th class="uk-text-success">ODDS</th>
                           <th class="uk-text-emphasis">MIN MATCHES</th>
                           <th class="uk-text-success">MAX MATCHES</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <td><input id="odd" type="text" class="uk-input" placeholder="Enter odds to genaret"></td>
                           <td>
                                <select id="min-match" type="text" class="uk-input">
                                <option value="0"> --USE DEFAULT--</option>
                                <?php foreach($matches as $match ): ?>
                                    <option value="<?=$match?>"><?=$match?></option>
                                <?php endforeach; ?>
                                <select>
                           </td>
                           <td>
                                <select id="max-match" type="text" class="uk-input">
                                <option value="0"> --USE DEFAULT-- </option>
                                <?php foreach($matches as $match ): ?>
                                    <option value="<?=$match?>"><?=$match?></option>
                                <?php endforeach; ?>
                                <select>
                           </td>
                        </tr>
                     </tbody>
                  </table>
                  
                  <button id="predict-submit" type="button" class="uk-button uk-button-secondary uk-width-1-1 uk-margin-small-bottom">Submit</button>
            </div>
            
         </div>
         <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
        <script src="<?=$subPath .  '/js/script.js?v=2'?>"></script>
    </body>
</html>