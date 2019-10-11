<?php


function registerUser($data,$con){

	$decode = json_decode(json_encode($data), true);

	$sql = "INSERT INTO users(name, relation, email, mobile, sex, dob, blood_group, height, weight, marital_status,occupation)
    VALUES('".$decode['name']."', '".$decode['relation']."', '".$decode['email']."', '".$decode['mobile']."', '".$decode['sex']."', '".$decode['dob']."', '".$decode['blood_group']."', '".$decode['height']."', '".$decode['weight']."', '".$decode['marital_status']."', '".$decode['occupation']."')";
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