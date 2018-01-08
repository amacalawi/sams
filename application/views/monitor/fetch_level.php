<?php

    foreach ($results as $row)
    {
        $arr[] = array (
        'levelid'=>$row->levels_id,
        'levelname'=>$row->levels_name
        );
    }
    header('Content-Type: application/json');
    echo json_encode( $arr );

?>