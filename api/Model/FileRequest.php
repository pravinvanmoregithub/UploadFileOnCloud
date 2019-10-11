<?php

use Aws\AwsS3;

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;


require 'aws-autoloader.php';

include("resize-class.php");

function saveImageOnAws($FileUrl,$FileWidth,$FileHeight){

    $resP = array();

    if (!file_exists("/tmp")) {
        mkdir("/tmp");
    }
            
    $tempFilePath = '/tmp/' . basename($FileUrl);

    $tempFile = fopen($tempFilePath, "w") or die("Error: Unable to open file.");

    $fileContents = file_get_contents($FileUrl);

	$tempFile = file_put_contents($tempFilePath, $fileContents);

    // *** 1) Initialise / load image
    $resizeObj = new resize($tempFilePath);

    // *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
    $resizeObj -> resizeImage($FileWidth,$FileHeight, 'crop');

    $ResizefileTempName = '/tmp/' .basename($FileUrl). '-resizeda.jpg';

    // *** 3) Save image
    $resizeObj -> saveImage($ResizefileTempName, 1000);

    $bucket = 'khelfit';

    $keyname = 'upload/' . basename($FileUrl);

    $pathInS3 = 'https://s3.us-east-2.amazonaws.com/' . $bucket . '/' . $keyname;

    // Instantiate the client.
    $s3 = S3Client::factory(array(
        'region' => 'ap-south-1',
        'version' => 'latest',
        'credentials' => array(
            'key' => 'AKIAJEK2A4IAXYQGCOPQ',
            'secret'  => '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',
        ),
        'options' => array(
            'scheme' => 'http',
        )
    ));

    try {
            // Upload data.
        $result = $s3->putObject(array(
            'Bucket' => $bucket,
            'Key'    => $keyname,
            'SourceFile'   => $ResizefileTempName,
            'ACL'    => 'public-read'
        ));

        $resP['upload'] = true;
        $resP['filename'] = basename($FileUrl);

    } catch (S3Exception $e) {

        $resP['upload'] = false;

    }

    return $resP;              

    
}

function UploadFile($con,$data){

    $dataDec = json_decode(json_encode($data), true);

    $exception = array();
    
    if(isset($dataDec['FileUrl']) && $dataDec['FileUrl'] != ''){

        $FileUrl = $dataDec['FileUrl'];

    }else{

        $exception['error'] = "File width is missing";

        return $exception;
    }

    if(isset($dataDec['FileWidth']) && $dataDec['FileWidth'] != ''){

        $FileWidth = $dataDec['FileWidth'];

    }else{

        $exception['error'] = "File width is missing";

        return $exception;
    }

    if(isset($dataDec['FileHeight']) && $dataDec['FileHeight'] != ''){

        $FileHeight = $dataDec['FileHeight'];

    }else{

        $exception['error'] = "File height is missing";

        return $exception;
    }

    $ImageRespAws = saveImageOnAws($dataDec['FileUrl'],$FileWidth,$FileHeight);

    $FileDestination = 'http://khelfit.s3.amazonaws.com/upload/'.$ImageRespAws['filename'];

    $FileUploadedOn = date('Y-m-d H:i:s');

    if(isset($ImageRespAws['upload']) && $ImageRespAws['upload']){

        $sql = "INSERT INTO uploadfiles(Filesource,FileDestination,FileWidth,FileHeight,FileUploadedOn) VALUES('".$FileUrl."', '".$FileDestination."', $FileWidth, $FileHeight,'".$FileUploadedOn."')";

        if(!mysqli_query($con,$sql))
        {
            $exception['error'] = "Problem while adding authorized user";
            
            return $exception;
            
        }else{

            $exception['success'] = "Image uploaded successfully";
            $exception['ImageUrl'] = $FileDestination;
        }
                
    }else{

        $exception['error'] = "Problem while uploading the file";

        return $exception;
    }

    return $exception;
}

?>
