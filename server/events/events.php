<?php 
include('../db/connect.php');

function selecionquery($db,$userdeatils,$photodetails)
{
    $Photographer =htmlspecialchars($userdeatils['Photographer']);
    $aboutphoto =htmlspecialchars($userdeatils['aboutphoto']);
    $insertValuesSQL=$errorUpload=$errorUploadType="";
    $targetDir ="../../assets/images/users/"; 
    $allowTypes = array('jpg','png','jpeg','gif'); 
    $files='';
    $fileNames = array_filter($photodetails['photoupload']['name']);
    if(empty($fileNames)){
        $insertValuesSQL.="',' '"."NOW()".")";
    }
    if(!empty($fileNames)){
        foreach($fileNames as $key=>$val){ 
            $fileName = basename($fileNames[$key]); 
            $targetFilePath = $targetDir . $fileName; 
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
            if(in_array($fileType, $allowTypes)){ 
                if(move_uploaded_file($_FILES["photoupload"]["tmp_name"][$key], $targetFilePath)){ 
                    $insertValuesSQL .= "('".$Photographer."','".$aboutphoto."','".$fileName."', NOW()),"; 
                }else{ 
                    $errorUpload .= $fileNames[$key].' | '; 
                } 
            }else{ 
                $errorUploadType .= $fileNames[$key].' | '; 
            } 
        } 
    $errorUpload = !empty($errorUpload)?'Upload Error: '.trim($errorUpload, ' | '):''; 
    $errorUploadType = !empty($errorUploadType)?'File Type Error: '.trim($errorUploadType, ' | '):''; 
    $errorMsg = !empty($errorUpload)?'<br/>'.$errorUpload.'<br/>'.$errorUploadType:'<br/>'.$errorUploadType; 
     
    if(!empty($insertValuesSQL)){ 
        $insertValuesSQL = trim($insertValuesSQL, ','); 
        $insert = $db->query("INSERT INTO photos (camera_name,description,image_name,createdAT) VALUES $insertValuesSQL"); 
        if($insert){ 
            $statusMsg=200; 
        }else{ 
            $statusMsg = "Sorry, there was an error uploading your file."; 
        } 
    }else{ 
        $statusMsg = "Upload failed! ".$errorMsg; 
    } 
}else{ 
    $statusMsg = 'Please select a file to upload.'; 
} 
echo $statusMsg;
}

function cameramanname($db)
{
    $query = $db->query("SELECT DISTINCT camera_name FROM photos");
    return $query;
}
    
function getalldatas($db){
    $query = $db->query("SELECT * FROM `photos`");
    return $query;
}

function sortby($db,$sorting,$usernames=""){
    if($sorting=="namesort"){
        $query = $db->query("SELECT * FROM `photos` ORDER BY camera_name ASC");
    }else if($sorting=="newimages"){
        $query = $db->query("SELECT * FROM `photos` ORDER BY createdAT  DESC");

    }else if($sorting=="oldimages"){
        $query = $db->query("SELECT * FROM `photos` ORDER BY createdAT  ASC");
    }else if($sorting=="username"){
        if($usernames=="'all'"){
            $query = $db->query("SELECT * FROM `photos`");

        }else{
            $query=$db->query("SELECT * FROM `photos` where camera_name in ($usernames)");
        }
    }else{
    $query = $db->query("SELECT * FROM `photos`");
    }
    return $query;
}

function addlikes($db,$likes,$id){
    $id_update=base64_decode($id);
    $conn = $db->query("UPDATE photos SET likes =$likes WHERE id =$id_update");
}

// FUNCTION CALLING
if(isset($_POST['likes']) && $_POST['data']=="like"){
    addlikes($db,$_POST['likes'],$_POST['id']);
}

if(isset($_POST['uploading'])){
    selecionquery($db,$_POST,$_FILES);
}
