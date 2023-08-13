<?php
  session_start();
	include("config.php");

  if(!isset($_SESSION['user'])){
    session_destroy();
    header("location: login.php");
  }

  //query for session
  $user_check=$_SESSION['user'];
  $sql="SELECT * FROM users WHERE username='$user_check'";
  $stmt=$conn->query($sql);
  if(!$row=$stmt->fetch(PDO::FETCH_ASSOC) || $stmt->rowCount()<1){
    session_destroy();
    header("location: login.php");
  }

  
  $user_check=$_SESSION['user'];
  $sql="SELECT * FROM users WHERE username='$user_check'";
  $stmt=$conn->query($sql);
  $row=$stmt->fetch(PDO::FETCH_ASSOC);
  $login_user=$row['first_name']." ".$row['last_name'];
  $login_access=$row['account_type'];

  //Add Character
  if(isset($_POST['submit_add_character'])){
    $basedir="uploads/img/char/";
    //Check if basedir exists
    if(file_exists($basedir)){
      $uploadOk = 1;
      $uploadFailedMessage="Character not added! ";

      //Upload Character Image
      $charImageName = basename($_FILES["inp_character_image"]["name"]);
      $targetCharImgLoc = $basedir.basename($_FILES["inp_character_image"]["name"]);

      //Check if image is Undefined /  Multiple Files / Corrupt
      if (!isset($_FILES['inp_character_image']['error']) || is_array($_FILES['inp_character_image']['error'])) {
        $uploadFailedMessage.="Invalid Image Parameters! ";
        $uploadOk = 0;
      }

      //Check if it's an actual image
      $finfo = new finfo(FILEINFO_MIME_TYPE);
      if (false === $ext = array_search( 
        $finfo->file($_FILES["inp_character_image"]["tmp_name"]), 
        array(
            'jpg' => 'image/jpg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png'
        ), true )) {
          $uploadFailedMessage.="Only genuine JPG, JPEG, & PNG image types are allowed! ";
          $uploadOk = 0;
      }

      // Check file size (Max: 10MB | 10485760 Bytes)
      if ($_FILES["inp_character_image"]["size"] > 10485760) {
        $uploadFailedMessage.="Image file is too large! (Max: 10MB) ";
        $uploadOk = 0;
      }

      //Upload Character Thumbnail
      $charThumbnailName = basename($_FILES["inp_character_thumbnail"]["name"]);
      $targetCharThumbLoc = $basedir.basename($_FILES["inp_character_thumbnail"]["name"]);

      //Check if image is Undefined /  Multiple Files / Corrupt
      if (!isset($_FILES['inp_character_thumbnail']['error']) || is_array($_FILES['inp_character_thumbnail']['error'])) {
        $uploadFailedMessage.="Invalid Image Parameters! ";
        $uploadOk = 0;
      }

      //Check if it's an actual image
      $finfo = new finfo(FILEINFO_MIME_TYPE);
      if (false === $ext = array_search( 
        $finfo->file($_FILES["inp_character_thumbnail"]["tmp_name"]), 
        array(
            'jpg' => 'image/jpg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png'
        ), true )) {
          $uploadFailedMessage.="Only genuine JPG, JPEG, & PNG image types are allowed! ";
          $uploadOk = 0;
      }

      // Check file size (Max: 10MB | 10485760 Bytes)
      if ($_FILES["inp_character_thumbnail"]["size"] > 10485760) {
        $uploadFailedMessage.="Image file is too large! (Max: 10MB) ";
        $uploadOk = 0;
      }

      // Check upload
      if ($uploadOk == 0) {
        echo "<script> window.location.href = 'p3-characters.php'; alert('".$uploadFailedMessage."'); </script>";
      } 
      else {
        $duplicateMsg="Adding Character Failed! ";
        $duplicateCheck=1;

        $dupCharNameCheck = str_replace('\'','’',$_POST['inp_character_name']);

        $sql="SELECT * FROM characters";
        $resultCharacter=$conn->query($sql);
        while($rowCharacter = $resultCharacter->fetch(PDO::FETCH_ASSOC)){
          if (preg_replace('/\s+/', '', strtolower($rowCharacter['character_name'])) == preg_replace('/\s+/', '', strtolower($dupCharNameCheck))){
            $duplicateCheck=0;
            $duplicateMsg.="Character Name Already Exists! ";
            break;
          }
        }

        // $sql="SELECT * FROM characters WHERE character_name = '{$dupCharNameCheck}'";
        // $resultCharacter=$conn->query($sql);
        // $resultCharacter->fetch(PDO::FETCH_ASSOC);
        // if($resultCharacter->rowCount()>0){
        //   $duplicateCheck=0;
        //   $duplicateMsg.="Character Name Already Exists! ";
        // }

        if(file_exists($targetCharImgLoc)){
          $duplicateCheck=0;
          $duplicateMsg.="Character Image Already Exists! ";
        }
        if(file_exists($targetCharThumbLoc)){
          $duplicateCheck=0;
          $duplicateMsg.="Character Thumbnail Already Exists! ";
        }
        if($targetCharImgLoc == $targetCharThumbLoc){
          $duplicateCheck=0;
          $duplicateMsg.="Character Image and Character Thumbnail CANNOT Have Same Filename! ";
        }

        if($duplicateCheck==1){
          if (move_uploaded_file($_FILES["inp_character_image"]["tmp_name"], $targetCharImgLoc) && move_uploaded_file($_FILES["inp_character_thumbnail"]["tmp_name"], $targetCharThumbLoc)) {

            //Move Order
            $rowCountOrder=0;
            $sql="SELECT * FROM characters ORDER BY character_order";
            $resultCharacter=$conn->query($sql);
            while($rowCharacter=$resultCharacter->fetch(PDO::FETCH_ASSOC)){
              $rowCountOrder += 1;
              $rowCharId = $rowCharacter['character_id'];
              $rowCharOrder = $rowCharacter['character_order'];
              if($rowCharOrder < $_POST['inp_character_order']){
                $sql="UPDATE characters SET character_order =:charOrder WHERE character_id=:charId";
                $stmt=$conn->prepare($sql);
                $stmt->execute([':charOrder'=>$rowCountOrder,
                                ':charId'=>$rowCharId
                              ]);
              }
              else{
                $sql="UPDATE characters SET character_order =:charOrder WHERE character_id=:charId";
                $stmt=$conn->prepare($sql);
                $stmt->execute([':charOrder'=>$rowCountOrder+1,
                                ':charId'=>$rowCharId
                              ]);
              }
              
            }
        
            //Add Character to table
            $sql="INSERT INTO characters (character_order, character_name, character_info, character_image, character_image_location, character_thumbnail, character_thumbnail_location) 
            VALUES (:charOrder, :charName, :charInfo, :charImage, :charImageLoc, :charThumbnail, :charThumbnailLoc)";
            $stmt=$conn->prepare($sql);
            $stmt->execute([':charOrder'=>$_POST['inp_character_order'],
                            ':charName'=>str_replace('\'','’',$_POST['inp_character_name']),
                            ':charInfo'=>str_replace('\'','’',$_POST['inp_character_info']),
                            ':charImage'=>$_FILES["inp_character_image"]["name"],
                            ':charImageLoc'=>$targetCharImgLoc,
                            ':charThumbnail'=>$_FILES["inp_character_thumbnail"]["name"],
                            ':charThumbnailLoc'=>$targetCharThumbLoc,
                          ]);
            echo "<script> window.location.href = 'p3-characters.php'; alert('Character Added Successfully.'); </script>";
          } 
          else {
            echo "<script> window.location.href = 'p3-characters.php'; alert('There was an error in uploading the files.'); </script>";
          }
        }
        else{
          echo "<script> window.location.href = 'p3-characters.php'; alert('".$duplicateMsg."'); </script>";
        }

      }
    }
    else{
      echo "<script> window.location.href = 'p3-characters.php'; alert('ERROR: BASE DIRECTORY CANNOT BE FOUND!'); </script>";
    }
   
  }

  //Edit Character
  if(isset($_POST['submit_edit_character'])){

    $basedir="uploads/img/char/";
    //Check if basedir exists
    if(file_exists($basedir)){

      $uploadImageExist = 1;
      $uploadThumbnailExist = 1;
      $uploadOk = 1;
      $uploadFailedMessage="Character not added! ";

      $originalCharImageLoc = $_POST['upd_char_image_loc'];
      $originalCharThumbnailLoc = $_POST['upd_char_thumbnail_loc'];

      //Check if original files exist
      $charfileCheck = 1;
      $charfileMsg = "Character not added! ";
      if(!file_exists($originalCharImageLoc)){
        $charfileCheck = 0;
        $charfileMsg .= "Original character Image CANNOT Be Found. ";
      }
      if(!file_exists($originalCharThumbnailLoc)){
        $charfileCheck = 0;
        $charfileMsg .= "Original character Thumbnail CANNOT Be Found. ";
      }
      if($charfileCheck == 1){
        //Character Image Loc
        $charImageName = basename($originalCharImageLoc);
        $targetCharImgLoc = $originalCharImageLoc = $_POST['upd_char_image_loc'];

        //Character Thumbnail Loc
        $charThumbnailName = basename($originalCharThumbnailLoc);
        $targetCharThumbLoc =$originalCharThumbnailLoc = $_POST['upd_char_thumbnail_loc'];

        //Check if there's an uploaded Image
        if(file_exists($_FILES['upd_character_image']['tmp_name']) || is_uploaded_file($_FILES['upd_character_image']['tmp_name'])) {
          $uploadImageExist = 1;

          //Check if image is Undefined /  Multiple Files / Corrupt
          if (!isset($_FILES['upd_character_image']['error']) || is_array($_FILES['upd_character_image']['error'])) {
            $uploadFailedMessage.="Invalid Image Parameters! ";
            $uploadOk = 0;
          }

          //Check if it's an actual image
          $finfo = new finfo(FILEINFO_MIME_TYPE);
          if (false === $ext = array_search( 
            $finfo->file($_FILES["upd_character_image"]["tmp_name"]), 
            array(
                'jpg' => 'image/jpg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png'
            ), true )) {
              $uploadFailedMessage.="Only genuine JPG, JPEG, & PNG image types are allowed! ";
              $uploadOk = 0;
          }

          // Check file size (Max: 10MB | 10485760 Bytes)
          if ($_FILES["upd_character_image"]["size"] > 10485760) {
            $uploadFailedMessage.="Image file is too large! (Max: 10MB) ";
            $uploadOk = 0;
          }

          //Update Image if pass
          if($uploadOk ==  1){
            $charImageName = basename($_FILES["upd_character_image"]["name"]);
            $targetCharImgLoc = $basedir.basename($_FILES["upd_character_image"]["name"]);
          }
        }
        else{
          $uploadImageExist = 0;
        }  

        //Check if there's an uploaded Thumbnail
        if (file_exists($_FILES['upd_character_thumbnail']['tmp_name']) || is_uploaded_file($_FILES['upd_character_thumbnail']['tmp_name'])) {
          $uploadThumbnailExist = 1;

          //Check if image is Undefined /  Multiple Files / Corrupt
          if (!isset($_FILES['upd_character_thumbnail']['error']) || is_array($_FILES['upd_character_thumbnail']['error'])) {
            $uploadFailedMessage.="Invalid Image Parameters! ";
            $uploadOk = 0;
          }

          //Check if it's an actual image
          $finfo = new finfo(FILEINFO_MIME_TYPE);
          if (false === $ext = array_search( 
            $finfo->file($_FILES["upd_character_thumbnail"]["tmp_name"]), 
            array(
                'jpg' => 'image/jpg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png'
            ), true )) {
              $uploadFailedMessage.="Only genuine JPG, JPEG, & PNG image types are allowed! ";
              $uploadOk = 0;
          }

          // Check file size (Max: 10MB | 10485760 Bytes)
          if ($_FILES["upd_character_thumbnail"]["size"] > 10485760) {
            $uploadFailedMessage.="Image file is too large! (Max: 10MB) ";
            $uploadOk = 0;
          }

          //Update thumbnail if pass
          if($uploadOk ==  1){
            $charThumbnailName = basename($_FILES["upd_character_thumbnail"]["name"]);
            $targetCharThumbLoc = $basedir.basename($_FILES["upd_character_thumbnail"]["name"]);
          }
        }
        else{
          $uploadThumbnailExist = 0;
        }

        // Check upload
        if ($uploadOk == 0) {
          echo "<script> window.location.href = 'p3-characters.php'; alert('".$uploadFailedMessage."'); </script>";
        } 
        else {
          $sql2 = "SELECT * FROM characters WHERE character_id = '{$_POST['upd_char_id']}'";
          $resultCharacterCheck=$conn->query($sql2);
          $resultCharacterCheck->fetch(PDO::FETCH_ASSOC);

          //Check if character exists in table
          if($resultCharacterCheck->rowCount()>0){

            if($uploadImageExist == 1 && $uploadThumbnailExist == 1){
              $duplicateMsg="Editing Character Failed! ";
              $duplicateCheck=1;

              $dupCharIdCheck = $_POST['upd_char_id'];
              $dupCharNameCheck = str_replace('\'','’',$_POST['upd_character_name']);
              
              $sql="SELECT * FROM characters WHERE character_id = '{$dupCharIdCheck}'";
              $resultCharacter=$conn->query($sql);
              $rowCharacter = $resultCharacter->fetch(PDO::FETCH_ASSOC);
              $characterPrevName = $rowCharacter['character_name'];

              $sql="SELECT * FROM characters";
              $resultCharacter=$conn->query($sql);
              while($rowCharacter = $resultCharacter->fetch(PDO::FETCH_ASSOC)){
                if ($rowCharacter['character_id'] != $dupCharIdCheck && preg_replace('/\s+/', '', strtolower($rowCharacter['character_name'])) == preg_replace('/\s+/', '', strtolower($dupCharNameCheck))){
                  $duplicateCheck=0;
                  $duplicateMsg.="Character Name Already Exists! ";
                  break;
                }
              }

              // $sql="SELECT * FROM characters WHERE character_name = '{$dupCharNameCheck}'";
              // $resultCharacter=$conn->query($sql);
              // $resultCharacter->fetch(PDO::FETCH_ASSOC);
              // if( ($dupCharNameCheck!=$characterPrevName) && $resultCharacter->rowCount()>0){
              //   $duplicateCheck=0;
              //   $duplicateMsg.="Character Name Already Exists! ";
              // }
              if(file_exists($targetCharImgLoc)){
                $duplicateCheck=0;
                $duplicateMsg.="Character Image Already Exists! ";
              }
              if(file_exists($targetCharThumbLoc)){
                $duplicateCheck=0;
                $duplicateMsg.="Character Thumbnail Already Exists! ";
              }
              if($targetCharImgLoc == $targetCharThumbLoc){
                $duplicateCheck=0;
                $duplicateMsg.="Character Image and Character Thumbnail CANNOT Have Same Filename! ";
              }

              if($duplicateCheck==1){
                if (move_uploaded_file($_FILES["upd_character_image"]["tmp_name"], $targetCharImgLoc) && move_uploaded_file($_FILES["upd_character_thumbnail"]["tmp_name"], $targetCharThumbLoc)) {
              
                  //Remove existing files
                  $removeCheck=1;
                  $removeMsg="Editing character failed. ";
                  if(!unlink($originalCharImageLoc)){
                    $removeCheck=0;
                    $removeMsg.="Failed to remove existing character Image file! ";
                  }
                  if(!unlink($originalCharThumbnailLoc)){
                    $removeCheck=0;
                    $removeMsg.="Failed to remove existing character Thumbnail file! ";
                  }
    
                  if($removeCheck == 1){
                    //Move Order
                    $rowCountOrder=0;
                    $sql="SELECT * FROM characters ORDER BY character_order";
                    $resultCharacter=$conn->query($sql);
                    while($rowCharacter=$resultCharacter->fetch(PDO::FETCH_ASSOC)){
                      $rowCountOrder += 1;
                      $rowCharId = $rowCharacter['character_id'];
                      $rowCharOrder = $rowCharacter['character_order'];
                      if($rowCharOrder < $_POST['upd_character_order']){
                        $sql="UPDATE characters SET character_order =:charOrder WHERE character_id=:charId";
                        $stmt=$conn->prepare($sql);
                        $stmt->execute([':charOrder'=>$rowCountOrder,
                                        ':charId'=>$rowCharId
                                      ]);
                      }
                      else{
                        $sql="UPDATE characters SET character_order =:charOrder WHERE character_id=:charId";
                        $stmt=$conn->prepare($sql);
                        $stmt->execute([':charOrder'=>$rowCountOrder+1,
                                        ':charId'=>$rowCharId
                                      ]);
                      }
                      
                    }

                    //Edit Character
                    $sql="UPDATE characters SET character_name =:charName, character_info =:charInfo, character_order =:charOrder, character_image =:charImage, character_image_location =:charImageLoc, 
                    character_thumbnail =:charThumbnail, character_thumbnail_location=:charThumbnailLoc
                    WHERE character_id=:charId";
                    $stmt=$conn->prepare($sql);
                    $stmt->execute([':charName'=>str_replace('\'','’',$_POST['upd_character_name']),
                                    ':charInfo'=>str_replace('\'','’',$_POST['upd_character_info']),
                                    ':charOrder'=>$_POST['upd_character_order'],
                                    ':charImage'=>$charImageName,
                                    ':charImageLoc'=>$targetCharImgLoc,
                                    ':charThumbnail'=>$charThumbnailName,
                                    ':charThumbnailLoc'=>$targetCharThumbLoc,
                                    ':charId'=>$_POST['upd_char_id']
                                  ]);
                    echo "<script> window.location.href = 'p3-characters.php'; alert('Character Updated Successfully!'); </script>";
                  }
                  else{
                    echo "<script> window.location.href = 'p3-characters.php'; alert('".$removeMsg."'); </script>";
                  }
    
                } 
                else {
                  echo "<script> window.location.href = 'p3-characters.php'; alert('Editing Character Failed! There was an error in uploading the files.'); </script>";
                }
              }
              else{
                echo "<script> window.location.href = 'p3-characters.php'; alert('".$duplicateMsg."'); </script>";
              }
              
            }
            elseif ($uploadImageExist == 0 && $uploadThumbnailExist == 1){
              $duplicateMsg="Editing Character Failed! ";
              $duplicateCheck=1;

              $dupCharIdCheck = $_POST['upd_char_id'];
              $dupCharNameCheck = str_replace('\'','’',$_POST['upd_character_name']);
              
              $sql="SELECT * FROM characters WHERE character_id = '{$dupCharIdCheck}'";
              $resultCharacter=$conn->query($sql);
              $rowCharacter = $resultCharacter->fetch(PDO::FETCH_ASSOC);
              $characterPrevName = $rowCharacter['character_name'];

              $sql="SELECT * FROM characters";
              $resultCharacter=$conn->query($sql);
              while($rowCharacter = $resultCharacter->fetch(PDO::FETCH_ASSOC)){
                if ($rowCharacter['character_id'] != $dupCharIdCheck && preg_replace('/\s+/', '', strtolower($rowCharacter['character_name'])) == preg_replace('/\s+/', '', strtolower($dupCharNameCheck))){
                  $duplicateCheck=0;
                  $duplicateMsg.="Character Name Already Exists! ";
                  break;
                }
              }

              // $sql="SELECT * FROM characters WHERE character_name = '{$dupCharNameCheck}'";
              // $resultCharacter=$conn->query($sql);
              // $resultCharacter->fetch(PDO::FETCH_ASSOC);
              // if( ($dupCharNameCheck!=$characterPrevName) && $resultCharacter->rowCount()>0){
              //   $duplicateCheck=0;
              //   $duplicateMsg.="Character Name Already Exists! ";
              // }

              if(file_exists($targetCharThumbLoc)){
                $duplicateCheck=0;
                $duplicateMsg.="Character Thumbnail Already Exists! ";
              }

              if($duplicateCheck==1){
                if (move_uploaded_file($_FILES["upd_character_thumbnail"]["tmp_name"], $targetCharThumbLoc)) {
                
                  //Move existing file
                  $removeCheck=1;
                  $removeMsg="Editing character failed. ";
                  if(!rename($originalCharImageLoc, $targetCharImgLoc)){
                    $removeCheck=0;
                    $removeMsg.="Failed to move existing character Image file! ";
                  }
                  //Remove existing file
                  if(!unlink($originalCharThumbnailLoc)){
                    $removeCheck=0;
                    $removeMsg.="Failed to remove existing character Thumbnail file! ";
                  }
    
                  if($removeCheck == 1){
                    //Move Order
                    $rowCountOrder=0;
                    $sql="SELECT * FROM characters ORDER BY character_order";
                    $resultCharacter=$conn->query($sql);
                    while($rowCharacter=$resultCharacter->fetch(PDO::FETCH_ASSOC)){
                      $rowCountOrder += 1;
                      $rowCharId = $rowCharacter['character_id'];
                      $rowCharOrder = $rowCharacter['character_order'];
                      if($rowCharOrder < $_POST['upd_character_order']){
                        $sql="UPDATE characters SET character_order =:charOrder WHERE character_id=:charId";
                        $stmt=$conn->prepare($sql);
                        $stmt->execute([':charOrder'=>$rowCountOrder,
                                        ':charId'=>$rowCharId
                                      ]);
                      }
                      else{
                        $sql="UPDATE characters SET character_order =:charOrder WHERE character_id=:charId";
                        $stmt=$conn->prepare($sql);
                        $stmt->execute([':charOrder'=>$rowCountOrder+1,
                                        ':charId'=>$rowCharId
                                      ]);
                      }
                      
                    }

                    //Edit Character
                    $sql="UPDATE characters SET character_name =:charName, character_info =:charInfo, character_order =:charOrder, character_image =:charImage, character_image_location =:charImageLoc, 
                    character_thumbnail =:charThumbnail, character_thumbnail_location=:charThumbnailLoc
                    WHERE character_id=:charId";
                    $stmt=$conn->prepare($sql);
                    $stmt->execute([':charName'=>str_replace('\'','’',$_POST['upd_character_name']),
                                    ':charInfo'=>str_replace('\'','’',$_POST['upd_character_info']),
                                    ':charOrder'=>$_POST['upd_character_order'],
                                    ':charImage'=>$charImageName,
                                    ':charImageLoc'=>$targetCharImgLoc,
                                    ':charThumbnail'=>$charThumbnailName,
                                    ':charThumbnailLoc'=>$targetCharThumbLoc,
                                    ':charId'=>$_POST['upd_char_id']
                                  ]);
                    echo "<script> window.location.href = 'p3-characters.php'; alert('Character Updated Successfully!'); </script>";
                  }
                  else{
                    echo "<script> window.location.href = 'p3-characters.php'; alert('".$removeMsg."'); </script>";
                  }
                  
                } 
                else {
                  echo "<script> window.location.href = 'p3-characters.php'; alert('Editing Character Failed! There was an error in uploading the files.'); </script>";
                }
              }
              else{
                echo "<script> window.location.href = 'p3-characters.php'; alert('".$duplicateMsg."'); </script>";
              }
              
            }
            elseif ($uploadImageExist == 1 && $uploadThumbnailExist == 0){
              $duplicateMsg="Editing Character Failed! ";
              $duplicateCheck=1;

              $dupCharIdCheck = $_POST['upd_char_id'];
              $dupCharNameCheck = str_replace('\'','’',$_POST['upd_character_name']);
              
              $sql="SELECT * FROM characters WHERE character_id = '{$dupCharIdCheck}'";
              $resultCharacter=$conn->query($sql);
              $rowCharacter = $resultCharacter->fetch(PDO::FETCH_ASSOC);
              $characterPrevName = $rowCharacter['character_name'];

              $sql="SELECT * FROM characters";
              $resultCharacter=$conn->query($sql);
              while($rowCharacter = $resultCharacter->fetch(PDO::FETCH_ASSOC)){
                if ($rowCharacter['character_id'] != $dupCharIdCheck && preg_replace('/\s+/', '', strtolower($rowCharacter['character_name'])) == preg_replace('/\s+/', '', strtolower($dupCharNameCheck))){
                  $duplicateCheck=0;
                  $duplicateMsg.="Character Name Already Exists! ";
                  break;
                }
              }
              // $sql="SELECT * FROM characters WHERE character_name = '{$dupCharNameCheck}'";
              // $resultCharacter=$conn->query($sql);
              // $resultCharacter->fetch(PDO::FETCH_ASSOC);
              // if( ($dupCharNameCheck!=$characterPrevName) && $resultCharacter->rowCount()>0){
              //   $duplicateCheck=0;
              //   $duplicateMsg.="Character Name Already Exists! ";
              // }
              if(file_exists($targetCharImgLoc)){
                $duplicateCheck=0;
                $duplicateMsg.="Character Image Already Exists! ";
              }

              if($duplicateCheck==1){
                if (move_uploaded_file($_FILES["upd_character_image"]["tmp_name"], $targetCharImgLoc)) {
  
                  //Remove existing file
                  $removeCheck=1;
                  $removeMsg="Editing character failed. ";
                  if(!unlink($originalCharImageLoc)){
                    $removeCheck=0;
                    $removeMsg.="Failed to remove existing character Image file! ";
                  }
                  //Move existing file
                  if(!rename($originalCharThumbnailLoc, $targetCharThumbLoc)){
                    $removeCheck=0;
                    $removeMsg.="Failed to move existing character Thumbnail file! ";
                  }
                
                  if($removeCheck == 1){
                    //Move Order
                    $rowCountOrder=0;
                    $sql="SELECT * FROM characters ORDER BY character_order";
                    $resultCharacter=$conn->query($sql);
                    while($rowCharacter=$resultCharacter->fetch(PDO::FETCH_ASSOC)){
                      $rowCountOrder += 1;
                      $rowCharId = $rowCharacter['character_id'];
                      $rowCharOrder = $rowCharacter['character_order'];
                      if($rowCharOrder < $_POST['upd_character_order']){
                        $sql="UPDATE characters SET character_order =:charOrder WHERE character_id=:charId";
                        $stmt=$conn->prepare($sql);
                        $stmt->execute([':charOrder'=>$rowCountOrder,
                                        ':charId'=>$rowCharId
                                      ]);
                      }
                      else{
                        $sql="UPDATE characters SET character_order =:charOrder WHERE character_id=:charId";
                        $stmt=$conn->prepare($sql);
                        $stmt->execute([':charOrder'=>$rowCountOrder+1,
                                        ':charId'=>$rowCharId
                                      ]);
                      }
                      
                    }

                    //Edit Character
                    $sql="UPDATE characters SET character_name =:charName, character_info =:charInfo, character_order =:charOrder, character_image =:charImage, character_image_location =:charImageLoc, 
                    character_thumbnail =:charThumbnail, character_thumbnail_location=:charThumbnailLoc
                    WHERE character_id=:charId";
                    $stmt=$conn->prepare($sql);
                    $stmt->execute([':charName'=>str_replace('\'','’',$_POST['upd_character_name']),
                                    ':charInfo'=>str_replace('\'','’',$_POST['upd_character_info']),
                                    ':charOrder'=>$_POST['upd_character_order'],
                                    ':charImage'=>$charImageName,
                                    ':charImageLoc'=>$targetCharImgLoc,
                                    ':charThumbnail'=>$charThumbnailName,
                                    ':charThumbnailLoc'=>$targetCharThumbLoc,
                                    ':charId'=>$_POST['upd_char_id']
                                  ]);
                      echo "<script> window.location.href = 'p3-characters.php'; alert('Character Updated Successfully!'); </script>";
                  }
                  else{
                    echo "<script> window.location.href = 'p3-characters.php'; alert('".$removeMsg."'); </script>";
                  }
                
                } 
                else {
                  echo "<script> window.location.href = 'p3-characters.php'; alert('Editing Character Failed! There was an error in uploading the files.'); </script>";
                }
              }
              else{
                echo "<script> window.location.href = 'p3-characters.php'; alert('".$duplicateMsg."'); </script>";
              }
              
            }
            elseif ($uploadImageExist == 0 && $uploadThumbnailExist == 0){
              $duplicateMsg="Editing Character Failed! ";
              $duplicateCheck=1;

              $dupCharIdCheck = $_POST['upd_char_id'];
              $dupCharNameCheck = str_replace('\'','’',$_POST['upd_character_name']);
              $sql="SELECT * FROM characters WHERE character_id = '{$dupCharIdCheck}'";
              $resultCharacter=$conn->query($sql);
              $rowCharacter = $resultCharacter->fetch(PDO::FETCH_ASSOC);
              $characterPrevName = $rowCharacter['character_name'];

              $sql="SELECT * FROM characters";
              $resultCharacter=$conn->query($sql);
              while($rowCharacter = $resultCharacter->fetch(PDO::FETCH_ASSOC)){
                if ($rowCharacter['character_id'] != $dupCharIdCheck && preg_replace('/\s+/', '', strtolower($rowCharacter['character_name'])) == preg_replace('/\s+/', '', strtolower($dupCharNameCheck))){
                  $duplicateCheck=0;
                  $duplicateMsg.="Character Name Already Exists! ";
                  break;
                }
              }

              // $sql="SELECT * FROM characters WHERE character_name = '{$dupCharNameCheck}'";
              // $resultCharacter=$conn->query($sql);
              // $resultCharacter->fetch(PDO::FETCH_ASSOC);
              // if( ($dupCharNameCheck!=$characterPrevName) && $resultCharacter->rowCount()>0){
              //   $duplicateCheck=0;
              //   $duplicateMsg.="Character Name Already Exists! ";
              // }

              if($duplicateCheck==1){
                //Move existing files
                $removeCheck=1;
                $removeMsg="Editing character failed. ";
                if(!rename($originalCharImageLoc, $targetCharImgLoc)){
                  $removeCheck=0;
                  $removeMsg.="Failed to move existing character Image file! ";
                }
                if(!rename($originalCharThumbnailLoc, $targetCharThumbLoc)){
                  $removeCheck=0;
                  $removeMsg.="Failed to move existing character Tthumbnail file! ";
                }
    
                if($removeCheck == 1){
                  //Move Order
                  $rowCountOrder=0;
                  $sql="SELECT * FROM characters ORDER BY character_order";
                  $resultCharacter=$conn->query($sql);
                  while($rowCharacter=$resultCharacter->fetch(PDO::FETCH_ASSOC)){
                    $rowCountOrder += 1;
                    $rowCharId = $rowCharacter['character_id'];
                    $rowCharOrder = $rowCharacter['character_order'];
                    if($rowCharOrder < $_POST['upd_character_order']){
                      $sql="UPDATE characters SET character_order =:charOrder WHERE character_id=:charId";
                      $stmt=$conn->prepare($sql);
                      $stmt->execute([':charOrder'=>$rowCountOrder,
                                      ':charId'=>$rowCharId
                                    ]);
                    }
                    else{
                      $sql="UPDATE characters SET character_order =:charOrder WHERE character_id=:charId";
                      $stmt=$conn->prepare($sql);
                      $stmt->execute([':charOrder'=>$rowCountOrder+1,
                                      ':charId'=>$rowCharId
                                    ]);
                    }
                    
                  }

                  //Edit Character
                  $sql="UPDATE characters SET character_name =:charName, character_info =:charInfo, character_order =:charOrder, character_image =:charImage, character_image_location =:charImageLoc, 
                  character_thumbnail =:charThumbnail, character_thumbnail_location=:charThumbnailLoc
                  WHERE character_id=:charId";
                  $stmt=$conn->prepare($sql);
                  $stmt->execute([':charName'=>str_replace('\'','’',$_POST['upd_character_name']),
                                  ':charInfo'=>str_replace('\'','’',$_POST['upd_character_info']),
                                  ':charOrder'=>$_POST['upd_character_order'],
                                  ':charImage'=>$charImageName,
                                  ':charImageLoc'=>$targetCharImgLoc,
                                  ':charThumbnail'=>$charThumbnailName,
                                  ':charThumbnailLoc'=>$targetCharThumbLoc,
                                  ':charId'=>$_POST['upd_char_id']
                                ]);
                  echo "<script> window.location.href = 'p3-characters.php'; alert('Character Updated Successfully!'); </script>";
                }
                else{
                  echo "<script> window.location.href = 'p3-characters.php'; alert('".$removeMsg."'); </script>";
                }
              }
              else{
                echo "<script> window.location.href = 'p3-characters.php'; alert('".$duplicateMsg."'); </script>";
              }
              
  
            }
            else{
              echo "<script> window.location.href = 'p3-characters.php'; alert('Editing Character Failed! There was an error in file update.'); </script>";
            }
          }
          else {
            echo "<script> window.location.href = 'p3-characters.php'; alert('Editing Character Failed! Character does NOT Exist in characters table.'); </script>";
          }      
          
        }
      }
      else{
        echo "<script> window.location.href = 'p3-characters.php'; alert('".$charfileMsg."'); </script>";
      }

      
    }
    else{
      echo "<script> window.location.href = 'p3-characters.php'; alert('ERROR: BASE DIRECTORY CANNOT BE FOUND!'); </script>";
    }
    
  }

   //Delete Character
   if(isset($_POST['submit_delete_character'])){
    
    $basedir="uploads/img/char/";
    //Check if basedir exists
    if(file_exists($basedir)){
      $charId = $_POST['inp_delete_character'];

      //Check if character exists in table
      $sql="SELECT * FROM characters WHERE character_id = '{$charId}'";
      $resultCharacter=$conn->query($sql);
      $resultCharacter->fetch(PDO::FETCH_ASSOC);
      if($resultCharacter->rowCount()>0){

        $sql="SELECT * FROM characters WHERE character_id = '{$charId}'";
        $resultCharacter=$conn->query($sql);
        $rowCharacter =$resultCharacter->fetch(PDO::FETCH_ASSOC);
        $characterImageLoc = $rowCharacter['character_image_location'];
        $characterThumbnailLoc = $rowCharacter['character_thumbnail_location'];
        
        $charfileCheck = 1;
        $charfileMsg="Character deletion failed! ";
        //Check if character files exist
        if(!file_exists($characterImageLoc)){
          $charfileCheck = 0;
          $charfileMsg.="Character Image directory/file CANNOT Be Found. ";
        }
        if(!file_exists($characterThumbnailLoc)){
          $charfileCheck = 0;
          $charfileMsg.="Character Thumbnail directory/file CANNOT Be Found. ";
        }

        if($charfileCheck == 1){
          $deleteFailedMessage = "Character deletion failed. ";
          $deleteOk=1;
          //Delete Character Image and Thumbnail
          if(!unlink($characterImageLoc)){
            $deleteOk=0;
            $deleteFailedMessage .= "There was an error in deleting character Image file. ";
          }
          if(!unlink($characterThumbnailLoc)){
            $deleteOk=0;
            $deleteFailedMessage .= "There was an error in deleting character Thumbnail file. ";
          }

          if($deleteOk == 1){

            //Delete Character Record
            $sql="DELETE FROM characters WHERE character_id=:charId;";
            $stmt=$conn->prepare($sql);
            $stmt->execute([':charId'=>$charId]);

            //Move Order
            $rowCountOrder=0;
            $sql="SELECT * FROM characters ORDER BY character_order";
            $resultCharacter=$conn->query($sql);
            while($rowCharacter=$resultCharacter->fetch(PDO::FETCH_ASSOC)){
              $rowCountOrder += 1;
              $rowCharId = $rowCharacter['character_id'];
              $rowCharOrder = $rowCharacter['character_order'];
              $sql="UPDATE characters SET character_order =:charOrder WHERE character_id=:charId";
              $stmt=$conn->prepare($sql);
              $stmt->execute([':charOrder'=>$rowCountOrder,
                              ':charId'=>$rowCharId
                            ]);
            }  

            echo "<script> window.location.href = 'p3-characters.php'; alert('Character Deleted Successfully.'); </script>";
          }
          else{
            echo "<script> window.location.href = 'p3-characters.php'; alert('".$deleteFailedMessage."'); </script>";
          }
        }
        else{
          echo "<script> window.location.href = 'p3-characters.php'; alert('".$charfileMsg."'); </script>";
        }

      }
      else {
        echo "<script> window.location.href = 'p3-characters.php'; alert('Character does NOT Exist in characters table.'); </script>";
      }

    }
    else{
      echo "<script> window.location.href = 'p3-characters.php'; alert('ERROR: BASE DIRECTORY CANNOT BE FOUND!'); </script>";
    }
    
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no" />
    <title>One Piece (Strawhat)</title>
    <!------ Bootstrap CSS ----->
    <link rel="stylesheet" href="bootstrap-5.1.3-dist/css/bootstrap.css"/>
    <!----- JS and JQuery ----->
    <script src="bootstrap-5.1.3-dist/js/jquery-3.6.0.js"></script>
    <script src="bootstrap-5.1.3-dist/js/bootstrap.js"></script>

    <link rel="stylesheet" href="page-stylesheet.css" />
    <link rel="icon" type="image/x-icon" href="assets/wampis-logo.ico" />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Lato"
    />
  </head>
  <body>
    <!-- Navigation Bar -->
    <div class="top-stick">
      <div class="navbar-container">
        <!-- <i></i> -->
        <div class="navbar-menu-container">
          <a
            class="navbar-button-menu"
            id="id-menu-hidden"
            onclick="menuToggle()"
          >
            <div class="menu-icon-line-container">
              <div class="menu-icon-line"></div>
              <div class="menu-icon-line"></div>
              <div class="menu-icon-line"></div>
            </div>
          </a>
          <a class="navbar-button-sitelogo" href="index.php"
            ><img
              src="assets/wampis-logo.png"
              class="wampis-sitelogo"
              alt="wampis logo"
              width="35px"
            /><i class="navbar-button-sitelogo-text">Strawhat</i></a
          >
        </div>
        <div id="id-button-hide">
          <a class="navbar-button-container" href="index.php">
            <div class="navbar-button">Home</div>
          </a>
          <a class="navbar-button-container" href="p3-characters.php">
            <div class="navbar-button">CrewMembers</div>
          </a>
          <a class="navbar-button-container" href="p4-episodes.php">
            <div class="navbar-button">Highlights</div>
          </a>
        </div>
        
        <!-- Log In Tab -->
        <div class="user-login-container">
          <div class="user-login-button" onclick="accToggle()"
            ><img src="assets/user-icon-light.png" alt="user icon" width="35px" class="user-icon"/><div class="login-icon-usertext"><i>Hi, <?php echo $login_user; ?></i></div>
            <img src="assets/drop-down-button-light.png" alt="dropdown icon" width="20px" class="user-dropdown-icon">
            </div
          >
        </div>
      </div>
      <!-- Dropdown Account -->
      <div class="dropdown-account-container" id="id-dropdown-account-container">
        <a class="dropdown-account-button" href="acc.php">My Account</a>
        <?php if($login_access=="Admin"){?>
          <a class="dropdown-account-button" href="list.php">User List</a>
        <?php }?>
        <a class="dropdown-account-button" href="logout.php">Log Out</a>
      </div>

      <!-- Dropdown Menu -->
      <div class="dropdown-menu-container" id="id-dropdown-menu-container">
        <a class="dropdown-menu-button" href="index.php">Home</a>
        <a class="dropdown-menu-button" href="p3-characters.php">CrewMembers</a>
        <a class="dropdown-menu-button" href="p4-episodes.php">Episodes</a>
      </div>
    </div>

    <!-- Navigation Bar END -->
    <!-- content -->
    <div class="overflw">
      <div class="characters-container">
        <div class="char-heading">
          <?php
          $sql="SELECT * FROM characters";
          $resultCharacters=$conn->query($sql);
          $resultCharacters->fetch(PDO::FETCH_ASSOC);
          $charLastOrder = $resultCharacters->rowCount()+1;
          ?>
          <h2>Crew Members<?php if($login_access=="Admin") {?>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#add_character_modal" id="id_add_character_btn" onclick="characterAdd(<?php echo $charLastOrder; ?>)">Add</button>
          <?php }?></h2>
          <div class="char-heading-line"></div>
        </div>
        <!-- char box info display -->
        <div class="char-box-info" id="char-box-info-id">
          <div id="char-hide-btn-container">
            <img src="assets/x-icon-antiquewhite.png" class="char-box-hide-btn" id="char-box-info-btn-id" onclick="charinfoHide()" width="35px" alt="x icon">
          </div>
          <img class="char-display-full-pic"
            src=""
            alt="character"
          />
          <h3></h3>
          <p></p>
        </div>

        <!-- char box thumbnail -->
        <div class="char-fig-container clearfix" id="char-fig-container-id">
          <?php
            $sql="SELECT * FROM characters ORDER BY character_order";
            $resultChar=$conn->query($sql);
            while($rowChar=$resultChar->fetch(PDO::FETCH_ASSOC)){
              
              //replace special characters for onclick to work
              $charNameChange = str_replace("'",'39Si3N9gLe3Q9t', str_replace('"','39DoU3b9Le3Q9t',$rowChar['character_name']) );

              $charInfoChange = str_replace("'",'39Si3N9gLe3Q9t', str_replace('"','39DoU3b9Le3Q9t',$rowChar['character_info']) );
              $charInfoChange = str_replace("\r",'36Re3T6u3R6n', str_replace("\n",'36Ne3W6Li3N6e',$charInfoChange) );


              $charImgChange = str_replace("'",'39Si3N9gLe3Q9t', str_replace('"','39DoU3b9Le3Q9t',$rowChar['character_image']) );
              $charThumbnailChange = str_replace("'",'39Si3N9gLe3Q9t', str_replace('"','39DoU3b9Le3Q9t',$rowChar['character_thumbnail']) );
              $charImgLocChange = str_replace("'",'39Si3N9gLe3Q9t', str_replace('"','39DoU3b9Le3Q9t',$rowChar['character_image_location']) );
              $charThumbnailLocChange = str_replace("'",'39Si3N9gLe3Q9t', str_replace('"','39DoU3b9Le3Q9t',$rowChar['character_thumbnail_location']) );
          ?>
          <div class="char-box-fig">
            <figure>
              <div class="char-box-fig-img">
                <img
                  src="<?php echo $rowChar['character_thumbnail_location']; ?>"
                  alt="<?php echo $rowChar['character_name']; ?>"
                />
                  <div class="char-box-fig-hiddeninfo">
                    <img
                      src="<?php echo $rowChar['character_image_location']; ?>"
                      alt="<?php echo $rowChar['character_name']; ?>"
                    />
                    <h3><?php echo $rowChar['character_name']; ?></h3>
                    <p><?php echo $rowChar['character_info']; ?></p>
                  </div>
              </div>
              <figcaption><p><?php echo $rowChar['character_name']; ?></p><?php if($login_access=="Admin") { ?><p><button type="button" class="btn btn-primary" 
                data-bs-toggle="modal" data-bs-target="#edit_character_modal" onclick="characterEdit('<?php echo $rowChar['character_id']; ?>','<?php echo $rowChar['character_order']; ?>', '<?php echo $charNameChange; ?>','<?php echo $charInfoChange; ?>','<?php echo $charImgChange; ?>','<?php echo $charThumbnailChange; ?>', '<?php echo $charImgLocChange; ?>', '<?php echo $charThumbnailLocChange; ?>')">Edit</button>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete_character_modal" onclick="characterDelete('<?php echo $rowChar['character_id']; ?>','<?php echo $charNameChange; ?>')"
                >Delete</button></p><?php } ?>
              </figcaption>
            </figure>
          </div>
          <?php }?>
          <!-- end fig -->
        </div>
      </div>

    <!--------------------------- add Character Modal ------------------------>
    <div class="modal fade" id="add_character_modal" tabindex="-1" role="dialog" aria-labelledby="modal_container" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modal_container">Add Character</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="" method="POST" enctype='multipart/form-data'>
              <div class="mb-3 mt-3">
                <input type="text" class="form-control" id="id_inp_character_name" placeholder="Enter Character Name" name="inp_character_name" required/>
              </div>
              <div class="mb-3">
                <textarea class="form-control" cols="1" rows="4" id="id_inp_character_info" placeholder="Enter Character Description" name="inp_character_info" required></textarea>
              </div>
              <div class="mb-3 mt-3">
                <label class="form-label">Order in Character Display Arrangement: </label>
                <input type="number" min="1" max="9000" class="form-control" id="id_inp_character_order" placeholder="Enter Character Order" name="inp_character_order" required/>
              </div>
              <!-- Upload Image -->
              <div class="mb-3 char_pos1">
                <label class="form-label">Select Character Image with Rectangle Aspect Ratio</label>
                <input type="file" class="upd-txt-width" id="id_inp_character_image" name="inp_character_image" required/>
              </div>
              <div class="mb-3 char_pos2">
                <label class="form-label">Select Character Thumbnail with Square Aspect Ratio</label>
                <input type="file" class="upd-txt-width" id="id_inp_character_thumbnail" name="inp_character_thumbnail" required/>
              </div>
          </div>
          <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="submit_add_character">Save</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!--------------------------- edit Character Modal ------------------------>
    <div class="modal fade" id="edit_character_modal" tabindex="-1" role="dialog" aria-labelledby="modal_container" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modal_container">Edit Character</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="" method="POST" enctype='multipart/form-data'>
              <div class="mb-3 mt-3">
                <input type="text" class="form-control" id="id_upd_character_name" placeholder="Enter Character Name" name="upd_character_name" required/>
              </div>
              <div class="mb-3">
                <textarea class="form-control" cols="1" rows="4" id="id_upd_character_info" placeholder="Enter Character Description" name="upd_character_info"></textarea>
              </div>
              <div class="mb-3 mt-3">
                <label class="form-label">Order in Character Display Arrangement: </label>
                <input type="number" min="1" max="9000" class="form-control" id="id_upd_character_order" placeholder="Enter Character Order" name="upd_character_order"/>
              </div>
              <!-- Upload Image -->
              <div class="mb-3 char_pos1">
                <label class="form-label" id="id_cur_character_image"></label>
                <label class="form-label">Select Character Image with Rectangle Aspect Ratio (Optional - Choosing will overwrite existing image)</label>
                <input type="file" class="upd-txt-width" id="id_upd_character_image" name="upd_character_image"/>
              </div>
              <div class="mb-3 char_pos2">
                <label class="form-label" id="id_cur_character_thumbnail"></label>
                <label class="form-label">Select Character Thumbnail with Square Aspect Ratio (Optional - Choosing will overwrite existing thumbnail)</label>
                <input type="file" class="upd-txt-width" id="id_upd_character_thumbnail" name="upd_character_thumbnail"/>
              </div>
          </div>
          <div class="modal-footer">
              <input type="hidden" name="upd_char_image_loc" id="id_upd_char_image_loc"/>
              <input type="hidden" name="upd_char_thumbnail_loc" id="id_upd_char_thumbnail_loc"/>
              <input type="hidden" name="upd_char_id" id="id_upd_char_id"/>
              <button type="submit" class="btn btn-primary" name="submit_edit_character">Save</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </form>
          </div>
        </div>
      </div>
    </div>          

    <!--------------------------- delete Character Modal ------------------------>
    <div class="modal fade" id="delete_character_modal" tabindex="-1" role="dialog" aria-labelledby="modal_container" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modal_container">Delete Character</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            </button>
          </div>
          <div class="modal-body">
            <p>Are you sure you want to delete <span id="id-charNm-del"></span>?</p>
          </div>
          <div class="modal-footer">
            <form action="" method="POST">
              <input type="hidden" name="inp_delete_character" id="id_inp_delete_character"/>
              <button type="submit" class="btn btn-primary" name="submit_delete_character">Yes</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <script src="page-script.js"></script>
  </body>
</html>
<script>
  function characterAdd($charOrder){
    document.getElementById('id_inp_character_order').value = $charOrder;
  }

  //Send value to edit modal
  function characterEdit($charId, $charOrder, $charName, $charInfo, $charImage, $charThumbnail, $charImageLoc, $charThumbnailLoc){
    document.getElementById('id_upd_char_id').value = $charId;
    document.getElementById('id_upd_character_order').value = $charOrder;
    //replace changed value to original value
    document.getElementById('id_upd_character_name').value = $charName.replace(/39DoU3b9Le3Q9t/g,"\"").replace(/39Si3N9gLe3Q9t/g,"\'");
    document.getElementById('id_upd_character_info').value = $charInfo.replace(/39DoU3b9Le3Q9t/g,"\"").replace(/39Si3N9gLe3Q9t/g,"\'").replace(/36Re3T6u3R6n/g,"\r").replace(/36Ne3W6Li3N6e/g,"\n");
    document.getElementById('id_cur_character_image').innerHTML = "Current char image: "+$charImage.replace(/39DoU3b9Le3Q9t/g,"\"").replace(/39Si3N9gLe3Q9t/g,"\'");
    document.getElementById('id_cur_character_thumbnail').innerHTML = "Current char thumbnail: "+$charThumbnail.replace(/39DoU3b9Le3Q9t/g,"\"").replace(/39Si3N9gLe3Q9t/g,"\'");
    document.getElementById('id_upd_char_image_loc').value = $charImageLoc.replace(/39DoU3b9Le3Q9t/g,"\"").replace(/39Si3N9gLe3Q9t/g,"\'");
    document.getElementById('id_upd_char_thumbnail_loc').value = $charThumbnailLoc.replace(/39DoU3b9Le3Q9t/g,"\"").replace(/39Si3N9gLe3Q9t/g,"\'");
  }

   //Send value to delete modal
  function characterDelete($charId, $charName){
    document.getElementById('id_inp_delete_character').value = $charId;
    //replace changed value to original value
    document.getElementById('id-charNm-del').innerHTML = $charName.replace(/39DoU3b9Le3Q9t/g,"\"").replace(/39Si3N9gLe3Q9t/g,"\'");
  }

  //Input ’ instead of quote ' in chararacter info
  // $(function ()
  //   {
  //     $('#id_upd_character_info').keyup(function (e){
  //         if(e.keyCode == 222){
  //             var curr = getCursor(this);
  //             var val = $(this).val();
  //             var end = val.length;
  //             $(this).val( val.substr(0, curr-1) + '’' + val.substr(curr, end));
  //         }
  //     })
  //   });

  //   //Get Cursor Position in textarea
  //   function getCursor(ev) { 
  //     if (ev.selectionStart) { 
  //         return ev.selectionStart; 
  //     }
  //     else if (document.selection) { 
  //         ev.focus(); 

  //         var r = document.selection.createRange(); 
  //         if (r == null) { 
  //             return 0; 
  //         } 

  //         var re = ev.createTextRange(), 
  //         rc = re.duplicate(); 
  //         re.moveToBookmark(r.getBookmark()); 
  //         rc.setEndPoint('EndToStart', re); 

  //         return rc.text.length; 
  //     }  
  //     return 0; 
  //   }

</script>
