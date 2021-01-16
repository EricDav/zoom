<?php 

    $data  = getData();
    jsonResponse(array('data' => $data, 'success' => true), 200);
    

?>
