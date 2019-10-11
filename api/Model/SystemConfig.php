<?php

function getSchools($con){

	$decode = json_decode(json_encode($data), true);

	$sql = "select * from ";
    if(!mysqli_query($con,$sql))
    {
        $data->error = "Problem while adding record";
    }

	$data->respId = mysqli_insert_id($con);
	
	return $data;
}


function getUser($con){

	$sql = "select * from users";
    if(!mysqli_query($con,$sql))
    {
        $data['error'] = "Problem while adding record";

    }else{

		$result = mysqli_query($con,$sql);
		$data = mysqli_fetch_array($result, MYSQLI_ASSOC);

	}

	return $data;
}

?>