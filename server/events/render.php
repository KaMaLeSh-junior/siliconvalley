<?php
include('../events/events.php');
if(isset($_POST) &&  $_POST['data'] =="getall"){
$datas =getalldatas($db);
$photographername=cameramanname($db);
$output=[];
$picdatas='';
$cameraman="";
for ($i=0; $i <$datas->num_rows ; $i++) { 
    $data =$datas->fetch_assoc();
$dots=strlen($data['description'])>10?'...':'';
$imagename =$data["image_name"];
$id=base64_encode($data['id']);
$likes="<span data-id='".$id."' class='heart' data-like='1' >&#x1F90D;</i></span>";
if($data["likes"]==1){  
$likes="<span  data-id='".$id."' data-like='0' class='heart' >&#x1F497;</i></span>";
}
$picdatas.="<div class='card col-sm-2  col-sm-2' style='background-image: url(".'"../assets/images/users/'.$imagename.'"'.");'>
".$likes."
   <div class='section-card'>   
       <div class='left'>
           <div class='description'>&nbsp;".substr($data['description'],0,10)."".$dots."</div>
           <div class='date'>&nbsp;".date('Y-m-d',strtotime($data['createdAT']))."</div>
       </div>
       <div class='right'>
           <div id='cameraman'>-by ".$data['camera_name']." &nbsp;</div>
       </div>
   </div>
</div>" ;
}
for ($i=0; $i <$photographername->num_rows ; $i++) { 
    $data =$photographername->fetch_assoc();
    $cameraman.="<label for='kamalesh' class='dropdown-item'><input type='checkbox' id='selectedcamera' value='".$data['camera_name']."' name='selectcamera'/>&nbsp;".$data['camera_name']."</label>";
}
$output['photographs']=$picdatas;
$output['username']=$cameraman;
echo json_encode($output);
}

    if(isset($_POST) &&  $_POST['data'] =="sorting"){
        $cameraman_details =isset($_POST['cameraman'])?$_POST['cameraman']:"";
        $datas =sortby($db,$_POST['sorting'],$cameraman_details);
        $output='';
        for ($i=0; $i <$datas->num_rows ; $i++) { 
            $data =$datas->fetch_assoc();
        $dots=strlen($data['description'])>10?'...':'';
        $imagename =$data["image_name"];
        $id=base64_encode($data['id']);
        $onclick='add_likes(1,'.$id.')';
        $onclick1='add_likes(0,'.$id.')';
        $likes="<span data-id='".$id."' class='heart' >&#x1F90D;</i></span>";
        if($data["likes"]==1){
        $likes="<span  data-id='".$id."' class='heart' >&#x1F497;</i></span>";
        }
        $output .="<div class='card col-sm-2  col-sm-2' style='background-image: url(".'"../assets/images/users/'.$imagename.'"'.");'>
           <div class='section-card'>   
               <div class='left'>
                   <div class='description'>&nbsp;".substr($data['description'],0,10)."".$dots."</div>
                   <div class='date'>&nbsp;".date('Y-m-d',strtotime($data['createdAT']))."</div>
               </div>
               <div class='right'>
                   <div id='cameraman'>-by ".$data['camera_name']." &nbsp;</div>
               </div>
           </div>
           ".$likes."
        </div>" ;
        
        }
        echo json_encode($output);
        }


?>