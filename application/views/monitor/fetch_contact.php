<?php

    foreach ($results as $row)
    {
        $arr[] = array (
        'id'=>$row->id,
        'firstname'=>$row->firstname,
        'lastname'=>$row->lastname
        );
    }
    header('Content-Type: application/json');
    echo json_encode( $arr );

?>