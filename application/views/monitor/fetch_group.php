<?php

    foreach ($results as $row)
    {
        $arr[] = array (
        'groupid'=>$row->groups_id,
        'groupname'=>$row->groups_name
        );
    }
    header('Content-Type: application/json');
    echo json_encode( $arr );

?>