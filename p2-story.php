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

  $storyid = 2;
  $plotb1id = 6;
  $plotb2id = 7;
  $plotb3id = 8;
  $plotb4id = 9;
  $plotb5id = 10;

  //Edit Story - Setting
  if(isset($_POST['submit_edit_story'])){
    $basedir="uploads/img/story/";

    if(file_exists($basedir)){

      //Check if story exists in table
      $sql="SELECT * FROM story WHERE story_id = '{$storyid}'";
      $resultStory=$conn->query($sql);
      $resultStory->fetch(PDO::FETCH_ASSOC);

      if($resultStory->rowCount()>0){
        $sql="SELECT * FROM story WHERE story_id = '{$storyid}'";
        $resultStory=$conn->query($sql);
        $rowStory =$resultStory->fetch(PDO::FETCH_ASSOC);
        $originalStoryImageLoc = $rowStory['story_image_location'];

        //Check if original story image exists
        if(file_exists($originalStoryImageLoc)){

          $updStoryImage=basename($originalStoryImageLoc);
          $updStoryImageLocation=$originalStoryImageLoc;
          $uploadImageExist = 1;
          $uploadOk = 1;
          $uploadFailedMessage="Editing Story Failed! ";
            
          //Check if there's an uploaded Thumbnail
          if (file_exists($_FILES['upd_story_image_location']['tmp_name']) || is_uploaded_file($_FILES['upd_story_image_location']['tmp_name'])) {
            $uploadImageExist = 1;

            //Check if image is Undefined /  Multiple Files / Corrupt
            if (!isset($_FILES['upd_story_image_location']['error']) || is_array($_FILES['upd_story_image_location']['error'])) {
              $uploadFailedMessage.="Invalid Image Parameters! ";
              $uploadOk = 0;
            }

            //Check if it's an actual image
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            if (false === $ext = array_search( 
              $finfo->file($_FILES['upd_story_image_location']['tmp_name']), 
              array(
                  'jpg' => 'image/jpg',
                  'jpeg' => 'image/jpeg',
                  'png' => 'image/png'
              ), true )) {
                $uploadFailedMessage.="Only genuine JPG, JPEG, & PNG image types are allowed! ";
                $uploadOk = 0;
            }

            // Check file size (Max: 10MB | 10000000 Bytes)
            if ($_FILES["upd_story_image_location"]["size"] > 10000000) {
              $uploadFailedMessage.="Image file is too large! (Max: 10MB) ";
              $uploadOk = 0;
            }

            //Update image if pass
            if($uploadOk ==  1){
              $updStoryImage=basename($_FILES['upd_story_image_location']['name']);
              $updStoryImageLocation=$basedir.$updStoryImage;
            }
          }
          else{
            $uploadImageExist = 0;
          }

          // Check upload
          if ($uploadOk == 0) {
            echo "<script> window.location.href = 'p2-story.php'; alert('".$uploadFailedMessage."'); </script>";
          } 
          else{
            if($uploadImageExist == 1){
              if(move_uploaded_file($_FILES['upd_story_image_location']['tmp_name'], $updStoryImageLocation)){
                
                //Remove existing file
                if(unlink($originalStoryImageLoc)){
                  $sql="UPDATE story SET story_paragraph_top =:storyPgTop, story_paragraph_mid =:storyPgMid, story_image_caption =:storyCaption,story_image_location =:storyImageLoc  WHERE story_id=:storyId";
                  $stmt=$conn->prepare($sql);
                  $stmt->execute([':storyPgTop'=>str_replace('\'','’',$_POST['upd_story_paragraph_top']),
                                  ':storyPgMid'=>str_replace('\'','’',$_POST['upd_story_paragraph_mid']),
                                  ':storyCaption'=>str_replace('\'','’',$_POST['upd_story_image_caption']),
                                  ':storyImageLoc'=>$updStoryImageLocation,
                                  ':storyId'=> $storyid ]);

                  echo "<script> window.location.href = 'p2-story.php'; alert('Story Updated Successfully.'); </script>";
                }
                else{
                  echo "<script> window.location.href = 'p2-story.php'; alert('Failed to remove existing story image file!'); </script>";
                }
                
              }
              else{
                echo "<script> window.location.href = 'p2-story.php'; alert('There was an error in uploading the files.'); </script>";
              }
              
            }
            else{
              $sql="UPDATE story SET story_paragraph_top =:storyPgTop, story_paragraph_mid =:storyPgMid, story_image_caption =:storyCaption,story_image_location =:storyImageLoc  WHERE story_id=:storyId";
              $stmt=$conn->prepare($sql);
              $stmt->execute([':storyPgTop'=>str_replace('\'','’',$_POST['upd_story_paragraph_top']),
                              ':storyPgMid'=>str_replace('\'','’',$_POST['upd_story_paragraph_mid']),
                              ':storyCaption'=>str_replace('\'','’',$_POST['upd_story_image_caption']),
                              ':storyImageLoc'=>$updStoryImageLocation,
                              ':storyId'=> $storyid ]);

              echo "<script> window.location.href = 'p2-story.php'; alert('Story Updated Successfully.'); </script>";
            }
          }
        }
        else{
          echo "<script> window.location.href = 'p2-story.php'; alert('Editing story failed! Original story image file CANNOT Be Found.'); </script>";
        }

      }
      else {
        echo "<script> window.location.href = 'p2-story.php'; alert('Editing story failed! Story (ID:2) does NOT Exist in story table.'); </script>";
      }
    }
    else{
      echo "<script> window.location.href = 'p2-story.php'; alert('ERROR: BASE DIRECTORY CANNOT BE FOUND!'); </script>";
    }

  }

  //Edit Plot - Block 1
  if(isset($_POST['submit_edit_plotb1'])){
    $basedir="uploads/img/plot/";

    if(file_exists($basedir)){

      //Check if plot exists in table
      $sql="SELECT * FROM plot WHERE plot_id = '{$plotb1id}'";
      $resultPlot=$conn->query($sql);
      $resultPlot->fetch(PDO::FETCH_ASSOC);

      if($resultPlot->rowCount()>0){
        $sql="SELECT * FROM plot WHERE plot_id = '{$plotb1id}'";
        $resultPlot=$conn->query($sql);
        $rowPlot =$resultPlot->fetch(PDO::FETCH_ASSOC);
        $originalPlotB1ImageLoc = $rowPlot['plot_image_location'];

        //Check if original plot image exists
        if(file_exists($originalPlotB1ImageLoc)){

          $updPlotB1Image=basename($originalPlotB1ImageLoc);
          $updPlotB1ImageLocation=$originalPlotB1ImageLoc;
          $uploadImageExist = 1;
          $uploadOk = 1;
          $uploadFailedMessage="Editing Plot Failed! ";
            
          //Check if there's an uploaded Thumbnail
          if (file_exists($_FILES['upd_plotb1_image_location']['tmp_name']) || is_uploaded_file($_FILES['upd_plotb1_image_location']['tmp_name'])) {
            $uploadImageExist = 1;

            //Check if image is Undefined /  Multiple Files / Corrupt
            if (!isset($_FILES['upd_plotb1_image_location']['error']) || is_array($_FILES['upd_plotb1_image_location']['error'])) {
              $uploadFailedMessage.="Invalid Image Parameters! ";
              $uploadOk = 0;
            }

            //Check if it's an actual image
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            if (false === $ext = array_search( 
              $finfo->file($_FILES['upd_plotb1_image_location']['tmp_name']), 
              array(
                  'jpg' => 'image/jpg',
                  'jpeg' => 'image/jpeg',
                  'png' => 'image/png'
              ), true )) {
                $uploadFailedMessage.="Only genuine JPG, JPEG, & PNG image types are allowed! ";
                $uploadOk = 0;
            }

            // Check file size (Max: 10MB | 10000000 Bytes)
            if ($_FILES["upd_plotb1_image_location"]["size"] > 10000000) {
              $uploadFailedMessage.="Image file is too large! (Max: 10MB) ";
              $uploadOk = 0;
            }

            //Update image if pass
            if($uploadOk ==  1){
              $updPlotB1Image=basename($_FILES['upd_plotb1_image_location']['name']);
              $updPlotB1ImageLocation=$basedir.$updPlotB1Image;
            }
          }
          else{
            $uploadImageExist = 0;
          }

          // Check upload
          if ($uploadOk == 0) {
            echo "<script> window.location.href = 'p2-story.php'; alert('".$uploadFailedMessage."'); </script>";
          } 
          else{
            if($uploadImageExist == 1){
              if(!file_exists($updPlotB1ImageLocation)){
                if(move_uploaded_file($_FILES['upd_plotb1_image_location']['tmp_name'], $updPlotB1ImageLocation)){
                
                  //Remove existing file
                  if(unlink($originalPlotB1ImageLoc)){
                    $sql="UPDATE plot SET plot_paragraph_mid =:plotPgMid, plot_image_caption =:plotCaption, plot_image_location =:plotImageLoc  WHERE plot_id=:plotId";
                    $stmt=$conn->prepare($sql);
                    $stmt->execute([
                                    ':plotPgMid'=>str_replace('\'','’',$_POST['upd_plotb1_paragraph_mid']),
                                    ':plotCaption'=>str_replace('\'','’',$_POST['upd_plotb1_image_caption']),
                                    ':plotImageLoc'=>$updPlotB1ImageLocation,
                                    ':plotId'=> $plotb1id ]);
  
                    echo "<script> window.location.href = 'p2-story.php'; alert('Plot Updated Successfully.'); </script>";
                  }
                  else{
                    echo "<script> window.location.href = 'p2-story.php'; alert('Failed to remove existing plot image file!'); </script>";
                  }
                  
                }
                else{
                  echo "<script> window.location.href = 'p2-story.php'; alert('There was an error in uploading the files.'); </script>";
                }
              }
              else{
                echo "<script> window.location.href = 'p2-story.php'; alert('Editing Plot Failed! Image Already Exists!'); </script>";
              }
              
            }
            else{
              $sql="UPDATE plot SET plot_paragraph_mid =:plotPgMid, plot_image_caption =:plotCaption, plot_image_location =:plotImageLoc  WHERE plot_id=:plotId";
              $stmt=$conn->prepare($sql);
              $stmt->execute([
                              ':plotPgMid'=>str_replace('\'','’',$_POST['upd_plotb1_paragraph_mid']),
                              ':plotCaption'=>str_replace('\'','’',$_POST['upd_plotb1_image_caption']),
                              ':plotImageLoc'=>$updPlotB1ImageLocation,
                              ':plotId'=> $plotb1id ]);
                              
              echo "<script> window.location.href = 'p2-story.php'; alert('Plot Updated Successfully.'); </script>";
            }
          }
        }
        else{
          echo "<script> window.location.href = 'p2-story.php'; alert('Editing plot failed! Original plot image file CANNOT Be Found.'); </script>";
        }

      }
      else {
        echo "<script> window.location.href = 'p2-story.php'; alert('Editing plot failed! Plot (ID:6) does NOT Exist in plot table.'); </script>";
      }
    }
    else{
      echo "<script> window.location.href = 'p2-story.php'; alert('ERROR: BASE DIRECTORY CANNOT BE FOUND!'); </script>";
    }

  }

  //Edit Plot - Block 2
  if(isset($_POST['submit_edit_plotb2'])){
    $basedir="uploads/img/plot/";

    if(file_exists($basedir)){

      //Check if plot exists in table
      $sql="SELECT * FROM plot WHERE plot_id = '{$plotb2id}'";
      $resultPlot=$conn->query($sql);
      $resultPlot->fetch(PDO::FETCH_ASSOC);

      if($resultPlot->rowCount()>0){
        $sql="SELECT * FROM plot WHERE plot_id = '{$plotb2id}'";
        $resultPlot=$conn->query($sql);
        $rowPlot =$resultPlot->fetch(PDO::FETCH_ASSOC);
        $originalPlotB2ImageLoc = $rowPlot['plot_image_location'];

        //Check if original plot image exists
        if(file_exists($originalPlotB2ImageLoc)){

          $updPlotB2Image=basename($originalPlotB2ImageLoc);
          $updPlotB2ImageLocation=$originalPlotB2ImageLoc;
          $uploadImageExist = 1;
          $uploadOk = 1;
          $uploadFailedMessage="Editing Plot Failed! ";
            
          //Check if there's an uploaded Thumbnail
          if (file_exists($_FILES['upd_plotb2_image_location']['tmp_name']) || is_uploaded_file($_FILES['upd_plotb2_image_location']['tmp_name'])) {
            $uploadImageExist = 1;

            //Check if image is Undefined /  Multiple Files / Corrupt
            if (!isset($_FILES['upd_plotb2_image_location']['error']) || is_array($_FILES['upd_plotb2_image_location']['error'])) {
              $uploadFailedMessage.="Invalid Image Parameters! ";
              $uploadOk = 0;
            }

            //Check if it's an actual image
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            if (false === $ext = array_search( 
              $finfo->file($_FILES['upd_plotb2_image_location']['tmp_name']), 
              array(
                  'jpg' => 'image/jpg',
                  'jpeg' => 'image/jpeg',
                  'png' => 'image/png'
              ), true )) {
                $uploadFailedMessage.="Only genuine JPG, JPEG, & PNG image types are allowed! ";
                $uploadOk = 0;
            }

            // Check file size (Max: 10MB | 10000000 Bytes)
            if ($_FILES["upd_plotb2_image_location"]["size"] > 10000000) {
              $uploadFailedMessage.="Image file is too large! (Max: 10MB) ";
              $uploadOk = 0;
            }

            //Update image if pass
            if($uploadOk ==  1){
              $updPlotB2Image=basename($_FILES['upd_plotb2_image_location']['name']);
              $updPlotB2ImageLocation=$basedir.$updPlotB2Image;
            }
          }
          else{
            $uploadImageExist = 0;
          }

          // Check upload
          if ($uploadOk == 0) {
            echo "<script> window.location.href = 'p2-story.php'; alert('".$uploadFailedMessage."'); </script>";
          } 
          else{
            if($uploadImageExist == 1){
              if(!file_exists($updPlotB2ImageLocation)){
                if(move_uploaded_file($_FILES['upd_plotb2_image_location']['tmp_name'], $updPlotB2ImageLocation)){
                
                  //Remove existing file
                  if(unlink($originalPlotB2ImageLoc)){
                    $sql="UPDATE plot SET plot_paragraph_mid =:plotPgMid, plot_image_caption =:plotCaption, plot_image_location =:plotImageLoc  WHERE plot_id=:plotId";
                    $stmt=$conn->prepare($sql);
                    $stmt->execute([
                                    ':plotPgMid'=>str_replace('\'','’',$_POST['upd_plotb2_paragraph_mid']),
                                    ':plotCaption'=>str_replace('\'','’',$_POST['upd_plotb2_image_caption']),
                                    ':plotImageLoc'=>$updPlotB2ImageLocation,
                                    ':plotId'=> $plotb2id ]);
  
                    echo "<script> window.location.href = 'p2-story.php'; alert('Plot Updated Successfully.'); </script>";
                  }
                  else{
                    echo "<script> window.location.href = 'p2-story.php'; alert('Failed to remove existing plot image file!'); </script>";
                  }
                  
                }
                else{
                  echo "<script> window.location.href = 'p2-story.php'; alert('There was an error in uploading the files.'); </script>";
                }
              }
              else{
                echo "<script> window.location.href = 'p2-story.php'; alert('Editing Plot Failed! Image Already Exists!'); </script>";
              }

            }
            else{
              $sql="UPDATE plot SET plot_paragraph_mid =:plotPgMid, plot_image_caption =:plotCaption, plot_image_location =:plotImageLoc  WHERE plot_id=:plotId";
              $stmt=$conn->prepare($sql);
              $stmt->execute([
                              ':plotPgMid'=>str_replace('\'','’',$_POST['upd_plotb2_paragraph_mid']),
                              ':plotCaption'=>str_replace('\'','’',$_POST['upd_plotb2_image_caption']),
                              ':plotImageLoc'=>$updPlotB2ImageLocation,
                              ':plotId'=> $plotb2id ]);
                              
              echo "<script> window.location.href = 'p2-story.php'; alert('Plot Updated Successfully.'); </script>";
            }
          }
        }
        else{
          echo "<script> window.location.href = 'p2-story.php'; alert('Editing plot failed! Original plot image file CANNOT Be Found.'); </script>";
        }

      }
      else {
        echo "<script> window.location.href = 'p2-story.php'; alert('Editing plot failed! Plot (ID:7) does NOT Exist in plot table.'); </script>";
      }
    }
    else{
      echo "<script> window.location.href = 'p2-story.php'; alert('ERROR: BASE DIRECTORY CANNOT BE FOUND!'); </script>";
    }

  }
  
  //Edit Plot - Block 3
  if(isset($_POST['submit_edit_plotb3'])){
    $basedir="uploads/img/plot/";

    if(file_exists($basedir)){

      //Check if plot exists in table
      $sql="SELECT * FROM plot WHERE plot_id = '{$plotb3id}'";
      $resultPlot=$conn->query($sql);
      $resultPlot->fetch(PDO::FETCH_ASSOC);

      if($resultPlot->rowCount()>0){
        $sql="SELECT * FROM plot WHERE plot_id = '{$plotb3id}'";
        $resultPlot=$conn->query($sql);
        $rowPlot =$resultPlot->fetch(PDO::FETCH_ASSOC);
        $originalPlotB3ImageLoc = $rowPlot['plot_image_location'];

        //Check if original plot image exists
        if(file_exists($originalPlotB3ImageLoc)){

          $updPlotB3Image=basename($originalPlotB3ImageLoc);
          $updPlotB3ImageLocation=$originalPlotB3ImageLoc;
          $uploadImageExist = 1;
          $uploadOk = 1;
          $uploadFailedMessage="Editing Plot Failed! ";
            
          //Check if there's an uploaded Thumbnail
          if (file_exists($_FILES['upd_plotb3_image_location']['tmp_name']) || is_uploaded_file($_FILES['upd_plotb3_image_location']['tmp_name'])) {
            $uploadImageExist = 1;

            //Check if image is Undefined /  Multiple Files / Corrupt
            if (!isset($_FILES['upd_plotb3_image_location']['error']) || is_array($_FILES['upd_plotb3_image_location']['error'])) {
              $uploadFailedMessage.="Invalid Image Parameters! ";
              $uploadOk = 0;
            }

            //Check if it's an actual image
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            if (false === $ext = array_search( 
              $finfo->file($_FILES['upd_plotb3_image_location']['tmp_name']), 
              array(
                  'jpg' => 'image/jpg',
                  'jpeg' => 'image/jpeg',
                  'png' => 'image/png'
              ), true )) {
                $uploadFailedMessage.="Only genuine JPG, JPEG, & PNG image types are allowed! ";
                $uploadOk = 0;
            }

            // Check file size (Max: 10MB | 10000000 Bytes)
            if ($_FILES["upd_plotb3_image_location"]["size"] > 10000000) {
              $uploadFailedMessage.="Image file is too large! (Max: 10MB) ";
              $uploadOk = 0;
            }

            //Update image if pass
            if($uploadOk ==  1){
              $updPlotB3Image=basename($_FILES['upd_plotb3_image_location']['name']);
              $updPlotB3ImageLocation=$basedir.$updPlotB3Image;
            }
          }
          else{
            $uploadImageExist = 0;
          }

          // Check upload
          if ($uploadOk == 0) {
            echo "<script> window.location.href = 'p2-story.php'; alert('".$uploadFailedMessage."'); </script>";
          } 
          else{
            if($uploadImageExist == 1){

              if(!file_exists($updPlotB3ImageLocation)){
                if(move_uploaded_file($_FILES['upd_plotb3_image_location']['tmp_name'], $updPlotB3ImageLocation)){
                
                  //Remove existing file
                  if(unlink($originalPlotB3ImageLoc)){
                    $sql="UPDATE plot SET plot_paragraph_top =:plotPgTop, plot_paragraph_mid =:plotPgMid, plot_image_caption =:plotCaption, plot_image_location =:plotImageLoc  WHERE plot_id=:plotId";
                    $stmt=$conn->prepare($sql);
                    $stmt->execute([':plotPgTop'=>str_replace('\'','’',$_POST['upd_plotb3_paragraph_top']),
                                    ':plotPgMid'=>str_replace('\'','’',$_POST['upd_plotb3_paragraph_mid']),
                                    ':plotCaption'=>str_replace('\'','’',$_POST['upd_plotb3_image_caption']),
                                    ':plotImageLoc'=>$updPlotB3ImageLocation,
                                    ':plotId'=> $plotb3id ]);
  
                    echo "<script> window.location.href = 'p2-story.php'; alert('Plot Updated Successfully.'); </script>";
                  }
                  else{
                    echo "<script> window.location.href = 'p2-story.php'; alert('Failed to remove existing plot image file!'); </script>";
                  }
                  
                }
                else{
                  echo "<script> window.location.href = 'p2-story.php'; alert('There was an error in uploading the files.'); </script>";
                }
              }
              else{
                echo "<script> window.location.href = 'p2-story.php'; alert('Editing Plot Failed! Image Already Exists!'); </script>";
              }
              
            }
            else{
              $sql="UPDATE plot SET plot_paragraph_top =:plotPgTop, plot_paragraph_mid =:plotPgMid, plot_image_caption =:plotCaption, plot_image_location =:plotImageLoc  WHERE plot_id=:plotId";
              $stmt=$conn->prepare($sql);
              $stmt->execute([':plotPgTop'=>str_replace('\'','’',$_POST['upd_plotb3_paragraph_top']),
                              ':plotPgMid'=>str_replace('\'','’',$_POST['upd_plotb3_paragraph_mid']),
                              ':plotCaption'=>str_replace('\'','’',$_POST['upd_plotb3_image_caption']),
                              ':plotImageLoc'=>$updPlotB3ImageLocation,
                              ':plotId'=> $plotb3id ]);

              echo "<script> window.location.href = 'p2-story.php'; alert('Plot Updated Successfully.'); </script>";
            }
          }
        }
        else{
          echo "<script> window.location.href = 'p2-story.php'; alert('Editing plot failed! Original plot image file CANNOT Be Found.'); </script>";
        }

      }
      else {
        echo "<script> window.location.href = 'p2-story.php'; alert('Editing plot failed! Plot (ID:8) does NOT Exist in plot table.'); </script>";
      }
    }
    else{
      echo "<script> window.location.href = 'p2-story.php'; alert('ERROR: BASE DIRECTORY CANNOT BE FOUND!'); </script>";
    }

  }

  //Edit Plot - Block 4
  if(isset($_POST['submit_edit_plotb4'])){
    $basedir="uploads/img/plot/";

    if(file_exists($basedir)){

      //Check if plot exists in table
      $sql="SELECT * FROM plot WHERE plot_id = '{$plotb4id}'";
      $resultPlot=$conn->query($sql);
      $resultPlot->fetch(PDO::FETCH_ASSOC);

      if($resultPlot->rowCount()>0){
        $sql="SELECT * FROM plot WHERE plot_id = '{$plotb4id}'";
        $resultPlot=$conn->query($sql);
        $rowPlot =$resultPlot->fetch(PDO::FETCH_ASSOC);
        $originalPlotB4ImageLoc = $rowPlot['plot_image_location'];

        //Check if original plot image exists
        if(file_exists($originalPlotB4ImageLoc)){

          $updPlotB4Image=basename($originalPlotB4ImageLoc);
          $updPlotB4ImageLocation=$originalPlotB4ImageLoc;
          $uploadImageExist = 1;
          $uploadOk = 1;
          $uploadFailedMessage="Editing Plot Failed! ";
            
          //Check if there's an uploaded Thumbnail
          if (file_exists($_FILES['upd_plotb4_image_location']['tmp_name']) || is_uploaded_file($_FILES['upd_plotb4_image_location']['tmp_name'])) {
            $uploadImageExist = 1;

            //Check if image is Undefined /  Multiple Files / Corrupt
            if (!isset($_FILES['upd_plotb4_image_location']['error']) || is_array($_FILES['upd_plotb4_image_location']['error'])) {
              $uploadFailedMessage.="Invalid Image Parameters! ";
              $uploadOk = 0;
            }

            //Check if it's an actual image
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            if (false === $ext = array_search( 
              $finfo->file($_FILES['upd_plotb4_image_location']['tmp_name']), 
              array(
                  'jpg' => 'image/jpg',
                  'jpeg' => 'image/jpeg',
                  'png' => 'image/png'
              ), true )) {
                $uploadFailedMessage.="Only genuine JPG, JPEG, & PNG image types are allowed! ";
                $uploadOk = 0;
            }

            // Check file size (Max: 10MB | 10000000 Bytes)
            if ($_FILES["upd_plotb4_image_location"]["size"] > 10000000) {
              $uploadFailedMessage.="Image file is too large! (Max: 10MB) ";
              $uploadOk = 0;
            }

            //Update image if pass
            if($uploadOk ==  1){
              $updPlotB4Image=basename($_FILES['upd_plotb4_image_location']['name']);
              $updPlotB4ImageLocation=$basedir.$updPlotB4Image;
            }
          }
          else{
            $uploadImageExist = 0;
          }

          // Check upload
          if ($uploadOk == 0) {
            echo "<script> window.location.href = 'p2-story.php'; alert('".$uploadFailedMessage."'); </script>";
          } 
          else{
            if($uploadImageExist == 1){

              if(!file_exists($updPlotB4ImageLocation)){
                if(move_uploaded_file($_FILES['upd_plotb4_image_location']['tmp_name'], $updPlotB4ImageLocation)){
                
                  //Remove existing file
                  if(unlink($originalPlotB4ImageLoc)){
                    $sql="UPDATE plot SET plot_paragraph_top =:plotPgTop, plot_paragraph_mid =:plotPgMid, plot_image_caption =:plotCaption, plot_image_location =:plotImageLoc  WHERE plot_id=:plotId";
                    $stmt=$conn->prepare($sql);
                    $stmt->execute([':plotPgTop'=>str_replace('\'','’',$_POST['upd_plotb4_paragraph_top']),
                                    ':plotPgMid'=>str_replace('\'','’',$_POST['upd_plotb4_paragraph_mid']),
                                    ':plotCaption'=>str_replace('\'','’',$_POST['upd_plotb4_image_caption']),
                                    ':plotImageLoc'=>$updPlotB4ImageLocation,
                                    ':plotId'=> $plotb4id ]);
  
                    echo "<script> window.location.href = 'p2-story.php'; alert('Plot Updated Successfully.'); </script>";
                  }
                  else{
                    echo "<script> window.location.href = 'p2-story.php'; alert('Failed to remove existing plot image file!'); </script>";
                  }
                  
                }
                else{
                  echo "<script> window.location.href = 'p2-story.php'; alert('There was an error in uploading the files.'); </script>";
                }
              }
              else{
                echo "<script> window.location.href = 'p2-story.php'; alert('Editing Plot Failed! Image Already Exists!'); </script>";
              }
              
            }
            else{
              $sql="UPDATE plot SET plot_paragraph_top =:plotPgTop, plot_paragraph_mid =:plotPgMid, plot_image_caption =:plotCaption, plot_image_location =:plotImageLoc  WHERE plot_id=:plotId";
              $stmt=$conn->prepare($sql);
              $stmt->execute([':plotPgTop'=>str_replace('\'','’',$_POST['upd_plotb4_paragraph_top']),
                              ':plotPgMid'=>str_replace('\'','’',$_POST['upd_plotb4_paragraph_mid']),
                              ':plotCaption'=>str_replace('\'','’',$_POST['upd_plotb4_image_caption']),
                              ':plotImageLoc'=>$updPlotB4ImageLocation,
                              ':plotId'=> $plotb4id ]);

              echo "<script> window.location.href = 'p2-story.php'; alert('Plot Updated Successfully.'); </script>";
            }
          }
        }
        else{
          echo "<script> window.location.href = 'p2-story.php'; alert('Editing plot failed! Original plot image file CANNOT Be Found.'); </script>";
        }

      }
      else {
        echo "<script> window.location.href = 'p2-story.php'; alert('Editing plot failed! Plot (ID:9) does NOT Exist in plot table.'); </script>";
      }
    }
    else{
      echo "<script> window.location.href = 'p2-story.php'; alert('ERROR: BASE DIRECTORY CANNOT BE FOUND!'); </script>";
    }

  }

  //Edit Plot - Block 5
  if(isset($_POST['submit_edit_plotb5'])){

    //Check if plot exists in table
    $sql="SELECT * FROM plot WHERE plot_id = '{$plotb5id}'";
    $resultPlot=$conn->query($sql);
    $resultPlot->fetch(PDO::FETCH_ASSOC);

    if($resultPlot->rowCount()>0){

      $sql="UPDATE plot SET plot_paragraph_top =:plotPgTop, plot_paragraph_mid =:plotPgMid WHERE plot_id=:plotId";
      $stmt=$conn->prepare($sql);
      $stmt->execute([':plotPgTop'=>str_replace('\'','’',$_POST['upd_plotb5_paragraph_top']),
                      ':plotPgMid'=>str_replace('\'','’',$_POST['upd_plotb5_paragraph_mid']),
                      ':plotId'=> $plotb5id ]);

      echo "<script> window.location.href = 'p2-story.php'; alert('Plot Updated Successfully.'); </script>";
    }
    else {
      echo "<script> window.location.href = 'p2-story.php'; alert('Editing plot failed! Plot (ID:10) does NOT Exist in plot table.'); </script>";
    }

  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no" />
    <title>Kimetsu no Yaiba - Fansite</title>
    <!------ Bootstrap CSS ----->
    <link rel="stylesheet" href="bootstrap-5.1.3-dist/css/bootstrap.css"/>
    <!----- JS and JQuery ----->
    <script src="bootstrap-5.1.3-dist/js/jquery-3.6.0.js"></script>
    <script src="bootstrap-5.1.3-dist/js/bootstrap.js"></script>
    
    <link rel="stylesheet" href="page-stylesheet.css" />
    <link rel="icon" type="image/x-icon" href="assets/kny-icon-logo.ico" />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Lato"
    />
  </head>
  <body>
    <!-- Top Pic -->
    <div class="overflw">
      <div class="intro-pic">
        <img
          src="assets/img/banner02-story.png"
          alt="KnY Story Banner"
          class="image"
        />
      </div>
    </div>

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
              src="assets/kny-logo.png"
              class="kny-sitelogo"
              alt="kny logo"
              width="35px"
            /><i class="navbar-button-sitelogo-text">Knyfansite</i></a
          >
        </div>
        <div id="id-button-hide">
          <a class="navbar-button-container" href="index.php">
            <div class="navbar-button">Home</div>
          </a>
          <a class="navbar-button-container" href="p2-story.php">
            <div class="navbar-button">Story</div>
          </a>
          <a class="navbar-button-container" href="p3-characters.php">
            <div class="navbar-button">Characters</div>
          </a>
          <a class="navbar-button-container" href="p4-episodes.php">
            <div class="navbar-button">Episodes</div>
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
        <a class="dropdown-menu-button" href="p2-story.php">Story</a>
        <a class="dropdown-menu-button" href="p3-characters.php">Characters</a>
        <a class="dropdown-menu-button" href="p4-episodes.php">Episodes</a>
      </div>
    </div>

    <!-- Navigation Bar END -->

    <!-- content -->
    <div class="overflw">
      <div class="story-container">
        <div class="story-setting-container">
          <?php
            $sql="SELECT * FROM story WHERE story_id='{$storyid}'";
            $resultStory=$conn->query($sql);
            $rowStory=$resultStory->fetch(PDO::FETCH_ASSOC);

            //replace special characters for onclick to work
            $storyPTopChange = str_replace("'",'39Si3N9gLe3Q9t', str_replace('"','39DoU3b9Le3Q9t',$rowStory['story_paragraph_top']) );
            $storyPTopChange = str_replace("\r",'36Re3T6u3R6n', str_replace("\n",'36Ne3W6Li3N6e',$storyPTopChange) );

            $storyPMidChange = str_replace("'",'39Si3N9gLe3Q9t', str_replace('"','39DoU3b9Le3Q9t',$rowStory['story_paragraph_mid']) );
            $storyPMidChange = str_replace("\r",'36Re3T6u3R6n', str_replace("\n",'36Ne3W6Li3N6e',$storyPMidChange) );

            $storyImgCaption = str_replace("'",'39Si3N9gLe3Q9t', str_replace('"','39DoU3b9Le3Q9t',$rowStory['story_image_caption']) );
            $storyImgLoc = str_replace("'",'39Si3N9gLe3Q9t', str_replace('"','39DoU3b9Le3Q9t',$rowStory['story_image_location']) );

          ?>
          <!-- Setting -->
          <h2>Setting<?php if($login_access=="Admin") {?><button type="button" class="btn btn-primary" id="id_edit_story_btn" onclick="storyEdit('<?php echo $storyPTopChange; ?>','<?php echo  $storyPMidChange; ?>','<?php echo $storyImgCaption; ?>','<?php echo basename($storyImgLoc); ?>')" data-bs-toggle="modal" data-bs-target="#edit_story_modal">Edit</button><?php }?></h2>

          <div class="story-heading-line"></div>
          <div class="justify">
            <span><?php echo $rowStory['story_paragraph_top']; ?></span>
            <figure class="story-fig-right">
              <img
                src="<?php echo $rowStory['story_image_location']; ?>"
                alt="<?php echo $rowStory['story_image_caption']; ?>"
                class="story-fig-img"
              />
              <figcaption>
                <p><?php echo $rowStory['story_image_caption']; ?></p>
              </figcaption>
            </figure>
            <span><?php echo $rowStory['story_paragraph_mid']; ?></span>
          </div>
        </div>
        <!-- Plot - Block 1 -->
        <div class="story-plot-container">
          <?php
            $sql="SELECT * FROM plot WHERE plot_id='{$plotb1id}'";
            $resultPlot=$conn->query($sql);
            $rowPlot=$resultPlot->fetch(PDO::FETCH_ASSOC);

            //replace special characters for onclick to work
            $plotPMidChange = str_replace("'",'39Si3N9gLe3Q9t', str_replace('"','39DoU3b9Le3Q9t',$rowPlot['plot_paragraph_mid']) );
            $plotPMidChange = str_replace("\r",'36Re3T6u3R6n', str_replace("\n",'36Ne3W6Li3N6e',$plotPMidChange) );

            $plotImgCaption = str_replace("'",'39Si3N9gLe3Q9t', str_replace('"','39DoU3b9Le3Q9t',$rowPlot['plot_image_caption']) );
            $plotImgLoc = str_replace("'",'39Si3N9gLe3Q9t', str_replace('"','39DoU3b9Le3Q9t',$rowPlot['plot_image_location']) );

          ?>
          <h2>Plot<?php if($login_access=="Admin") {?><button type="button" class="btn btn-primary" id="id_edit_plotb1_btn" onclick="plotb1Edit('<?php echo  $plotPMidChange; ?>','<?php echo $plotImgCaption; ?>','<?php echo basename($plotImgLoc); ?>')" data-bs-toggle="modal" data-bs-target="#edit_plotb1_modal">Edit B1</button><?php }?></h2>
          <div class="story-heading-line"></div>
          <div class="plot-block-container">
            <figure class="story-fig-left">
              <img
                src="<?php echo $rowPlot['plot_image_location']; ?>"
                alt="<?php echo $rowPlot['plot_image_caption']; ?>"
              />
              <figcaption>
                <p><?php echo $rowPlot['plot_image_caption']; ?></p>
              </figcaption>
            </figure>
            <p><?php echo $rowPlot['plot_paragraph_mid']; ?></p>
          </div>
          <!-- Plot - Block 2 -->
          <?php
            $sql="SELECT * FROM plot WHERE plot_id='{$plotb2id}'";
            $resultPlot=$conn->query($sql);
            $rowPlot=$resultPlot->fetch(PDO::FETCH_ASSOC);

            //replace special characters for onclick to work
            $plotPMidChange = str_replace("'",'39Si3N9gLe3Q9t', str_replace('"','39DoU3b9Le3Q9t',$rowPlot['plot_paragraph_mid']) );
            $plotPMidChange = str_replace("\r",'36Re3T6u3R6n', str_replace("\n",'36Ne3W6Li3N6e',$plotPMidChange) );

            $plotImgCaption = str_replace("'",'39Si3N9gLe3Q9t', str_replace('"','39DoU3b9Le3Q9t',$rowPlot['plot_image_caption']) );
            $plotImgLoc = str_replace("'",'39Si3N9gLe3Q9t', str_replace('"','39DoU3b9Le3Q9t',$rowPlot['plot_image_location']) );

          ?>
          <?php if($login_access=="Admin") {?><button type="button" class="btn btn-primary" id="id_edit_plotb2_btn" onclick="plotb2Edit('<?php echo  $plotPMidChange; ?>','<?php echo $plotImgCaption; ?>','<?php echo basename($plotImgLoc); ?>')" data-bs-toggle="modal" data-bs-target="#edit_plotb2_modal">Edit B2</button><?php }?>
          <div class="plot-block-container">
            <figure class="story-fig-right">
              <img
                src="<?php echo $rowPlot['plot_image_location']; ?>"
                alt="<?php echo $rowPlot['plot_image_caption']; ?>"
              />
              <figcaption>
                <p><?php echo $rowPlot['plot_image_caption']; ?></p>
              </figcaption>
            </figure>
            <p><?php echo $rowPlot['plot_paragraph_mid']; ?></p>
          </div>
          <!-- Plot - Block 3 -->
          <?php
            $sql="SELECT * FROM plot WHERE plot_id='{$plotb3id}'";
            $resultPlot=$conn->query($sql);
            $rowPlot=$resultPlot->fetch(PDO::FETCH_ASSOC);

            //replace special characters for onclick to work
            $plotPTopChange = str_replace("'",'39Si3N9gLe3Q9t', str_replace('"','39DoU3b9Le3Q9t',$rowPlot['plot_paragraph_top']) );
            $plotPTopChange = str_replace("\r",'36Re3T6u3R6n', str_replace("\n",'36Ne3W6Li3N6e',$plotPTopChange) );

            $plotPMidChange = str_replace("'",'39Si3N9gLe3Q9t', str_replace('"','39DoU3b9Le3Q9t',$rowPlot['plot_paragraph_mid']) );
            $plotPMidChange = str_replace("\r",'36Re3T6u3R6n', str_replace("\n",'36Ne3W6Li3N6e',$plotPMidChange) );

            $plotImgCaption = str_replace("'",'39Si3N9gLe3Q9t', str_replace('"','39DoU3b9Le3Q9t',$rowPlot['plot_image_caption']) );
            $plotImgLoc = str_replace("'",'39Si3N9gLe3Q9t', str_replace('"','39DoU3b9Le3Q9t',$rowPlot['plot_image_location']) );

          ?>
          <?php if($login_access=="Admin") {?><button type="button" class="btn btn-primary" id="id_edit_plotb3_btn" onclick="plotb3Edit('<?php echo $plotPTopChange; ?>','<?php echo  $plotPMidChange; ?>','<?php echo $plotImgCaption; ?>','<?php echo basename($plotImgLoc); ?>')" data-bs-toggle="modal" data-bs-target="#edit_plotb3_modal">Edit B3</button><?php }?>
          <div class="justify plot-block-container">
            <span><?php echo $rowPlot['plot_paragraph_top']; ?></span>
            <figure class="story-fig-left">
              <img
                src="<?php echo $rowPlot['plot_image_location']; ?>"
                alt="<?php echo $rowPlot['plot_image_caption']; ?>"
              />
              <figcaption>
                <p><?php echo $rowPlot['plot_image_caption']; ?></p>
              </figcaption>
            </figure>
            <span><?php echo $rowPlot['plot_paragraph_mid']; ?><br /></span>
          </div>
          <!-- Plot - Block 4 -->
          <?php
            $sql="SELECT * FROM plot WHERE plot_id='{$plotb4id}'";
            $resultPlot=$conn->query($sql);
            $rowPlot=$resultPlot->fetch(PDO::FETCH_ASSOC);

            //replace special characters for onclick to work
            $plotPTopChange = str_replace("'",'39Si3N9gLe3Q9t', str_replace('"','39DoU3b9Le3Q9t',$rowPlot['plot_paragraph_top']) );
            $plotPTopChange = str_replace("\r",'36Re3T6u3R6n', str_replace("\n",'36Ne3W6Li3N6e',$plotPTopChange) );

            $plotPMidChange = str_replace("'",'39Si3N9gLe3Q9t', str_replace('"','39DoU3b9Le3Q9t',$rowPlot['plot_paragraph_mid']) );
            $plotPMidChange = str_replace("\r",'36Re3T6u3R6n', str_replace("\n",'36Ne3W6Li3N6e',$plotPMidChange) );

            $plotImgCaption = str_replace("'",'39Si3N9gLe3Q9t', str_replace('"','39DoU3b9Le3Q9t',$rowPlot['plot_image_caption']) );
            $plotImgLoc = str_replace("'",'39Si3N9gLe3Q9t', str_replace('"','39DoU3b9Le3Q9t',$rowPlot['plot_image_location']) );

          ?>
          
          <div class="justify plot-block-container">
          <?php if($login_access=="Admin") {?><button type="button" class="btn btn-primary" id="id_edit_plotb4_btn" onclick="plotb4Edit('<?php echo $plotPTopChange; ?>','<?php echo  $plotPMidChange; ?>','<?php echo $plotImgCaption; ?>','<?php echo basename($plotImgLoc); ?>')" data-bs-toggle="modal" data-bs-target="#edit_plotb4_modal">Edit B4</button><?php }?>
            <span><br /><?php echo $rowPlot['plot_paragraph_top']; ?></span>
            <figure class="story-fig-right">
              <img
                src="<?php echo $rowPlot['plot_image_location']; ?>"
                alt="<?php echo $rowPlot['plot_image_caption']; ?>"
              />
              <figcaption>
                <p><?php echo $rowPlot['plot_image_caption']; ?></p>
              </figcaption>
            </figure>
            <span><?php echo $rowPlot['plot_paragraph_mid']; ?><br />
            </span>
          </div>
          <!-- Plot - Block 5 -->
          <?php
            $sql="SELECT * FROM plot WHERE plot_id='{$plotb5id}'";
            $resultPlot=$conn->query($sql);
            $rowPlot=$resultPlot->fetch(PDO::FETCH_ASSOC);

            //replace special characters for onclick to work
            $plotPTopChange = str_replace("'",'39Si3N9gLe3Q9t', str_replace('"','39DoU3b9Le3Q9t',$rowPlot['plot_paragraph_top']) );
            $plotPTopChange = str_replace("\r",'36Re3T6u3R6n', str_replace("\n",'36Ne3W6Li3N6e',$plotPTopChange) );

            $plotPMidChange = str_replace("'",'39Si3N9gLe3Q9t', str_replace('"','39DoU3b9Le3Q9t',$rowPlot['plot_paragraph_mid']) );
            $plotPMidChange = str_replace("\r",'36Re3T6u3R6n', str_replace("\n",'36Ne3W6Li3N6e',$plotPMidChange) );

          ?>
          <div class="plot-last plot-block-container">
          <?php if($login_access=="Admin") {?><button type="button" class="btn btn-primary" id="id_edit_plotb5_btn" onclick="plotb5Edit('<?php echo $plotPTopChange; ?>','<?php echo  $plotPMidChange; ?>')" data-bs-toggle="modal" data-bs-target="#edit_plotb5_modal">Edit B5</button><?php }?>
            <p><?php echo $rowPlot['plot_paragraph_top']; ?></p>
            <p><?php echo $rowPlot['plot_paragraph_mid']; ?></p>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <footer class="end-footer">
        <p>
          Copyright © 2022 Kimetsu no Yaiba. All Rights Reserved.<br />
          This site does not store any files on the internet.<br />
          All contents are provided locally.
        </p>
      </footer>
    </div>

    <!--------------------------- edit Story Modal ------------------------>
    <div class="modal fade" id="edit_story_modal" tabindex="-1" role="dialog" aria-labelledby="modal_container" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="modal_container">Edit Setting</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="" method="POST" enctype='multipart/form-data'>
              <div class="mb-3">
                <label class="form-label">Press Enter to add new line. Delete the '&lt;br/>' to remove new line.</label>
                <textarea class="form-control" cols="1" rows="2" id="id_upd_story_paragraph_top" placeholder="Enter Top Paragraph here." name="upd_story_paragraph_top"></textarea>
              </div>  
              <div class="mb-3">
                <textarea class="form-control" cols="1" rows="4" id="id_upd_story_paragraph_mid" placeholder="Enter Mid Paragraph here." name="upd_story_paragraph_mid"></textarea>
              </div>
              <div class="mb-3 mt-3">
                <input type="text" class="form-control" id="id_upd_story_image_caption" placeholder="Enter Image Caption" name="upd_story_image_caption"/>
              </div>
              <div class="mb-3">
                <label class="form-label" id="id_cur_story_image"></label>
                <label class="form-label">Select Image for display on right (Optional - Choosing will overwrite current image.)</label>
                <input type="file" class="upd-txt-width" id="id_upd_story_image_location" name="upd_story_image_location"/>
              </div>
          </div>
          <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="submit_edit_story">Save</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </form>
          </div>
          </div>
      </div>
    </div>

    <!--------------------------- edit Plot B1 Modal ------------------------>
    <div class="modal fade" id="edit_plotb1_modal" tabindex="-1" role="dialog" aria-labelledby="modal_container" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="modal_container">Edit Plot - First Block</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="" method="POST" enctype='multipart/form-data'>
              <div class="mb-3">
                <label class="form-label">Press Enter to add new line. Delete the '&lt;br/>' to remove new line.</label>
                <textarea class="form-control" cols="1" rows="4" id="id_upd_plotb1_paragraph_mid" placeholder="Enter Mid Paragraph here." name="upd_plotb1_paragraph_mid"></textarea>
              </div>
              <div class="mb-3 mt-3">
                <input type="text" class="form-control" id="id_upd_plotb1_image_caption" placeholder="Enter Image Caption" name="upd_plotb1_image_caption"/>
              </div>
              <div class="mb-3">
                <label class="form-label" id="id_cur_plotb1_image"></label>
                <label class="form-label">Select Image for display on left (Optional - Choosing will overwrite current image.)</label>
                <input type="file" class="upd-txt-width" id="id_upd_plotb1_image_location" name="upd_plotb1_image_location"/>
              </div>
          </div>
          <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="submit_edit_plotb1">Save</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </form>
          </div>
          </div>
      </div>
    </div>

    <!--------------------------- edit Plot B2 Modal ------------------------>
    <div class="modal fade" id="edit_plotb2_modal" tabindex="-1" role="dialog" aria-labelledby="modal_container" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="modal_container">Edit Plot - Second Block</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="" method="POST" enctype='multipart/form-data'> 
              <div class="mb-3">
                <label class="form-label">Press Enter to add new line. Delete the '&lt;br/>' to remove new line.</label>
                <textarea class="form-control" cols="1" rows="4" id="id_upd_plotb2_paragraph_mid" placeholder="Enter Mid Paragraph here." name="upd_plotb2_paragraph_mid"></textarea>
              </div>
              <div class="mb-3 mt-3">
                <input type="text" class="form-control" id="id_upd_plotb2_image_caption" placeholder="Enter Image Caption" name="upd_plotb2_image_caption"/>
              </div>
              <div class="mb-3">
                <label class="form-label" id="id_cur_plotb2_image"></label>
                <label class="form-label">Select Image for display on right (Optional - Choosing will overwrite current image.)</label>
                <input type="file" class="upd-txt-width" id="id_upd_plotb2_image_location" name="upd_plotb2_image_location"/>
              </div>
          </div>
          <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="submit_edit_plotb2">Save</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </form>
          </div>
          </div>
      </div>
    </div>

    <!--------------------------- edit Plot B3 Modal ------------------------>
    <div class="modal fade" id="edit_plotb3_modal" tabindex="-1" role="dialog" aria-labelledby="modal_container" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="modal_container">Edit Plot - Third Block</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="" method="POST" enctype='multipart/form-data'> 
              <div class="mb-3">
                <label class="form-label">Press Enter to add new line. Delete the '&lt;br/>' to remove new line.</label>
                <textarea class="form-control" cols="1" rows="2" id="id_upd_plotb3_paragraph_top" placeholder="Enter Top Paragraph here." name="upd_plotb3_paragraph_top"></textarea>
              </div> 
              <div class="mb-3">
                <textarea class="form-control" cols="1" rows="4" id="id_upd_plotb3_paragraph_mid" placeholder="Enter Mid Paragraph here." name="upd_plotb3_paragraph_mid"></textarea>
              </div>
              <div class="mb-3 mt-3">
                <input type="text" class="form-control" id="id_upd_plotb3_image_caption" placeholder="Enter Image Caption" name="upd_plotb3_image_caption"/>
              </div>
              <div class="mb-3">
                <label class="form-label" id="id_cur_plotb3_image"></label>
                <label class="form-label">Select Image for display on left (Optional - Choosing will overwrite current image.)</label>
                <input type="file" class="upd-txt-width" id="id_upd_plotb3_image_location" name="upd_plotb3_image_location"/>
              </div>
          </div>
          <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="submit_edit_plotb3">Save</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </form>
          </div>
          </div>
      </div>
    </div>

    <!--------------------------- edit Plot B4 Modal ------------------------>
    <div class="modal fade" id="edit_plotb4_modal" tabindex="-1" role="dialog" aria-labelledby="modal_container" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="modal_container">Edit Plot - Fourth Block</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="" method="POST" enctype='multipart/form-data'> 
              <div class="mb-3">
                <label class="form-label">Press Enter to add new line. Delete the '&lt;br/>' to remove new line.</label>
                <textarea class="form-control" cols="1" rows="2" id="id_upd_plotb4_paragraph_top" placeholder="Enter Top Paragraph here." name="upd_plotb4_paragraph_top"></textarea>
              </div> 
              <div class="mb-3">
                <textarea class="form-control" cols="1" rows="4" id="id_upd_plotb4_paragraph_mid" placeholder="Enter Mid Paragraph here." name="upd_plotb4_paragraph_mid"></textarea>
              </div>
              <div class="mb-3 mt-3">
                <input type="text" class="form-control" id="id_upd_plotb4_image_caption" placeholder="Enter Image Caption" name="upd_plotb4_image_caption"/>
              </div>
              <div class="mb-3">
                <label class="form-label" id="id_cur_plotb4_image"></label>
                <label class="form-label">Select Image for display on right (Optional - Choosing will overwrite current image.)</label>
                <input type="file" class="upd-txt-width" id="id_upd_plotb4_image_location" name="upd_plotb4_image_location"/>
              </div>
          </div>
          <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="submit_edit_plotb4">Save</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </form>
          </div>
          </div>
      </div>
    </div>

    <!--------------------------- edit Plot B5 Modal ------------------------>
    <div class="modal fade" id="edit_plotb5_modal" tabindex="-1" role="dialog" aria-labelledby="modal_container" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="modal_container">Edit Plot - Fifth Block</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="" method="POST"> 
              <div class="mb-3">
                <label class="form-label">Press Enter to add new line. Delete the '&lt;br/>' to remove new line.</label>
                <textarea class="form-control" cols="1" rows="2" id="id_upd_plotb5_paragraph_top" placeholder="Enter Top Paragraph here." name="upd_plotb5_paragraph_top"></textarea>
              </div> 
              <div class="mb-3">
                <textarea class="form-control" cols="1" rows="4" id="id_upd_plotb5_paragraph_mid" placeholder="Enter Mid Paragraph here." name="upd_plotb5_paragraph_mid"></textarea>
              </div>
          </div>
          <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="submit_edit_plotb5">Save</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </form>
          </div>
          </div>
      </div>
    </div>
    
    <script src="page-script.js"></script>
  </body>
</html>
<script>
  //Send value to story - setting edit modal
  function storyEdit($storyPgTop, $storyPgMid, $storyImgCaption, $storyImgLocBasename){
    //replace changed value to original value
    document.getElementById('id_upd_story_paragraph_top').value = $storyPgTop.replace(/39DoU3b9Le3Q9t/g,"\"").replace(/39Si3N9gLe3Q9t/g,"\'").replace(/36Re3T6u3R6n/g,"\r").replace(/36Ne3W6Li3N6e/g,"\n");
    document.getElementById('id_upd_story_paragraph_mid').value = $storyPgMid.replace(/39DoU3b9Le3Q9t/g,"\"").replace(/39Si3N9gLe3Q9t/g,"\'").replace(/36Re3T6u3R6n/g,"\r").replace(/36Ne3W6Li3N6e/g,"\n");
    document.getElementById('id_upd_story_image_caption').value = $storyImgCaption.replace(/39DoU3b9Le3Q9t/g,"\"").replace(/39Si3N9gLe3Q9t/g,"\'");
    document.getElementById('id_cur_story_image').innerHTML = "Current story image: "+$storyImgLocBasename.replace(/39DoU3b9Le3Q9t/g,"\"").replace(/39Si3N9gLe3Q9t/g,"\'");
  }

  //Send value to plot - block 1 edit modal
  function plotb1Edit($plotPgMid, $plotImgCaption, $plotImgLocBasename){
    //replace changed value to original value
    document.getElementById('id_upd_plotb1_paragraph_mid').value = $plotPgMid.replace(/39DoU3b9Le3Q9t/g,"\"").replace(/39Si3N9gLe3Q9t/g,"\'").replace(/36Re3T6u3R6n/g,"\r").replace(/36Ne3W6Li3N6e/g,"\n");
    document.getElementById('id_upd_plotb1_image_caption').value = $plotImgCaption.replace(/39DoU3b9Le3Q9t/g,"\"").replace(/39Si3N9gLe3Q9t/g,"\'");
    document.getElementById('id_cur_plotb1_image').innerHTML = "Current plot block 1 image: "+$plotImgLocBasename.replace(/39DoU3b9Le3Q9t/g,"\"").replace(/39Si3N9gLe3Q9t/g,"\'");
  }

  //Send value to plot - block 2 edit modal
  function plotb2Edit($plotPgMid, $plotImgCaption, $plotImgLocBasename){
    //replace changed value to original value
    document.getElementById('id_upd_plotb2_paragraph_mid').value = $plotPgMid.replace(/39DoU3b9Le3Q9t/g,"\"").replace(/39Si3N9gLe3Q9t/g,"\'").replace(/36Re3T6u3R6n/g,"\r").replace(/36Ne3W6Li3N6e/g,"\n");
    document.getElementById('id_upd_plotb2_image_caption').value = $plotImgCaption.replace(/39DoU3b9Le3Q9t/g,"\"").replace(/39Si3N9gLe3Q9t/g,"\'");
    document.getElementById('id_cur_plotb2_image').innerHTML = "Current plot block 2 image: "+$plotImgLocBasename.replace(/39DoU3b9Le3Q9t/g,"\"").replace(/39Si3N9gLe3Q9t/g,"\'");
  }

  //Send value to plot - block 3 edit modal
  function plotb3Edit($plotPgTop, $plotPgMid, $plotImgCaption, $plotImgLocBasename){
    //replace changed value to original value
    document.getElementById('id_upd_plotb3_paragraph_top').value = $plotPgTop.replace(/39DoU3b9Le3Q9t/g,"\"").replace(/39Si3N9gLe3Q9t/g,"\'").replace(/36Re3T6u3R6n/g,"\r").replace(/36Ne3W6Li3N6e/g,"\n");
    document.getElementById('id_upd_plotb3_paragraph_mid').value = $plotPgMid.replace(/39DoU3b9Le3Q9t/g,"\"").replace(/39Si3N9gLe3Q9t/g,"\'").replace(/36Re3T6u3R6n/g,"\r").replace(/36Ne3W6Li3N6e/g,"\n");
    document.getElementById('id_upd_plotb3_image_caption').value = $plotImgCaption.replace(/39DoU3b9Le3Q9t/g,"\"").replace(/39Si3N9gLe3Q9t/g,"\'");
    document.getElementById('id_cur_plotb3_image').innerHTML = "Current plot block 3 image: "+$plotImgLocBasename.replace(/39DoU3b9Le3Q9t/g,"\"").replace(/39Si3N9gLe3Q9t/g,"\'");
  }

  //Send value to plot - block 4 edit modal
  function plotb4Edit($plotPgTop, $plotPgMid, $plotImgCaption, $plotImgLocBasename){
    //replace changed value to original value
    document.getElementById('id_upd_plotb4_paragraph_top').value = $plotPgTop.replace(/39DoU3b9Le3Q9t/g,"\"").replace(/39Si3N9gLe3Q9t/g,"\'").replace(/36Re3T6u3R6n/g,"\r").replace(/36Ne3W6Li3N6e/g,"\n");
    document.getElementById('id_upd_plotb4_paragraph_mid').value = $plotPgMid.replace(/39DoU3b9Le3Q9t/g,"\"").replace(/39Si3N9gLe3Q9t/g,"\'").replace(/36Re3T6u3R6n/g,"\r").replace(/36Ne3W6Li3N6e/g,"\n");
    document.getElementById('id_upd_plotb4_image_caption').value = $plotImgCaption.replace(/39DoU3b9Le3Q9t/g,"\"").replace(/39Si3N9gLe3Q9t/g,"\'");
    document.getElementById('id_cur_plotb4_image').innerHTML = "Current plot block 4 image: "+$plotImgLocBasename.replace(/39DoU3b9Le3Q9t/g,"\"").replace(/39Si3N9gLe3Q9t/g,"\'");
  }

  //Send value to plot - block 5 edit modal
  function plotb5Edit($plotPgTop, $plotPgMid){
    //replace changed value to original value
    document.getElementById('id_upd_plotb5_paragraph_top').value = $plotPgTop.replace(/39DoU3b9Le3Q9t/g,"\"").replace(/39Si3N9gLe3Q9t/g,"\'").replace(/36Re3T6u3R6n/g,"\r").replace(/36Ne3W6Li3N6e/g,"\n");
    document.getElementById('id_upd_plotb5_paragraph_mid').value = $plotPgMid.replace(/39DoU3b9Le3Q9t/g,"\"").replace(/39Si3N9gLe3Q9t/g,"\'").replace(/36Re3T6u3R6n/g,"\r").replace(/36Ne3W6Li3N6e/g,"\n");
  }

//Get Caret Position
  function getCaret(ev) { 
    if (ev.selectionStart) { 
        return ev.selectionStart; 
    }
    else if (document.selection) { 
        ev.focus(); 

        var r = document.selection.createRange(); 
        if (r == null) { 
            return 0; 
        } 

        var re = ev.createTextRange(), 
        rc = re.duplicate(); 
        re.moveToBookmark(r.getBookmark()); 
        rc.setEndPoint('EndToStart', re); 

        return rc.text.length; 
    }  
    return 0; 
  }

  //Set Caret Position
  function setCaretPosition(elemId, caretPos) {
    var elem = document.getElementById(elemId);

    if(elem != null) {
        if(elem.createTextRange) {
            var range = elem.createTextRange();
            range.move('character', caretPos);
            range.select();
        }
        else {
            if(elem.selectionStart) {
                elem.focus();
                elem.setSelectionRange(caretPos, caretPos);
            }
            else
                elem.focus();
        }
    }
  }

  //Prevent new line in story setting - 1st textarea when pressed Enter
  $('#id_upd_story_paragraph_top').keypress(
  function(event){
    if (event.which == '13') {
      event.preventDefault();
    }
  });

  // Input Break Line in story setting - 1st textarea when pressed Enter
  $(function (){
    $('#id_upd_story_paragraph_top').keyup(function (e){
        if(e.keyCode == 13){
            var curr = getCaret(this);
            var val = $(this).val();
            var end = val.length;
            $(this).val( val.substr(0, curr) + '<br/>' + val.substr(curr, end));
            var prev = curr+5;
            setCaretPosition("id_upd_story_paragraph_top", prev);
        }
    })
  });

  //Prevent new line in story setting - 2nd textarea when pressed Enter
  $('#id_upd_story_paragraph_mid').keypress(
  function(event){
    if (event.which == '13') {
      event.preventDefault();
    }
  });

  // Input Break Line in story setting - 2nd textarea when pressed Enter
  $(function (){
    $('#id_upd_story_paragraph_mid').keyup(function (e){
        if(e.keyCode == 13){
            var curr = getCaret(this);
            var val = $(this).val();
            var end = val.length;
            $(this).val( val.substr(0, curr) + '<br/>' + val.substr(curr, end));
            var prev = curr+5;
            setCaretPosition("id_upd_story_paragraph_mid", prev);
        }
    })
  });

  //Prevent new line in plot block 1 - textarea when pressed Enter
  $('#id_upd_plotb1_paragraph_mid').keypress(
  function(event){
    if (event.which == '13') {
      event.preventDefault();
    }
  });

  // Input Break Line in plot block 1 - textarea when pressed Enter
  $(function (){
    $('#id_upd_plotb1_paragraph_mid').keyup(function (e){
        if(e.keyCode == 13){
            var curr = getCaret(this);
            var val = $(this).val();
            var end = val.length;
            $(this).val( val.substr(0, curr) + '<br/>' + val.substr(curr, end));
            var prev = curr+5;
            setCaretPosition("id_upd_plotb1_paragraph_mid", prev);
        }
    })
  });

  //Prevent new line in plot block 2 - textarea when pressed Enter
  $('#id_upd_plotb2_paragraph_mid').keypress(
  function(event){
    if (event.which == '13') {
      event.preventDefault();
    }
  });

  // Input Break Line in plot block 2 - textarea when pressed Enter
  $(function (){
    $('#id_upd_plotb2_paragraph_mid').keyup(function (e){
        if(e.keyCode == 13){
            var curr = getCaret(this);
            var val = $(this).val();
            var end = val.length;
            $(this).val( val.substr(0, curr) + '<br/>' + val.substr(curr, end));
            var prev = curr+5;
            setCaretPosition("id_upd_plotb2_paragraph_mid", prev);
        }
    })
  });

  //Prevent new line in plot block 3 - 1st textarea when pressed Enter
  $('#id_upd_plotb3_paragraph_top').keypress(
  function(event){
    if (event.which == '13') {
      event.preventDefault();
    }
  });

  // Input Break Line in plot block 3 - 1st textarea when pressed Enter
  $(function (){
    $('#id_upd_plotb3_paragraph_top').keyup(function (e){
        if(e.keyCode == 13){
            var curr = getCaret(this);
            var val = $(this).val();
            var end = val.length;
            $(this).val( val.substr(0, curr) + '<br/>' + val.substr(curr, end));
            var prev = curr+5;
            setCaretPosition("id_upd_plotb3_paragraph_top", prev);
        }
    })
  });

  //Prevent new line in plot block 3 - 2nd textarea when pressed Enter
  $('#id_upd_plotb3_paragraph_mid').keypress(
  function(event){
    if (event.which == '13') {
      event.preventDefault();
    }
  });

  // Input Break Line in plot block 3 - 2nd textarea when pressed Enter
  $(function (){
    $('#id_upd_plotb3_paragraph_mid').keyup(function (e){
        if(e.keyCode == 13){
            var curr = getCaret(this);
            var val = $(this).val();
            var end = val.length;
            $(this).val( val.substr(0, curr) + '<br/>' + val.substr(curr, end));
            var prev = curr+5;
            setCaretPosition("id_upd_plotb3_paragraph_mid", prev);
        }
    })
  });

  //Prevent new line in plot block 4 - 1st textarea when pressed Enter
  $('#id_upd_plotb4_paragraph_top').keypress(
  function(event){
    if (event.which == '13') {
      event.preventDefault();
    }
  });

  // Input Break Line in plot block 4 - 1st textarea when pressed Enter
  $(function (){
    $('#id_upd_plotb4_paragraph_top').keyup(function (e){
        if(e.keyCode == 13){
            var curr = getCaret(this);
            var val = $(this).val();
            var end = val.length;
            $(this).val( val.substr(0, curr) + '<br/>' + val.substr(curr, end));
            var prev = curr+5;
            setCaretPosition("id_upd_plotb4_paragraph_top", prev);
        }
    })
  });

  //Prevent new line in plot block 4 - 2nd textarea when pressed Enter
  $('#id_upd_plotb4_paragraph_mid').keypress(
  function(event){
    if (event.which == '13') {
      event.preventDefault();
    }
  });

  // Input Break Line in plot block 4 - 2nd textarea when pressed Enter
  $(function (){
    $('#id_upd_plotb4_paragraph_mid').keyup(function (e){
        if(e.keyCode == 13){
            var curr = getCaret(this);
            var val = $(this).val();
            var end = val.length;
            $(this).val( val.substr(0, curr) + '<br/>' + val.substr(curr, end));
            var prev = curr+5;
            setCaretPosition("id_upd_plotb4_paragraph_mid", prev);
        }
    })
  });

  //Prevent new line in plot block 5 - 1st textarea when pressed Enter
  $('#id_upd_plotb5_paragraph_top').keypress(
  function(event){
    if (event.which == '13') {
      event.preventDefault();
    }
  });

  // Input Break Line in plot block 5 - 1st textarea when pressed Enter
  $(function (){
    $('#id_upd_plotb5_paragraph_top').keyup(function (e){
        if(e.keyCode == 13){
            var curr = getCaret(this);
            var val = $(this).val();
            var end = val.length;
            $(this).val( val.substr(0, curr) + '<br/>' + val.substr(curr, end));
            var prev = curr+5;
            setCaretPosition("id_upd_plotb5_paragraph_top", prev);
        }
    })
  });

  //Prevent new line in plot block 5 - 2nd textarea when pressed Enter
  $('#id_upd_plotb5_paragraph_mid').keypress(
  function(event){
    if (event.which == '13') {
      event.preventDefault();
    }
  });

  // Input Break Line in plot block 5 - 2nd textarea when pressed Enter
  $(function (){
    $('#id_upd_plotb5_paragraph_mid').keyup(function (e){
        if(e.keyCode == 13){
            var curr = getCaret(this);
            var val = $(this).val();
            var end = val.length;
            $(this).val( val.substr(0, curr) + '<br/>' + val.substr(curr, end));
            var prev = curr+5;
            setCaretPosition("id_upd_plotb5_paragraph_mid", prev);
        }
    })
  });


</script>
