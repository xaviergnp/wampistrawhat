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
  if(!$row=$stmt->fetch(PDO::FETCH_ASSOC)|| $stmt->rowCount()<1){
    session_destroy();
    header("location: login.php");
  }

  $user_check=$_SESSION['user'];
  $sql="SELECT * FROM users WHERE username='$user_check'";
  $stmt=$conn->query($sql);
  $row=$stmt->fetch(PDO::FETCH_ASSOC);
  $login_user=$row['first_name']." ".$row['last_name'];
  $login_access=$row['account_type'];

  //switch season table
  if(isset($_POST['season_table_id'])){
    $seasonid = $_POST['season_table_id'];
    $sql="SELECT * FROM episodes WHERE season_id = '{$seasonid}' ORDER BY episode_number";
    $resultEpisode=$conn->query($sql);
    $rowEpCheck=$resultEpisode->fetch(PDO::FETCH_ASSOC);
    //check if empty
    if(empty($rowEpCheck['episode_id'])){
      ?>
      <tr class="episode-table-row">
        <td colspan="3" class="no-eps-added">No Highlights Added</td>
      </tr>
      <?php
    }
    else{
      //season table row -  episode list
      $sql="SELECT * FROM episodes WHERE season_id = '{$seasonid}' ORDER BY episode_number";
      $resultEpisode=$conn->query($sql);
      while($rowEp=$resultEpisode->fetch(PDO::FETCH_ASSOC)){

        //replace special characters for onclick to work
        $epTitleChange = str_replace("'",'39Si3N9gLe3Q9t', str_replace('"','39DoU3b9Le3Q9t',$rowEp['episode_title']) );
        $epTitleJapChange = str_replace("'",'39Si3N9gLe3Q9t', str_replace('"','39DoU3b9Le3Q9t',$rowEp['episode_title_jap']) );
        $epVidNameChange = str_replace("'",'39Si3N9gLe3Q9t', str_replace('"','39DoU3b9Le3Q9t',$rowEp['episode_video_name']) );
        $epVidLocChange = str_replace("'",'39Si3N9gLe3Q9t', str_replace('"','39DoU3b9Le3Q9t',$rowEp['episode_video_location']) );
        $epVidThumbnailNameChange = str_replace("'",'39Si3N9gLe3Q9t', str_replace('"','39DoU3b9Le3Q9t',$rowEp['episode_video_thumbnail_name']) );
        $epVidThumbnailLocChange = str_replace("'",'39Si3N9gLe3Q9t', str_replace('"','39DoU3b9Le3Q9t',$rowEp['episode_video_thumbnail_location']) );
        ?>
        <tr class="episode-table-row">
          <td class="ep-jmp eptable-epnum-col"><?php echo $rowEp['episode_number']; ?></td>
          <td class="ep-jmp eptable-eptitle-col">
              <p><?php echo $rowEp['episode_title']; ?></p>
              <i><?php echo $rowEp['episode_title_jap']; ?></i>
              <video
              src="<?php echo $rowEp['episode_video_location']; ?>"
              poster="<?php echo $rowEp['episode_video_thumbnail_location']; ?>"
              ></video>
          </td>
          <?php if($login_access=="Admin") {?>
          <td class="eptable-epaction-col">
              <div class="ep-action-btn">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#edit_episode_modal"
                onclick="episodeEdit('<?php echo $rowEp['episode_number']; ?>', '<?php echo $epTitleChange; ?>', 
                '<?php echo $epTitleJapChange; ?>', '<?php echo $epVidNameChange; ?>', '<?php echo $epVidLocChange; ?>',
                '<?php echo $epVidThumbnailNameChange; ?>', '<?php echo $epVidThumbnailLocChange; ?>', '<?php echo $rowEp['episode_id']; ?>', '<?php echo $rowEp['season_id']; ?>' )">Edit</button>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete_episode_modal" 
                onclick="episodeDelete('<?php echo $rowEp['episode_id']; ?>','<?php echo $rowEp['episode_number']; ?>','<?php echo $epTitleChange; ?>')">Delete</button>
              </div>
          </td>
          <?php }?>
        </tr>
      <?php }
    }

    exit;
  }

  //switch season table - set default video - first episode of selected season
  if(isset($_POST['season_video_id'])){ 

    $seasonid = $_POST['season_video_id'];
    $sqlssn = "SELECT * FROM episodes WHERE season_id='$seasonid' ORDER BY episode_number LIMIT 1";
    $stmtssn = $conn->query($sqlssn);
    $rowssn = $stmtssn->fetch(PDO::FETCH_ASSOC);

    //check if empty
    if(empty($rowssn['episode_id'])){
      ?>
      <video
        src=""
        poster=""
        class="kny-play-vid"
        id="kny-vid-jump-id"
        controls
      ></video>
      <div class="episode-text-container">
        <i class="episode-text-ep">No Episode Added</i>
      </div>
      <?php
    }
    else{
      //display episode video
      $seasonEpid = $rowssn['episode_id'];

      $seasonEpnum = $rowssn['episode_number'];
      $seasonEptitle = $rowssn['episode_title'];
      $seasonEptitlejap = $rowssn['episode_title_jap'];
      $seasonEpvidloc = $rowssn['episode_video_location'];
      $seasonEpthumbloc = $rowssn['episode_video_thumbnail_location'];
      ?>
      <video
        src="<?php echo $seasonEpvidloc; ?>"
        poster="<?php echo $seasonEpthumbloc; ?>"
        class="kny-play-vid"
        id="kny-vid-jump-id"
        controls
      ></video>
      <div class="episode-text-container">
        <i class="episode-text-ep">Episode </i>
        <i class="episode-text-epNumber"><?php echo $seasonEpnum; ?></i>
        <i class="episode-text-epHyphen"> - </i>
        <i class="episode-text-epTitle"><?php echo $seasonEptitle; ?></i><br />
        <i class="episode-text-epTitletext"><?php echo $seasonEptitlejap; ?></i>
      </div>
      <?php
    }
    
    exit;
  }

  //switch season table - set episode buttons
  if(isset($_POST['season_button_id'])){

    $seasonid = $_POST['season_button_id'];
    $sql="SELECT * FROM episodes WHERE season_id = '{$seasonid}' ORDER BY episode_number";
    $resultEpisode=$conn->query($sql);
    $rowEp=$resultEpisode->fetch(PDO::FETCH_ASSOC);

    //check if empty
    if(empty($rowEp)){
      ?>
        <div class="ep-button">
          <i class="ep-button-text">#</i>
          <p></p>
          <i></i>
          <video
            src=""
            poster=""
            class="ep-button-link"
          ></video>
        </div>
      <?php
    }
    else{
      //display episode buttons
      $sql="SELECT * FROM episodes WHERE season_id = '{$seasonid}' ORDER BY episode_number";
      $resultEpisode=$conn->query($sql);
      while($rowEp=$resultEpisode->fetch(PDO::FETCH_ASSOC)){
        ?>
          <div class="ep-button">
            <i class="ep-button-text"><?php echo $rowEp['episode_number']; ?></i>
            <p><?php echo $rowEp['episode_title']; ?></p>
            <i><?php echo $rowEp['episode_title_jap']; ?></i>
            <video
              src="<?php echo $rowEp['episode_video_location']; ?>"
              poster="<?php echo $rowEp['episode_video_thumbnail_location']; ?>"
              class="ep-button-link"
            ></video>
          </div>
        <?php }
    }
    
    exit;
  }

  //default season - first season
  $sqlssn = "SELECT * FROM season ORDER BY season_name LIMIT 1";
  $stmtssn = $conn->query($sqlssn);
  $rowssn = $stmtssn->fetch(PDO::FETCH_ASSOC);

  //check if empty
  if(!empty($rowssn['season_id'])){
    $seasonid = $rowssn['season_id'];
    $seasonname = $rowssn['season_name'];

    $sqlssn = "SELECT * FROM episodes WHERE season_id='$seasonid' ORDER BY episode_number LIMIT 1";
    $stmtssn = $conn->query($sqlssn);
    $rowssn = $stmtssn->fetch(PDO::FETCH_ASSOC);

    //check if empty
    if(!empty($rowssn['episode_id'])){
      $seasonEpid = $rowssn['episode_id'];
      $seasonEpnum = $rowssn['episode_number'];
      $seasonEptitle = $rowssn['episode_title'];
      $seasonEptitlejap = $rowssn['episode_title_jap'];
      $seasonEpvidloc = $rowssn['episode_video_location'];
      $seasonEpthumbloc = $rowssn['episode_video_thumbnail_location'];
    }
  }
 
  //Add Season
  if(isset($_POST['submit_add_season'])){
    
    $basedir="uploads/vid/";

    //Check if directory exists
    if(file_exists($basedir)){

      //Check if season name is valid file name
      if(!preg_match('/[.\/:\*\?\"<>|\\\]/', $_POST['inp_season_name'])){

        $seasonLocation = $basedir.$_POST['inp_season_name'];
        $targetdir=$basedir.$_POST['inp_season_name'];
        $ssnName = str_replace("'","''", $_POST['inp_season_name']);
        $dupSeasonMsg="Adding Season Failed! ";
        $dupSeasonCheck=1;

        $sql="SELECT * FROM season";
        $resultSeason=$conn->query($sql);
        while($rowSeason = $resultSeason->fetch(PDO::FETCH_ASSOC)){
          if ( preg_replace('/\s+/', '', strtolower($rowSeason['season_name'])) == preg_replace('/\s+/', '', strtolower($ssnName))){
            $dupSeasonCheck=0;
            $dupSeasonMsg.="Season Name Already Exists! ";
            break;
          }
        }

        // $sql="SELECT * FROM season WHERE season_name = '{$ssnName}'";
        // $resultSeason=$conn->query($sql);
        // $resultSeason->fetch(PDO::FETCH_ASSOC);
        // if($resultSeason->rowCount()>0){
        //   $dupSeasonCheck=0;
        //   $dupSeasonMsg.="Season Name Already Exists! ";
        // }
        if(file_exists($targetdir)){
          $dupSeasonCheck=0;
          $dupSeasonMsg.="Season Folder Already Exists! ";
        }

        if($dupSeasonCheck==1){
          //Create Season Folder with a subfolder for ep thumbnail
          if(mkdir($targetdir."/ep_thumb", 0755, true)){
            //Add Season
            $sql="INSERT INTO season (season_name, season_location) 
            VALUES (:ssnName, :ssnLoc)";
                $stmt=$conn->prepare($sql);
                $stmt->execute([':ssnName'=>$_POST['inp_season_name'],
                                ':ssnLoc'=>$seasonLocation
                              ]);
                              
            echo "<script> window.location.href = 'p4-episodes.php'; alert('Character Highlights Added Successfully.'); </script>";
          }
          else{
            echo "<script> window.location.href = 'p4-episodes.php'; alert('Adding Character Highlights Failed! There was an error in creating the Season Folder.'); </script>";
          }
          
        }
        else{
          echo "<script> window.location.href = 'p4-episodes.php'; alert('".$dupSeasonMsg."'); </script>";
        }

      }
      else{
        echo "<script> window.location.href = 'p4-episodes.php'; alert('Season Name has Invalid characters! File name CANNOT contain any of these characters: \\\ / : * ? \" < > | '); </script>";
      }

    }
    else{
      echo "<script> window.location.href = 'p4-episodes.php'; alert('ERROR: BASE DIRECTORY CANNOT BE FOUND!'); </script>";
    }

  }

  //Edit Season
  if(isset($_POST['submit_edit_season'])){

    //Set Season Folder
    $basedir="uploads/vid/";

    //Check if directory exists
    if(file_exists($basedir)){

      //Check if season name is valid file name
      if(!preg_match('/[.\/:\*\?\"<>|\\\]/', $_POST['upd_season_name'])){
        $targetdir=$basedir.$_POST['upd_season_name'];
        $seasonNameOld = $basedir.$_POST['upd_season_name_old'];
        $folderExistMessage="Editing Season Failed! ";
        $existCheck=1;

        $dupSeasonIdCheck = $_POST['upd_season_name_ID'];
        $sql="SELECT * FROM season WHERE season_id = '{$dupSeasonIdCheck}'";
        $resultSeason=$conn->query($sql);
        $rowSeason=$resultSeason->fetch(PDO::FETCH_ASSOC);
        $dupSeasonPrevNameCheck =$rowSeason['season_name'];
        $dupSeasonUpdNameCheck =$_POST['upd_season_name'];

        $sql="SELECT * FROM season";
        $resultSeason=$conn->query($sql);
        while($rowSeason = $resultSeason->fetch(PDO::FETCH_ASSOC)){
          if ($rowSeason['season_id'] != $dupSeasonIdCheck && preg_replace('/\s+/', '', strtolower($rowSeason['season_name'])) == preg_replace('/\s+/', '', strtolower($dupSeasonUpdNameCheck))){
            $existCheck=0;
            $folderExistMessage.="Season Name Already Exists! ";
            break;
          }
        }

        // $sql="SELECT * FROM season WHERE season_name = '{$dupSeasonUpdNameCheck}'";
        // $resultSeason=$conn->query($sql);
        // $resultSeason->fetch(PDO::FETCH_ASSOC);
        // if(($dupSeasonPrevNameCheck != $dupSeasonUpdNameCheck) && $resultSeason->rowCount()>0){
        //   $existCheck=0;
        //   $folderExistMessage.="Season Name Already Exists! ";
        // }

        //Check if Folders Exist
        if(!file_exists($seasonNameOld)){
          $existCheck=0;
          $folderExistMessage.="Previous Season Folder does NOT Exist! ";
        }
        if(file_exists($targetdir)){
          $existCheck=0;
          $folderExistMessage.="Season Folder Already Exists! ";
        }

        if($existCheck==1) {

          //Check if Season exists in season table
          $sql = "SELECT * FROM season WHERE season_id = '{$_POST['upd_season_name_ID']}'";
          $resultSeason=$conn->query($sql);
          $resultSeason->fetch(PDO::FETCH_ASSOC);
          $queryCheck = 1;
          $queryCheckMessage="";
          if($resultSeason->rowCount()<1){
            $queryCheck = 0;
            $queryCheckMessage.="Character highlights does NOT Exist in Character table ";
          }

          if($queryCheck == 1){
            //Rename season folder and Change Directory
            rename($seasonNameOld, $targetdir);

            //Edit Season
            $sql="UPDATE season SET season_name =:ssnName, season_location =:ssnLoc WHERE season_id=:updSsnId";
            $stmt=$conn->prepare($sql);
            $stmt->execute([':ssnName'=>$_POST['upd_season_name'],
                            ':ssnLoc'=>$targetdir,
                            ':updSsnId'=>$_POST['upd_season_name_ID']]);
            
            // Edit Episodes Location
            $sql1="SELECT * FROM episodes WHERE season_id = '{$_POST['upd_season_name_ID']}'";
            $resultEpisode=$conn->query($sql1);
            while($rowEp=$resultEpisode->fetch(PDO::FETCH_ASSOC)){
              $sql="UPDATE episodes SET episode_video_location =:epsLoc, episode_video_thumbnail_location =:epsThumbLoc WHERE episode_id=:epId";
              $stmt=$conn->prepare($sql);
              $stmt->execute([':epsLoc'=>$targetdir."/".$rowEp['episode_video_name'],
                              ':epsThumbLoc'=>$targetdir."/ep_thumb"."/".$rowEp['episode_video_thumbnail_name'],
                              ':epId'=>$rowEp['episode_id']]);
            }
            echo "<script> window.location.href = 'p4-episodes.php'; alert('Season Updated Successfully.'); </script>";
          }
          else {
            echo "<script> window.location.href = 'p4-episodes.php'; alert('Failed to edit. ".$queryCheckMessage."'); </script>";
          }
        }
        else{
          echo "<script> window.location.href = 'p4-episodes.php'; alert('".$folderExistMessage."'); </script>";
        }

      }
      else{
        echo "<script> window.location.href = 'p4-episodes.php'; alert('Season Name has Invalid characters! File name CANNOT contain any of these characters: \\\ / : * ? \" < > | '); </script>";
      }

    }
    else{
      echo "<script> window.location.href = 'p4-episodes.php'; alert('ERROR: BASE DIRECTORY CANNOT BE FOUND!'); </script>";
    }

  }

  //Delete Season
  if(isset($_POST['submit_delete_season'])){
    
    $basedir="uploads/vid/";
    
    //Check if directory exists
    if(file_exists($basedir)){

      $ssnId = $_POST["inp_delete_season"];
      $sql="SELECT * FROM season WHERE season_id = '{$ssnId}'";
      $resultSeason=$conn->query($sql);
      $resultSeason->fetch(PDO::FETCH_ASSOC);

      if($resultSeason->rowCount()>0){
        
        $ssnId = $_POST["inp_delete_season"];
        $sql="SELECT * FROM season WHERE season_id = '{$ssnId}'";
        $resultSeason=$conn->query($sql);
        $rowSeason=$resultSeason->fetch(PDO::FETCH_ASSOC);
        $seasonName =$rowSeason['season_name'];

        //Set Delete Season Folder
        $targetdir=$basedir.$seasonName;

        //Check Delete Season Folder
        if(file_exists($targetdir)){

          $deleteSuccess=1;

          //Delete function
          function rrmdir($dir) {
            if (is_dir($dir)) {
              $objects = scandir($dir);
              foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                  if (filetype($dir."/".$object) == "dir") 
                    rrmdir($dir."/".$object); 
                  else {
                    if (!unlink($dir."/".$object)){
                      $deleteSuccess=0;
                    }
                  }
                }
              }
              reset($objects);
              rmdir($dir);
              // if(rmdir($dir)){
              //     $deletecount=1;
              // }
            }
          }

          //Use Delete function
          rrmdir($targetdir);

          //Check if deleted
          if($deleteSuccess == 0){
            echo "<script> window.location.href = 'p4-episodes.php'; alert('Deletion Failed. There was an error in deleting the folder.'); </script>";
          }
          else{

            //Delete Season
            $sql="DELETE FROM season WHERE season_id=:ssnid;";
            $stmt=$conn->prepare($sql);
            $stmt->execute([':ssnid'=>$ssnId]);

            echo "<script> window.location.href = 'p4-episodes.php'; alert('Character Highlights Deleted Successfully.'); </script>";
          }

        }
        else{
          echo "<script> window.location.href = 'p4-episodes.php'; alert('Failed to delete. Folder ".$seasonName." does NOT Exist!'); </script>";
        }

      }
      else {
        echo "<script> window.location.href = 'p4-episodes.php'; alert('Character highlights does NOT Exist in the character table!'); </script>";
      }

    }
    else{
      echo "<script> window.location.href = 'p4-episodes.php'; alert('ERROR: BASE DIRECTORY CANNOT BE FOUND!'); </script>";
    }
    
  }

  //Add Episode
  if(isset($_POST['submit_add_episode'])){

    $sql="SELECT * FROM season WHERE season_id = '{$_POST['inp_episode_season_id']}'";
    $resultSeason=$conn->query($sql);
    $resultSeason->fetch(PDO::FETCH_ASSOC);
    if($resultSeason->rowCount()>0){
      $selectedSeasonId = $_POST['inp_episode_season_id'];
      $sql="SELECT * FROM season WHERE season_id = '{$_POST['inp_episode_season_id']}'";
      $resultSeason=$conn->query($sql);
      $rowSeason=$resultSeason->fetch(PDO::FETCH_ASSOC);
      $seasonName = $rowSeason['season_name'];
      $seasonLocation = $rowSeason['season_location'];

      if(file_exists($seasonLocation)){
        //Set upload episode video Location
        $targetEpLoc = $seasonLocation."/".basename($_FILES["inp_episode_video"]["name"]);

        //Check if folder exists

        // $videoFileType = strtolower(pathinfo($targetEpLoc,PATHINFO_EXTENSION));
        
        $uploadOk = 1;
        $uploadFailedMessage="Adding episode Failed! ";
        // 
        // if (file_exists($target_file)) {
        //   $uploadMessage.="Video File already exists! ";
        //   $uploadOk = 0;
        // }
        
        //Check if video is Undefined /  Multiple Files / Corrupt
        if (!isset($_FILES['inp_episode_video']['error']) || is_array($_FILES['inp_episode_video']['error'])) {
          $uploadFailedMessage.="Invalid Video Parameters! ";
          $uploadOk = 0;
        }

        //Check if it's an actual video
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        if (false === $ext = array_search( 
          $finfo->file($_FILES["inp_episode_video"]["tmp_name"]), 
          array(
              'mp4' => 'video/mp4'
          ), true )) {
            $uploadFailedMessage.="Only genuine MP4 video type is allowed! ";
            $uploadOk = 0;
        }

        // Check file size (Max: 2GB | 2147483648 Bytes)
        if ($_FILES['inp_episode_video']['size'] > 2147483648) {
          $uploadFailedMessage.="Video file is too large! (Max: 2GB) ";
          $uploadOk = 0;
        }

        // Check file format
        // if($videoFileType != "mp4") {
        //   $uploadFailedMessage.="Only MP4 video files are allowed! ";
        //   $uploadOk = 0;
        // }

        $epThumbnailLoc = $seasonLocation."/ep_thumb";
        if(!file_exists($epThumbnailLoc)){
          mkdir($epThumbnailLoc, 0755, true);
        }
        //Upload episode thumbnail
        $targetEpThumbLoc = $seasonLocation."/ep_thumb"."/".basename($_FILES["inp_episode_thumbnail"]["name"]);
        // $imageFileType = strtolower(pathinfo($targetEpThumbLoc,PATHINFO_EXTENSION));

        //Check if image is Undefined /  Multiple Files / Corrupt
        if (!isset($_FILES['inp_episode_thumbnail']['error']) || is_array($_FILES['inp_episode_thumbnail']['error'])) {
          $uploadFailedMessage.="Invalid Image Parameters! ";
          $uploadOk = 0;
        }

        //Check if it's an actual image
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        if (false === $ext = array_search( 
          $finfo->file($_FILES["inp_episode_thumbnail"]["tmp_name"]), 
          array(
              'jpg' => 'image/jpg',
              'jpeg' => 'image/jpeg',
              'png' => 'image/png'
          ), true )) {
            $uploadFailedMessage.="Only genuine JPG, JPEG, & PNG image types are allowed! ";
            $uploadOk = 0;
        }

        // Check file size (Max: 50MB | 52428800 Bytes)
        if ($_FILES["inp_episode_thumbnail"]["size"] > 52428800) {
          $uploadFailedMessage.="Image file is too large! (Max: 50MB) ";
          $uploadOk = 0;
        }

        // Check file format
        // if(($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg")) {
        //   $uploadFailedMessage.="Only JPG, JPEG, & PNG image files are allowed! ";
        //   $uploadOk = 0;
        // }

        // Check upload
        if ($uploadOk == 0) {
          echo "<script> window.location.href = 'p4-episodes.php'; alert('".$uploadFailedMessage."'); </script>";
        } 
        else {
          $duplicateMsg="Adding Episode Failed! ";
          $dupEpCheck = 1;

          // $dupEpNumCheck = $_POST['inp_episode_number'];
          $dupEpNumCheck = $_POST['inp_episode_title'];
          $dupEpTitleCheck = str_replace('\'','’',$_POST['inp_episode_title']);
          $dupEpTitleJapCheck = str_replace('\'','’',$_POST['inp_episode_title_jap']);
          $dupEpVideoCheck = $targetEpLoc;
          $dupEpThumbnailCheck = $targetEpThumbLoc;

          $sql="SELECT * FROM episodes WHERE episode_number = '{$dupEpNumCheck}' && season_id = '{$selectedSeasonId}'";
          $resultEpisode=$conn->query($sql);
          $resultEpisode->fetch(PDO::FETCH_ASSOC);
          if($resultEpisode->rowCount()>0){
            $dupEpCheck = 0;
            $duplicateMsg.="Episode Number Already Exists! ";
          }

          $sql="SELECT * FROM episodes WHERE season_id = '{$selectedSeasonId}'";
          $resultEpisode=$conn->query($sql);
          while($rowEpisode = $resultEpisode->fetch(PDO::FETCH_ASSOC)){
            if (preg_replace('/\s+/', '', strtolower($rowEpisode['episode_title'])) == preg_replace('/\s+/', '', strtolower($dupEpTitleCheck))){
              $dupEpCheck = 0;
              $duplicateMsg.="Episode Title Already Exists! ";
              break;
            }
          }

          $sql="SELECT * FROM episodes WHERE season_id = '{$selectedSeasonId}'";
          $resultEpisode=$conn->query($sql);
          while($rowEpisode = $resultEpisode->fetch(PDO::FETCH_ASSOC)){
            if (preg_replace('/\s+/', '', strtolower($rowEpisode['episode_title_jap'])) == preg_replace('/\s+/', '', strtolower($dupEpTitleJapCheck))){
              $dupEpCheck = 0;
              $duplicateMsg.="Episode Title - Japanese Already Exists! ";
              break;
            }
          }

          // $sql="SELECT * FROM episodes WHERE episode_title = '{$dupEpTitleCheck}' && season_id = '{$selectedSeasonId}'";
          // $resultEpisode=$conn->query($sql);
          // $resultEpisode->fetch(PDO::FETCH_ASSOC);
          // if($resultEpisode->rowCount()>0){
          //   $dupEpCheck = 0;
          //   $duplicateMsg.="Episode Title Already Exists! ";
          // }

          // $sql="SELECT * FROM episodes WHERE episode_title_jap = '{$dupEpTitleJapCheck}' && season_id = '{$selectedSeasonId}'";
          // $resultEpisode=$conn->query($sql);
          // $resultEpisode->fetch(PDO::FETCH_ASSOC);
          // if($resultEpisode->rowCount()>0){
          //   $dupEpCheck = 0;
          //   $duplicateMsg.="Episode Title - Japanese Already Exists! ";
          // }

          if(file_exists($dupEpVideoCheck)){
            $dupEpCheck = 0;
            $duplicateMsg.="Episode Video Already Exists! ";
          }

          if(file_exists($dupEpThumbnailCheck)){
            $dupEpCheck = 0;
            $duplicateMsg.="Episode Thumbnail Already Exists! ";
          }

          if($dupEpCheck == 1){
            if (move_uploaded_file($_FILES["inp_episode_video"]["tmp_name"], $targetEpLoc) && move_uploaded_file($_FILES["inp_episode_thumbnail"]["tmp_name"], $targetEpThumbLoc)) {
              //Add Episode to table
              
                $sql="INSERT INTO episodes (season_id, 
                -- episode_number, 
                episode_title, episode_title_jap, episode_video_name, episode_video_location, episode_video_thumbnail_name, episode_video_thumbnail_location) 
                VALUES (:ssnId, 
                -- :epNum, 
                :epTitle, :epTitleJap, :epVidName, :epVidLoc, :epVidThumb, :epVidThumbLoc)";
                $stmt=$conn->prepare($sql);
                $stmt->execute([':ssnId'=>$_POST['inp_episode_season_id'],
                                // ':epNum'=>$_POST['inp_episode_number'],
                                ':epTitle'=>str_replace('\'','’',$_POST['inp_episode_title']),
                                ':epTitleJap'=>str_replace('\'','’',$_POST['inp_episode_title_jap']),
                                ':epVidName'=>$_FILES["inp_episode_video"]["name"],
                                ':epVidLoc'=>$targetEpLoc,
                                ':epVidThumb'=>$_FILES["inp_episode_thumbnail"]["name"],
                                ':epVidThumbLoc'=>$targetEpThumbLoc
                              ]);
                echo "<script> window.location.href = 'p4-episodes.php'; alert('Episode Added Successfully.'); </script>";
              } 
              else {
                echo "<script> window.location.href = 'p4-episodes.php'; alert('Adding Episode Failed! There was an error uploading the files.'); </script>";
              }
          }
          else{
            echo "<script> window.location.href = 'p4-episodes.php'; alert('".$duplicateMsg."'); </script>";
          }
          
        }

      }
      else{
        echo "<script> window.location.href = 'p4-episodes.php'; alert('Adding Episode Failed! Season ".$seasonName." Directory/Folder CANNOT Be Found!'); </script>";
      }

    }
    else {
      echo "<script> window.location.href = 'p4-episodes.php'; alert('Adding Highlights Failed! Character highlights does NOT Exist in character table.'); </script>";
    }

  }

  //Edit Episode
  if(isset($_POST['submit_edit_episode'])){

    $originalEpisodeSeasonId = $_POST['upd_episode_season_id'];
    $selectedEpisodeSeasonId = $_POST['upd_episode_season'];

    $episodeseasonCheck=1;
    $episodeseasonCheckMessage="Editing episode failed. ";
    //Check if season exists
    $sql1="SELECT * FROM season WHERE season_id = '{$selectedEpisodeSeasonId}'";
    $resultSeason=$conn->query($sql1);
    $resultSeason->fetch(PDO::FETCH_ASSOC);
    if($resultSeason->rowCount()<1){
      $episodeseasonCheck=0;
      $episodeseasonCheckMessage.="Character does NOT Exist in character table. ";
    }
    
    //Check if episode exists
    $sql2 = "SELECT * FROM episodes WHERE episode_id = '{$_POST['upd_episode_id']}'";
    $resultEpisode=$conn->query($sql2);
    $resultEpisode->fetch(PDO::FETCH_ASSOC);
    if($resultEpisode->rowCount()<1){
      $episodeseasonCheck=0;
      $episodeseasonCheckMessage.="Episode does NOT Exist in episodes table. ";
    }
    
    if( $episodeseasonCheck == 1){
      $sql1="SELECT * FROM season WHERE season_id = '{$selectedEpisodeSeasonId}'";
      $resultSeason=$conn->query($sql1);
      $rowSeason=$resultSeason->fetch(PDO::FETCH_ASSOC);
      $ep_season = $rowSeason['season_name'];
      $ep_season_loc = $rowSeason['season_location'];

      if(file_exists($ep_season_loc)){
        $ep_vid = $_POST['upd_episode_video_name'];
        $ep_vid_loc = $ep_season_loc.'/'.$ep_vid;
        $ep_vid_thumbnail = $_POST['upd_episode_thumbnail_name'];
        $ep_vid_thumbnail_loc = $ep_season_loc.'/ep_thumb'.'/'.$ep_vid_thumbnail;

        $epThumbnailLoc = $ep_season_loc."/ep_thumb";
        if(!file_exists($epThumbnailLoc)){
          mkdir($epThumbnailLoc, 0755, true);
        }

        $originalEpisodeVideoLoc = $_POST['upd_episode_video_loc'];
        $originalEpisodeVideoThumbnailLoc = $_POST['upd_episode_thumbnail_loc'];

        $origfileCheck = 1;
        $origfileMsg = "Episode edit failed. ";
        if(!file_exists($originalEpisodeVideoLoc)){
          $origfileCheck = 0;
          $origfileMsg .= "Episode video directory/file CANNOT Be Found! ";
        }
        if(!file_exists($originalEpisodeVideoThumbnailLoc)){
          $origfileCheck = 0;
          $origfileMsg .= "Episode thumbnail directory/file CANNOT Be Found! ";
        }
        

        if($origfileCheck == 1){

          $uploadVideoExist = 1;
          $uploadThumbnailExist = 1;
          $uploadOk = 1;
          $uploadFailedMessage="Episode not added! ";

          //Check if there's an uploaded Video
          if (file_exists($_FILES['upd_episode_video']['tmp_name']) || is_uploaded_file($_FILES['upd_episode_video']['tmp_name'])) {
            $uploadVideoExist = 1;

            //Check if video is Undefined /  Multiple Files / Corrupt
            if (!isset($_FILES['upd_episode_video']['error']) || is_array($_FILES['upd_episode_video']['error'])) {
              $uploadFailedMessage.="Invalid Video Parameters! ";
              $uploadOk = 0;
            }

            //Check if it's an actual video
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            if (false === $ext = array_search( 
              $finfo->file($_FILES['upd_episode_video']['tmp_name']), 
              array(
                  'mp4' => 'video/mp4'
              ), true )) {
                $uploadFailedMessage.="Only genuine MP4 video type is allowed! ";
                $uploadOk = 0;
            }

            // Check file size (Max: 2GB | 2000000000 Bytes)
            if ($_FILES['upd_episode_video']['size'] > 2000000000) {
              $uploadFailedMessage.="Video file is too large! (Max: 2GB) ";
              $uploadOk = 0;
            }

            //Update video if pass
            if($uploadOk == 1){
              $ep_vid = basename($_FILES['upd_episode_video']['name']);
              $ep_vid_loc = $ep_season_loc.'/'.$ep_vid;
            }

          }
          else{
            $uploadVideoExist = 0;
          }

          //Check if there's an uploaded Thumbnail
          if (file_exists($_FILES['upd_episode_thumbnail']['tmp_name']) || is_uploaded_file($_FILES['upd_episode_thumbnail']['tmp_name'])) {
            $uploadThumbnailExist = 1;

            //Check if image is Undefined /  Multiple Files / Corrupt
            if (!isset($_FILES['upd_episode_thumbnail']['error']) || is_array($_FILES['upd_episode_thumbnail']['error'])) {
              $uploadFailedMessage.="Invalid Image Parameters! ";
              $uploadOk = 0;
            }

            //Check if it's an actual image
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            if (false === $ext = array_search( 
              $finfo->file($_FILES['upd_episode_thumbnail']['tmp_name']), 
              array(
                  'jpg' => 'image/jpg',
                  'jpeg' => 'image/jpeg',
                  'png' => 'image/png'
              ), true )) {
                $uploadFailedMessage.="Only genuine JPG, JPEG, & PNG image types are allowed! ";
                $uploadOk = 0;
            }

            // Check file size (Max: 50MB | 52428800 Bytes)
            if ($_FILES["upd_episode_thumbnail"]["size"] > 52428800) {
              $uploadFailedMessage.="Image file is too large! (Max: 50MB) ";
              $uploadOk = 0;
            }

            //Update image if pass
            if($uploadOk ==  1){
              $ep_vid_thumbnail = basename($_FILES['upd_episode_thumbnail']['name']);
              $ep_vid_thumbnail_loc = $ep_season_loc.'/ep_thumb'.'/'.$ep_vid_thumbnail;
            }

          }else{
            $uploadThumbnailExist = 0;
          }

          // Check upload
          if ($uploadOk == 0) {
            echo "<script> window.location.href = 'p4-episodes.php'; alert('".$uploadFailedMessage."'); </script>";
          } 
          else {
            if($uploadVideoExist == 1 && $uploadThumbnailExist == 1){

              $duplicateMsg="Adding Episode Failed! ";
              $dupEpCheck = 1;

              $dupEpIdCheck = $_POST['upd_episode_id'];
              // $dupEpNumCheck = $_POST['upd_episode_number'];
              $dupEpNumCheck = $_POST['inp_episode_title'];
              $dupEpTitleCheck = str_replace('\'','’',$_POST['upd_episode_title']);
              $dupEpTitleJapCheck = str_replace('\'','’',$_POST['upd_episode_title_jap']);
              $dupEpVideoCheck = $ep_vid_loc;
              $dupEpThumbnailCheck = $ep_vid_thumbnail_loc;

              $sql="SELECT * FROM episodes WHERE episode_id = '{$dupEpIdCheck}'";
              $resultEpisode=$conn->query($sql);
              $rowEpisode = $resultEpisode->fetch(PDO::FETCH_ASSOC);
              $epPrevNum = $rowEpisode['episode_number'];
              $epPrevTitle = $rowEpisode['episode_title'];
              $epPrevTitleJap = $rowEpisode['episode_title_jap'];

              $sql="SELECT * FROM episodes WHERE episode_number = '{$dupEpNumCheck}' AND season_id = '{$selectedEpisodeSeasonId}'";
              $resultEpisode=$conn->query($sql);
              $resultEpisode->fetch(PDO::FETCH_ASSOC);
              if(($epPrevNum != $dupEpNumCheck) && $resultEpisode->rowCount()>0){
                $dupEpCheck = 0;
                $duplicateMsg.="Episode Number Already Exists! ";
              }

              $sql="SELECT * FROM episodes WHERE season_id = '{$selectedEpisodeSeasonId}'";
              $resultEpisode=$conn->query($sql);
              while($rowEpisode = $resultEpisode->fetch(PDO::FETCH_ASSOC)){
                if ($rowEpisode['episode_id'] != $_POST['upd_episode_id'] && preg_replace('/\s+/', '', strtolower($rowEpisode['episode_title'])) == preg_replace('/\s+/', '', strtolower($dupEpTitleCheck))){
                  $dupEpCheck = 0;
                  $duplicateMsg.="Episode Title Already Exists! ";
                  break;
                }
              }

              $sql="SELECT * FROM episodes WHERE season_id = '{$selectedEpisodeSeasonId}'";
              $resultEpisode=$conn->query($sql);
              while($rowEpisode = $resultEpisode->fetch(PDO::FETCH_ASSOC)){
                if ($rowEpisode['episode_id'] != $_POST['upd_episode_id'] && preg_replace('/\s+/', '', strtolower($rowEpisode['episode_title_jap'])) == preg_replace('/\s+/', '', strtolower($dupEpTitleJapCheck))){
                  $dupEpCheck = 0;
                  $duplicateMsg.="Episode Title - Japanese Already Exists! ";
                  break;
                }
              }

              // $sql="SELECT * FROM episodes WHERE episode_title = '{$dupEpTitleCheck}' AND season_id = '{$selectedEpisodeSeasonId}'";
              // $resultEpisode=$conn->query($sql);
              // $resultEpisode->fetch(PDO::FETCH_ASSOC);
              // if(($epPrevTitle != $dupEpTitleCheck) && $resultEpisode->rowCount()>0){
              //   $dupEpCheck = 0;
              //   $duplicateMsg.="Episode Title Already Exists! ";
              // }

              // $sql="SELECT * FROM episodes WHERE episode_title_jap = '{$dupEpTitleJapCheck}' AND season_id = '{$selectedEpisodeSeasonId}'";
              // $resultEpisode=$conn->query($sql);
              // $resultEpisode->fetch(PDO::FETCH_ASSOC);
              // if(($epPrevTitleJap != $dupEpTitleJapCheck) && $resultEpisode->rowCount()>0){
              //   $dupEpCheck = 0;
              //   $duplicateMsg.="Episode Title - Japanese Already Exists! ";
              // }

              if(file_exists($dupEpVideoCheck)){
                $dupEpCheck = 0;
                $duplicateMsg.="Episode Video Already Exists! ";
              }

              if(file_exists($dupEpThumbnailCheck)){
                $dupEpCheck = 0;
                $duplicateMsg.="Episode Thumbnail Already Exists! ";
              }

              if($dupEpCheck == 1){
                if (move_uploaded_file($_FILES["upd_episode_video"]["tmp_name"], $ep_vid_loc) && move_uploaded_file($_FILES["upd_episode_thumbnail"]["tmp_name"], $ep_vid_thumbnail_loc)) {
                
                  //Remove existing files
                  $removeMsg="Episode edit failed. ";
                  $removeCheck=1;
                  if(!unlink($originalEpisodeVideoLoc)){
                    $removeMsg.="Failed to remove existing episode Video file! ";
                    $deleteCheck=0;
                  }
                  if(!unlink($originalEpisodeVideoThumbnailLoc)){
                    $removeMsg.="Failed to remove existing episode Image thumbnail file! ";
                    $removeCheck=0;
                  }
  
                  if($removeCheck == 1){
                      
                    //Edit Episode
                    $sql2="UPDATE episodes SET episode_number =:epNum, episode_title =:epTitle, episode_title_jap =:epTitleJap, episode_video_name =:epVid, 
                    episode_video_location =:epVidLoc, episode_video_thumbnail_name=:epVidThumbnail, episode_video_thumbnail_location =:epVidThumbnailLoc, season_id=:ssnNewId
                    WHERE episode_id=:epId";
                    $stmt=$conn->prepare($sql2);
                    $stmt->execute([
                      // ':epNum'=>$_POST['upd_episode_number'],
                                    ':epTitle'=>str_replace('\'','’',$_POST['upd_episode_title']),
                                    ':epTitleJap'=>str_replace('\'','’',$_POST['upd_episode_title_jap']),
                                    ':epVid'=>$ep_vid,
                                    ':epVidLoc'=>$ep_vid_loc,
                                    ':epVidThumbnail'=>$ep_vid_thumbnail,
                                    ':epVidThumbnailLoc'=>$ep_vid_thumbnail_loc,
                                    ':epId'=>$_POST['upd_episode_id'],
                                    ':ssnNewId'=>$selectedEpisodeSeasonId
                                  ]);
                    echo "<script> window.location.href = 'p4-episodes.php'; alert('Episode Updated Successfully.'); </script>";
                    
                  }
                  else{
                    echo "<script> window.location.href = 'p4-episodes.php'; alert('".$removeMsg."'); </script>";
                  }
                } 
                else {
                  echo "<script> window.location.href = 'p4-episodes.php'; alert('Editing Episode Failed! There was an error in uploading the files.'); </script>";
                }  
              }
              else{
                echo "<script> window.location.href = 'p4-episodes.php'; alert('".$duplicateMsg."'); </script>";
              }
              
            }
            elseif ($uploadVideoExist == 0 && $uploadThumbnailExist == 1){

              $duplicateMsg="Adding Episode Failed! ";
              $dupEpCheck = 1;

              $dupEpIdCheck = $_POST['upd_episode_id'];
              // $dupEpNumCheck = $_POST['upd_episode_number'];
              $dupEpNumCheck = $_POST['inp_episode_title'];
              $dupEpTitleCheck = str_replace('\'','’',$_POST['upd_episode_title']);
              $dupEpTitleJapCheck = str_replace('\'','’',$_POST['upd_episode_title_jap']);
              $dupEpVideoCheck = $ep_vid_loc;
              $dupEpThumbnailCheck = $ep_vid_thumbnail_loc;

              $sql="SELECT * FROM episodes WHERE episode_id = '{$dupEpIdCheck}'";
              $resultEpisode=$conn->query($sql);
              $rowEpisode = $resultEpisode->fetch(PDO::FETCH_ASSOC);
              $epPrevNum = $rowEpisode['episode_number'];
              $epPrevTitle = $rowEpisode['episode_title'];
              $epPrevTitleJap = $rowEpisode['episode_title_jap'];

              $sql="SELECT * FROM episodes WHERE episode_number = '{$dupEpNumCheck}' AND season_id = '{$selectedEpisodeSeasonId}'";
              $resultEpisode=$conn->query($sql);
              $resultEpisode->fetch(PDO::FETCH_ASSOC);
              if(($epPrevNum != $dupEpNumCheck) && $resultEpisode->rowCount()>0){
                $dupEpCheck = 0;
                $duplicateMsg.="Episode Number Already Exists! ";
              }

              $sql="SELECT * FROM episodes WHERE season_id = '{$selectedEpisodeSeasonId}'";
              $resultEpisode=$conn->query($sql);
              while($rowEpisode = $resultEpisode->fetch(PDO::FETCH_ASSOC)){
                if ($rowEpisode['episode_id'] != $_POST['upd_episode_id'] && preg_replace('/\s+/', '', strtolower($rowEpisode['episode_title'])) == preg_replace('/\s+/', '', strtolower($dupEpTitleCheck))){
                  $dupEpCheck = 0;
                  $duplicateMsg.="Episode Title Already Exists! ";
                  break;
                }
              }

              $sql="SELECT * FROM episodes WHERE season_id = '{$selectedEpisodeSeasonId}'";
              $resultEpisode=$conn->query($sql);
              while($rowEpisode = $resultEpisode->fetch(PDO::FETCH_ASSOC)){
                if ($rowEpisode['episode_id'] != $_POST['upd_episode_id'] && preg_replace('/\s+/', '', strtolower($rowEpisode['episode_title_jap'])) == preg_replace('/\s+/', '', strtolower($dupEpTitleJapCheck))){
                  $dupEpCheck = 0;
                  $duplicateMsg.="Episode Title - Japanese Already Exists! ";
                  break;
                }
              }

              // $sql="SELECT * FROM episodes WHERE episode_title = '{$dupEpTitleCheck}' AND season_id = '{$selectedEpisodeSeasonId}'";
              // $resultEpisode=$conn->query($sql);
              // $resultEpisode->fetch(PDO::FETCH_ASSOC);
              // if(($epPrevTitle != $dupEpTitleCheck) && $resultEpisode->rowCount()>0){
              //   $dupEpCheck = 0;
              //   $duplicateMsg.="Episode Title Already Exists! ";
              // }

              // $sql="SELECT * FROM episodes WHERE episode_title_jap = '{$dupEpTitleJapCheck}' AND season_id = '{$selectedEpisodeSeasonId}'";
              // $resultEpisode=$conn->query($sql);
              // $resultEpisode->fetch(PDO::FETCH_ASSOC);
              // if(($epPrevTitleJap != $dupEpTitleJapCheck) && $resultEpisode->rowCount()>0){
              //   $dupEpCheck = 0;
              //   $duplicateMsg.="Episode Title - Japanese Already Exists! ";
              // }

              if(file_exists($dupEpThumbnailCheck)){
                $dupEpCheck = 0;
                $duplicateMsg.="Episode Thumbnail Already Exists! ";
              }

              if($dupEpCheck == 1){
                if (move_uploaded_file($_FILES["upd_episode_thumbnail"]["tmp_name"], $ep_vid_thumbnail_loc)) {
                
                  //Move existing file
                  $removeMsg="Episode edit failed. ";
                  $removeCheck = 1;
                  if(!rename($originalEpisodeVideoLoc, $ep_vid_loc)){
                    $removeCheck =0;
                    $removeMsg.="Failed to move existing episode Video file! ";
                  }
                  //Remove existing file
                  if(!unlink($originalEpisodeVideoThumbnailLoc)){
                    $removeCheck =0;
                    $removeMsg.="Failed to remove existing episode Image thumbnail file! ";
                  }
  
                  if($removeCheck == 1){
                    //Edit Episode
                    $sql2="UPDATE episodes SET episode_number =:epNum, episode_title =:epTitle, episode_title_jap =:epTitleJap, episode_video_name =:epVid, 
                    episode_video_location =:epVidLoc, episode_video_thumbnail_name=:epVidThumbnail, episode_video_thumbnail_location =:epVidThumbnailLoc, season_id=:ssnNewId
                    WHERE episode_id=:epId";
                    $stmt=$conn->prepare($sql2);
                    $stmt->execute([
                      // ':epNum'=>$_POST['upd_episode_number'],
                                    ':epTitle'=>str_replace('\'','’',$_POST['upd_episode_title']),
                                    ':epTitleJap'=>str_replace('\'','’',$_POST['upd_episode_title_jap']),
                                    ':epVid'=>$ep_vid,
                                    ':epVidLoc'=>$ep_vid_loc,
                                    ':epVidThumbnail'=>$ep_vid_thumbnail,
                                    ':epVidThumbnailLoc'=>$ep_vid_thumbnail_loc,
                                    ':epId'=>$_POST['upd_episode_id'],
                                    ':ssnNewId'=>$selectedEpisodeSeasonId
                                  ]);
                    echo "<script> window.location.href = 'p4-episodes.php'; alert('Episode Updated Successfully!'); </script>";
                  }
                  else{
                    echo "<script> window.location.href = 'p4-episodes.php'; alert('".$removeMsg."'); </script>";
                  }
                } 
                else {
                  echo "<script> window.location.href = 'p4-episodes.php'; alert('Editing Episode Failed! There was an error in uploading the files.'); </script>";
                }
              }
              else{
                echo "<script> window.location.href = 'p4-episodes.php'; alert('".$duplicateMsg."'); </script>";
              }

            }
            elseif ($uploadVideoExist == 1 && $uploadThumbnailExist == 0){

              $duplicateMsg="Adding Episode Failed! ";
              $dupEpCheck = 1;

              $dupEpIdCheck = $_POST['upd_episode_id'];
              // $dupEpNumCheck = $_POST['upd_episode_number'];
              $dupEpNumCheck = $_POST['inp_episode_title'];
              $dupEpTitleCheck = str_replace('\'','’',$_POST['upd_episode_title']);
              $dupEpTitleJapCheck = str_replace('\'','’',$_POST['upd_episode_title_jap']);
              $dupEpVideoCheck = $ep_vid_loc;
              $dupEpThumbnailCheck = $ep_vid_thumbnail_loc;

              $sql="SELECT * FROM episodes WHERE episode_id = '{$dupEpIdCheck}'";
              $resultEpisode=$conn->query($sql);
              $rowEpisode = $resultEpisode->fetch(PDO::FETCH_ASSOC);
              $epPrevNum = $rowEpisode['episode_number'];
              $epPrevTitle = $rowEpisode['episode_title'];
              $epPrevTitleJap = $rowEpisode['episode_title_jap'];

              $sql="SELECT * FROM episodes WHERE episode_number = '{$dupEpNumCheck}' AND season_id = '{$selectedEpisodeSeasonId}'";
              $resultEpisode=$conn->query($sql);
              $resultEpisode->fetch(PDO::FETCH_ASSOC);
              if(($epPrevNum != $dupEpNumCheck) && $resultEpisode->rowCount()>0){
                $dupEpCheck = 0;
                $duplicateMsg.="Episode Number Already Exists! ";
              }

              $sql="SELECT * FROM episodes WHERE season_id = '{$selectedEpisodeSeasonId}'";
              $resultEpisode=$conn->query($sql);
              while($rowEpisode = $resultEpisode->fetch(PDO::FETCH_ASSOC)){
                if ($rowEpisode['episode_id'] != $_POST['upd_episode_id'] && preg_replace('/\s+/', '', strtolower($rowEpisode['episode_title'])) == preg_replace('/\s+/', '', strtolower($dupEpTitleCheck))){
                  $dupEpCheck = 0;
                  $duplicateMsg.="Episode Title Already Exists! ";
                  break;
                }
              }

              $sql="SELECT * FROM episodes WHERE season_id = '{$selectedEpisodeSeasonId}'";
              $resultEpisode=$conn->query($sql);
              while($rowEpisode = $resultEpisode->fetch(PDO::FETCH_ASSOC)){
                if ($rowEpisode['episode_id'] != $_POST['upd_episode_id'] && preg_replace('/\s+/', '', strtolower($rowEpisode['episode_title_jap'])) == preg_replace('/\s+/', '', strtolower($dupEpTitleJapCheck))){
                  $dupEpCheck = 0;
                  $duplicateMsg.="Episode Title - Japanese Already Exists! ";
                  break;
                }
              }

              // $sql="SELECT * FROM episodes WHERE episode_title = '{$dupEpTitleCheck}' AND season_id = '{$selectedEpisodeSeasonId}'";
              // $resultEpisode=$conn->query($sql);
              // $resultEpisode->fetch(PDO::FETCH_ASSOC);
              // if(($epPrevTitle != $dupEpTitleCheck) && $resultEpisode->rowCount()>0){
              //   $dupEpCheck = 0;
              //   $duplicateMsg.="Episode Title Already Exists! ";
              // }

              // $sql="SELECT * FROM episodes WHERE episode_title_jap = '{$dupEpTitleJapCheck}' AND season_id = '{$selectedEpisodeSeasonId}'";
              // $resultEpisode=$conn->query($sql);
              // $resultEpisode->fetch(PDO::FETCH_ASSOC);
              // if(($epPrevTitleJap != $dupEpTitleJapCheck) && $resultEpisode->rowCount()>0){
              //   $dupEpCheck = 0;
              //   $duplicateMsg.="Episode Title - Japanese Already Exists! ";
              // }

              if(file_exists($dupEpVideoCheck)){
                $dupEpCheck = 0;
                $duplicateMsg.="Episode Video Already Exists! ";
              }

              if($dupEpCheck == 1){
                if (move_uploaded_file($_FILES["upd_episode_video"]["tmp_name"], $ep_vid_loc)) {

                  //Remove existing file
                  $removeMsg="Episode edit failed. ";
                  $removeCheck = 1;
                  if(!unlink($originalEpisodeVideoLoc)){
                    $removeCheck = 0;
                    $removeMsg.="Failed to remove existing episode Video file! ";
                  }
                  //Move existing file
                  if(!rename($originalEpisodeVideoThumbnailLoc, $ep_vid_thumbnail_loc)){
                    $removeCheck = 0;
                    $removeMsg.="Failed to move existing episode Image thumbnail file! ";
                  }
                
                  if($removeCheck == 1){
                    //Edit Episode
                    $sql2="UPDATE episodes SET episode_number =:epNum, episode_title =:epTitle, episode_title_jap =:epTitleJap, episode_video_name =:epVid, 
                    episode_video_location =:epVidLoc, episode_video_thumbnail_name=:epVidThumbnail, episode_video_thumbnail_location =:epVidThumbnailLoc, season_id=:ssnNewId
                    WHERE episode_id=:epId";
                    $stmt=$conn->prepare($sql2);
                    $stmt->execute([
                      // ':epNum'=>$_POST['upd_episode_number'],
                                    ':epTitle'=>str_replace('\'','’',$_POST['upd_episode_title']),
                                    ':epTitleJap'=>str_replace('\'','’',$_POST['upd_episode_title_jap']),
                                    ':epVid'=>$ep_vid,
                                    ':epVidLoc'=>$ep_vid_loc,
                                    ':epVidThumbnail'=>$ep_vid_thumbnail,
                                    ':epVidThumbnailLoc'=>$ep_vid_thumbnail_loc,
                                    ':epId'=>$_POST['upd_episode_id'],
                                    ':ssnNewId'=>$selectedEpisodeSeasonId
                                  ]);
                    echo "<script> window.location.href = 'p4-episodes.php'; alert('Episode Updated Successfully!'); </script>";
                  }
                  else{
                    echo "<script> window.location.href = 'p4-episodes.php'; alert('".$removeMsg."'); </script>";
                  }
                  
                } 
                else {
                  echo "<script> window.location.href = 'p4-episodes.php'; alert('Editing Episode Failed! There was an error in uploading the files.'); </script>";
                }
              }
              else{
                echo "<script> window.location.href = 'p4-episodes.php'; alert('".$duplicateMsg."'); </script>";
              }
              
            }
            elseif ($uploadVideoExist == 0 && $uploadThumbnailExist == 0){


              $duplicateMsg="Adding Episode Failed! ";
              $dupEpCheck = 1;

              $dupEpIdCheck = $_POST['upd_episode_id'];
              // $dupEpNumCheck = $_POST['upd_episode_number'];
              $dupEpNumCheck = $_POST['inp_episode_title'];
              $dupEpTitleCheck = str_replace('\'','’',$_POST['upd_episode_title']);
              $dupEpTitleJapCheck = str_replace('\'','’',$_POST['upd_episode_title_jap']);
              $dupEpVideoCheck = $ep_vid_loc;
              $dupEpThumbnailCheck = $ep_vid_thumbnail_loc;

              $sql="SELECT * FROM episodes WHERE episode_id = '{$dupEpIdCheck}'";
              $resultEpisode=$conn->query($sql);
              $rowEpisode = $resultEpisode->fetch(PDO::FETCH_ASSOC);
              $epPrevNum = $rowEpisode['episode_number'];
              $epPrevTitle = $rowEpisode['episode_title'];
              $epPrevTitleJap = $rowEpisode['episode_title_jap'];

              $sql="SELECT * FROM episodes WHERE episode_number = '{$dupEpNumCheck}' AND season_id = '{$selectedEpisodeSeasonId}'";
              $resultEpisode=$conn->query($sql);
              $resultEpisode->fetch(PDO::FETCH_ASSOC);
              if(($epPrevNum != $dupEpNumCheck) && $resultEpisode->rowCount()>0){
                $dupEpCheck = 0;
                $duplicateMsg.="Episode Number Already Exists! ";
              }

              $sql="SELECT * FROM episodes WHERE season_id = '{$selectedEpisodeSeasonId}'";
              $resultEpisode=$conn->query($sql);
              while($rowEpisode = $resultEpisode->fetch(PDO::FETCH_ASSOC)){
                if ($rowEpisode['episode_id'] != $_POST['upd_episode_id'] && preg_replace('/\s+/', '', strtolower($rowEpisode['episode_title'])) == preg_replace('/\s+/', '', strtolower($dupEpTitleCheck))){
                  $dupEpCheck = 0;
                  $duplicateMsg.="Episode Title Already Exists! ";
                  break;
                }
              }

              $sql="SELECT * FROM episodes WHERE season_id = '{$selectedEpisodeSeasonId}'";
              $resultEpisode=$conn->query($sql);
              while($rowEpisode = $resultEpisode->fetch(PDO::FETCH_ASSOC)){
                if ($rowEpisode['episode_id'] != $_POST['upd_episode_id'] && preg_replace('/\s+/', '', strtolower($rowEpisode['episode_title_jap'])) == preg_replace('/\s+/', '', strtolower($dupEpTitleJapCheck))){
                  $dupEpCheck = 0;
                  $duplicateMsg.="Episode Title - Japanese Already Exists! ";
                  break;
                }
              }

              // $sql="SELECT * FROM episodes WHERE episode_title = '{$dupEpTitleCheck}' AND season_id = '{$selectedEpisodeSeasonId}'";
              // $resultEpisode=$conn->query($sql);
              // $resultEpisode->fetch(PDO::FETCH_ASSOC);
              // if(($epPrevTitle != $dupEpTitleCheck) && $resultEpisode->rowCount()>0){
              //   $dupEpCheck = 0;
              //   $duplicateMsg.="Episode Title Already Exists! ";
              // }

              // $sql="SELECT * FROM episodes WHERE episode_title_jap = '{$dupEpTitleJapCheck}' AND season_id = '{$selectedEpisodeSeasonId}'";
              // $resultEpisode=$conn->query($sql);
              // $resultEpisode->fetch(PDO::FETCH_ASSOC);
              // if(($epPrevTitleJap != $dupEpTitleJapCheck) && $resultEpisode->rowCount()>0){
              //   $dupEpCheck = 0;
              //   $duplicateMsg.="Episode Title - Japanese Already Exists! ";
              // }

              if($dupEpCheck == 1){
                //Move existing files
                $removeMsg="Episode edit failed. ";
                $removeCheck = 1;
                if(!rename($originalEpisodeVideoLoc, $ep_vid_loc)){
                  $removeCheck = 0;
                  $removeMsg.="Failed to move existing episode Video file! ";
                }
                if(!rename($originalEpisodeVideoThumbnailLoc, $ep_vid_thumbnail_loc)){
                  $removeCheck = 0;
                  $removeMsg.="Failed to move existing episode Image thumbnail file! ";
                }

                if($removeCheck == 1){
                  //Edit Episode
                  $sql2="UPDATE episodes SET episode_number =:epNum, episode_title =:epTitle, episode_title_jap =:epTitleJap, episode_video_name =:epVid, 
                  episode_video_location =:epVidLoc, episode_video_thumbnail_name=:epVidThumbnail, episode_video_thumbnail_location =:epVidThumbnailLoc, season_id=:ssnNewId
                  WHERE episode_id=:epId";
                  $stmt=$conn->prepare($sql2);
                  $stmt->execute([
                    // ':epNum'=>$_POST['upd_episode_number'],
                                  ':epTitle'=>str_replace('\'','’',$_POST['upd_episode_title']),
                                  ':epTitleJap'=>str_replace('\'','’',$_POST['upd_episode_title_jap']),
                                  ':epVid'=>$ep_vid,
                                  ':epVidLoc'=>$ep_vid_loc,
                                  ':epVidThumbnail'=>$ep_vid_thumbnail,
                                  ':epVidThumbnailLoc'=>$ep_vid_thumbnail_loc,
                                  ':epId'=>$_POST['upd_episode_id'],
                                  ':ssnNewId'=>$selectedEpisodeSeasonId
                                ]);
                  echo "<script> window.location.href = 'p4-episodes.php'; alert('Episode Updated Successfully!'); </script>";
                }
                else{
                  echo "<script> window.location.href = 'p4-episodes.php'; alert('".$removeMsg."'); </script>";
                }
              }
              else{
                echo "<script> window.location.href = 'p4-episodes.php'; alert('".$duplicateMsg."'); </script>";
              }
              
            }
            else{
              echo "<script> window.location.href = 'p4-episodes.php'; alert('Editing Episode Failed! There was an error in file update.'); </script>";
            }
          }
        }
        else{
          echo "<script> window.location.href = 'p4-episodes.php'; alert('".$origfileMsg."'); </script>";
        }

      }
      else{
        echo "<script> window.location.href = 'p4-episodes.php'; alert('Editing Episode Failed! Season ".$ep_season." Directory/Folder CANNOT Be Found!'); </script>";
      }
    }
    else{
      echo "<script> window.location.href = 'p4-episodes.php'; alert('".$episodeseasonCheckMessage."'); </script>";
    }

  }

  //Delete Episode
  if(isset($_POST['submit_delete_episode'])){

    $episodeId = $_POST['inp_delete_episode'];
    
    $sql="SELECT * FROM episodes WHERE episode_id = '{$episodeId}'";
    $resultEpisode=$conn->query($sql);
    $resultEpisode->fetch(PDO::FETCH_ASSOC);
    //Check if episode exists
    if($resultEpisode->rowCount()>0){
      $epFileMsg ="";
      $epFileCheck = 1;

      $sql="SELECT * FROM episodes WHERE episode_id = '{$episodeId}'";
      $resultEpisode=$conn->query($sql);
      $rowEpisode =$resultEpisode->fetch(PDO::FETCH_ASSOC);
      $episodeVideo = $rowEpisode['episode_video_location'];
      $episodeVideoThumbnail = $rowEpisode['episode_video_thumbnail_location'];

      //Check if file to be deleted exists
      if(!file_exists($episodeVideo)){
        $epFileCheck = 0;
        $epFileMsg .= "Episode Video file CANNOT Be Found. ";
      }

      if(!file_exists($episodeVideoThumbnail)){
        $epFileCheck = 0;
        $epFileMsg .= "Episode thumbnail Image file CANNOT Be Found. ";
      }

      if($epFileCheck == 1){

        //Delete Episode Video and Thumbnail
        $deleteFailedMessage = "Episode deletion failed. ";
        $deleteOk=1;
        if(!unlink($episodeVideo)){
          $deleteOk=0;
          $deleteFailedMessage .="There was an error in deleting episode Video file. ";
        }
        if(!unlink($episodeVideoThumbnail)){
          $deleteOk=0;
          $deleteFailedMessage .="There was an error in deleting episode Image Thumbnail file. ";
        }
        if($deleteOk == 1){
  
          //Delete Episode Record
          $sql="DELETE FROM episodes WHERE episode_id=:epId;";
          $stmt=$conn->prepare($sql);
          $stmt->execute([':epId'=>$episodeId]);
          echo "<script> window.location.href = 'p4-episodes.php'; alert('Episode Deleted Successfully.'); </script>";
        }
        else{
          echo "<script> window.location.href = 'p4-episodes.php'; alert('".$deleteFailedMessage."'); </script>";
        }
        echo "<script> window.location.href = 'p4-episodes.php'; alert('Episode Deleted Successfully.'); </script>";
      }
      else{
        echo "<script> window.location.href = 'p4-episodes.php'; alert('".$epFileMsg."'); </script>";
      }

    }
    else {
      echo "<script> window.location.href = 'p4-episodes.php'; alert('Deleting Episode Failed! Episode does NOT Exist in episodes table.'); </script>";
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
    <script src="bootstrap-5.1.3-dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="page-stylesheet.css" />
    <link rel="icon" type="image/x-icon" href="assets/kny-icon-logo.ico" />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Lato"
    />
  </head>
  <body >
  <!-- onbeforeunload='reset_options()' -->
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
              width="35px"
              alt="wampis logo"
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
            ><img src="assets/user-icon-light.png" width="35px" class="user-icon" alt="user icon"/><div class="login-icon-usertext"><i>Hi, <?php echo $login_user; ?></i></div>
            <img src="assets/drop-down-button-light.png" width="20px" class="user-dropdown-icon" alt="dropdown icon">
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
        <a class="dropdown-menu-button" href="p4-episodes.php">Highlights</a>
      </div>
    </div>

    <!-- Navigation Bar END -->

    <!-- content -->
    <div class="overflw">
      <div class="kny-vid-background">
        <div class="kny-vid-display-hide" id="kny-vid-display-id">
          <p class="kny-vid-headtext" id="kny-vid-jump-id-p" >Strawhat</p>
          <img src="assets/x-icon-antiquewhite.png" class="x-icon-position" id="id-x-icon-position" onclick="episodeDisplayHide()" alt="x icon" width="35px">
          <!-- <i  style="margin-bottom: 24px; visibility: hidden">-</i> -->
          <div id="id-season-vid-container">
            <video
              src="<?php echo $seasonEpvidloc; ?>"
              poster="<?php echo $seasonEpthumbloc; ?>"
              class="kny-play-vid"
              id="kny-vid-jump-id"
              controls
            ></video>
            <div class="episode-text-container">
              <i class="episode-text-ep">Episode </i>
              <i class="episode-text-epNumber"><?php echo $seasonEpnum; ?></i>
              <i class="episode-text-epHyphen"> - </i>
              <i class="episode-text-epTitle"><?php echo $seasonEptitle; ?></i><br />
              <i class="episode-text-epTitletext"><?php echo $seasonEptitlejap; ?></i>
            </div>
          </div>
          
          <div class="episode-text-line"></div>
          <div class="ep-button-container" id="id-ep-button-container">
            <?php
              $sql="SELECT * FROM episodes WHERE season_id = '{$seasonid}' ORDER BY episode_number";
              $resultEpisode=$conn->query($sql);
              while($rowEp=$resultEpisode->fetch(PDO::FETCH_ASSOC)){
            ?>
              <div class="ep-button">
                <i class="ep-button-text"><?php echo $rowEp['episode_number']; ?></i>
                <p><?php echo $rowEp['episode_title']; ?></p>
                <i><?php echo $rowEp['episode_title_jap']; ?></i>
                <video
                  src="<?php echo $rowEp['episode_video_location']; ?>"
                  poster="<?php echo $rowEp['episode_video_thumbnail_location']; ?>"
                  class="ep-button-link"
                ></video>
              </div>
            <?php } ?>
          </div>
        </div>

        <!-- Episode Table -->
        <div class="table-background-position">
          <!-- <img
            src="assets/img/kny-wallpaper01.png"
            alt="wallpaper01"
            class="backgroundpic"
          /> -->
          <div class="tablebox">
            <table class="table-episode">
              <tr>
                <th colspan="3">
                <select id="id-get-season" class="btn-series dropdown-ssn" onchange="selectSeason()">
                  <?php
                    $sql="SELECT * FROM season ORDER BY season_name";
                    $resultSeason=$conn->query($sql);
                    while($rowSsn=$resultSeason->fetch(PDO::FETCH_ASSOC)){
                    ?>
                    <option class="dropdown-ssn-item" value="<?php echo $rowSsn['season_id']; ?>" data-select-season="<?php echo $rowSsn['season_name']; ?>"><?php echo $rowSsn['season_name']; ?></option>
                    <?php } ?>
                </select> 
                <?php if($login_access=="Admin") {?>
                  <button type="button" class="btn btn-success add-ssn-btn" data-bs-toggle="modal" data-bs-target="#add_season_modal">Add</button>
                  <?php
                    $sqlseason = "SELECT * FROM season ORDER BY season_name LIMIT 1";
                    $stmtseason = $conn->query($sqlseason);
                    $rowseason = $stmtseason->fetch(PDO::FETCH_ASSOC);
                  
                    //check if empty
                    if(!empty($rowseason['season_id'])){
                  ?>
                  <button type="button" class="btn btn-primary edit-ssn-btn" data-bs-toggle="modal" data-bs-target="#edit_season_modal" id="id_edit_season_btn">Edit</button>
                  <button type="button" class="btn btn-danger delete-ssn-btn" data-bs-toggle="modal" data-bs-target="#delete_season_modal" id="id_delete_season_btn">Delete</button>
                  <button type="button" class="btn btn-success add-ep-btn" data-bs-toggle="modal" data-bs-target="#add_episode_modal" id="id_add_episode_btn">Add Highlights</button>
                <?php }}?>
                </th>
              </tr>
              <tr>
                <!-- <th class="eptable-epnum-col">EP#</th> -->
                <th id="id-table-ep-title" class="eptable-eptitle-col">Highlights Info</th>
                <?php if($login_access=="Admin") {?>
                <th class="eptable-epaction-col">Action</th>
                <?php }?>
              </tr>
              <!-- Display Episode List -->
            <tbody id="getSeasonEps">
              
            </tbody>
            <!-- End Episode -->
            </table>
          </div>
        </div>
      </div>

    <!--------------------------- add Season Modal ------------------------>
    <div class="modal fade" id="add_season_modal" tabindex="-1" role="dialog" aria-labelledby="modal_container" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modal_container">Add Character Highlights</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            </button>
          </div>
          <div class="modal-body">
            <form action="" method="POST">
              <!-- create season folder -->
              <div class="mb-3 mt-3">
                <input type="text" class="form-control" id="id_inp_season_name"  pattern='[^.\/:*?"<>|]+' title="Season Name can't contain any of these characters: . \ / : * ? &quot < > |" placeholder="Enter Character Highlights Name" name="inp_season_name" required/>
                <input type="hidden" id="id_inp_season_name_old" name="inp_season_name_old"/>
              </div>
          </div>
          <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="submit_add_season">Save</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </form>
          </div>
          </div>
      </div>
    </div>

    <!--------------------------- edit Season Modal ------------------------>
    <div class="modal fade" id="edit_season_modal" tabindex="-1" role="dialog" aria-labelledby="modal_container" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modal_container">Edit Character Highlights</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            </button>
          </div>
          <div class="modal-body">
            <form action="" method="POST">
              <!-- edit season folder -->
              <div class="mb-3 mt-3">
                <input type="hidden" name="upd_season_name_old" id="id_upd_season_name_old"/>
                <input type="hidden" name="upd_season_name_ID" id="id_upd_season_name_ID"/>
                <input type="text" class="form-control" id="id_upd_season_name" pattern='[^.\/:*?"<>|]+' title="Season Name can't contain any of these characters: . \ / : * ? &quot < > |" placeholder="Enter Character Highlights Name" name="upd_season_name" required/>
              </div>
          </div>
          <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="submit_edit_season">Save Changes</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!--------------------------- delete Season Modal ------------------------>
    <div class="modal fade" id="delete_season_modal" tabindex="-1" role="dialog" aria-labelledby="modal_container" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modal_container">Delete Character Highlights</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            </button>
          </div>
          <div class="modal-body">
            <p>Are you sure you want to delete <span id="id-ssnNm-del"></span>?</p>
          </div>
          <div class="modal-footer">
            <form action="" method="POST">
              <!-- delete season folder -->
              <input type="hidden" name="inp_delete_season" id="id_inp_delete_season"/>
              <button type="submit" class="btn btn-primary" name="submit_delete_season">Yes</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    
    <!--------------------------- add Episode Modal ------------------------>
    <div class="modal fade" id="add_episode_modal" tabindex="-1" role="dialog" aria-labelledby="modal_container" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modal_container">Add Highlights</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="" method="POST" enctype='multipart/form-data'>
              <div class="mb-3">
                <label class="form-label">Season Category</label>
                <select class="form-select" id="id_inp_episode_season"  name="inp_episode_season_id" required>
                  <option value="" readonly>----- Select -----</option>
                  <?php
                    $sql="SELECT * FROM season ORDER BY season_name";
                    $resultSeason=$conn->query($sql);
                    while($rowSsn=$resultSeason->fetch(PDO::FETCH_ASSOC)){
                  ?>
                  <option value="<?php echo $rowSsn['season_id']; ?>"><?php echo $rowSsn['season_name']; ?></option>
                  <?php } ?>
                </select>
              </div>
              <!-- <div class="mb-3 mt-3">
                <input type="number" min="0" max="9000" class="form-control" id="id_inp_episode_number" placeholder="Enter Episode Number" name="inp_episode_number" required/>
              </div> -->
              <div class="mb-3 mt-3">
                <input type="text" class="form-control" id="id_inp_episode_title" placeholder="Enter Highlights Info" name="inp_episode_title" required/>
              </div>
              <div class="mb-3 mt-3">
                <input type="text" class="form-control" id="id_inp_episode_title_jap" placeholder="Enter Background Song Title" name="inp_episode_title_jap" required/>
              </div>
              <!-- Upload Episode -->
              <div class="mb-3">
                <label class="form-label">Select Video</label>
                <input type="file" class="upd-txt-width" id="id_inp_episode_video" name="inp_episode_video" required/>
              </div>
              <div class="mb-3">
                <label class="form-label">Select Image - Thumbnail</label>
                <input type="file" class="upd-txt-width" id="id_inp_episode_thumbnail" name="inp_episode_thumbnail" required/>
              </div>
          </div>
          <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="submit_add_episode">Save</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!--------------------------- edit Episode Modal ------------------------>
    <div class="modal fade" id="edit_episode_modal" tabindex="-1" role="dialog" aria-labelledby="modal_container" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modal_container">Edit Highlights</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            </button>
          </div>
          <div class="modal-body">
            <form action="" method="POST" enctype='multipart/form-data'>
            <div class="mb-3">
                <label class="form-label">Season Category</label>
                <select class="form-select" id="id_upd_episode_season"  name="upd_episode_season" required>
                  <option value="" readonly>----- Select -----</option>
                  <?php
                    $sql="SELECT * FROM season ORDER BY season_name";
                    $resultSeason=$conn->query($sql);
                    while($rowSsn=$resultSeason->fetch(PDO::FETCH_ASSOC)){
                  ?>
                  <option value="<?php echo $rowSsn['season_id']; ?>"><?php echo $rowSsn['season_name']; ?></option>
                  <?php } ?>
                </select>
              </div>
              <!-- <div class="mb-3 mt-3">
                  <input type="number" min="0" max="9000" class="form-control" id="id_upd_episode_number" placeholder="Enter Episode Number" name="upd_episode_number" required/>
              </div> -->
              <div class="mb-3 mt-3">
                  <input type="text" class="form-control" id="id_upd_episode_title" placeholder="Enter Highlights Info" name="upd_episode_title" required/>
              </div>
              <div class="mb-3 mt-3">
                  <input type="text" class="form-control" id="id_upd_episode_title_jap" placeholder="Enter Background Song Title" name="upd_episode_title_jap" required/>
              </div>
              <!-- Upload Episode (Optional)-->
              <div class="mb-3">
              <label class="form-label" id="id_cur_episode_video"></label>
              <label class="form-label">Select Video (Optional - Choosing will overwrite existing episode)</label>
                <input type="file" class="upd-txt-width" id="id_upd_episode_video" name="upd_episode_video"/>
              </div>
              <div class="mb-3">
              <label class="form-label" id="id_cur_episode_thumbnail"></label>
              <label class="form-label">Select Image - Thumbnail (Optional - Choosing will overwrite existing episode thumbnail)</label>
                <input type="file" class="upd-txt-width" id="id_upd_episode_thumbnail" name="upd_episode_thumbnail"/>
              </div>
          </div>
          <div class="modal-footer">
              <input type="hidden" name="upd_episode_video_name" id="id_upd_episode_video_name"/>
              <input type="hidden" name="upd_episode_video_loc" id="id_upd_episode_video_loc"/>
              <input type="hidden" name="upd_episode_thumbnail_name" id="id_upd_episode_thumbnail_name"/>
              <input type="hidden" name="upd_episode_thumbnail_loc" id="id_upd_episode_thumbnail_loc"/>
              <input type="hidden" name="upd_episode_id" id="id_upd_episode_id"/>
              <input type="hidden" name="upd_episode_season_id" id="id_upd_episode_season_id"/>
              <button type="submit" class="btn btn-primary" name="submit_edit_episode">Save Changes</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!--------------------------- delete Episode Modal ------------------------>
    <div class="modal fade" id="delete_episode_modal" tabindex="-1" role="dialog" aria-labelledby="modal_container" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modal_container">Delete Highlights</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            </button>
          </div>
          <div class="modal-body">
            <p>Are you sure you want to delete <span id="id-epNm-del"></span>?</p>
          </div>
          <div class="modal-footer">
            <form action="" method="POST">
              <!-- delete episode -->
              <input type="hidden" name="inp_delete_episode" id="id_inp_delete_episode"/>
              <button type="submit" class="btn btn-primary" name="submit_delete_episode">Yes</button>
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

  // function reset_options(){
  //   document.getElementById("id-get-season").selectedIndex = 0;
  //   selectSeason();
  // }

  $(document).ready(function(){
    selectSeason();
  });

  //Get Selected Season and display table row
  function selectSeason(){
    var ssn_id = document.getElementById("id-get-season").value;

    //table row
    $.ajax({
      // url: file.php,
      method: "POST",
      data:{
        season_table_id : ssn_id
      },
      success:function(data){
        $("#getSeasonEps").html(data);
      }
    })

    //episode video
    $.ajax({
      method: "POST",
      data:{
        season_video_id : ssn_id
      },
      success:function(data){
        $("#id-season-vid-container").html(data);
      }
    })

    //episode button
    $.ajax({
      method: "POST",
      data:{
        season_button_id : ssn_id
      },
      success:function(data){
        $("#id-ep-button-container").html(data);
      }
    })
    
  }

  //Display Episode Video
  // tr td:nth-child(n):nth-child(-n + 2)
  $("#getSeasonEps").on('click','.ep-jmp',function() {
    episodeDisplayShow();
    jumpVid();
    var currentRow  = $(this).closest("tr");
    
    var epnumber = currentRow.find("td").html();
    var eptitle = currentRow.find("p").html();
    var eptitletext = currentRow.find("i").html();
    var eplink = currentRow.find("video").attr('src');
    var epthumbnail = currentRow.find("video").attr('poster');

    // document.getElementsByClassName("sampsamptbl table-ep-title-clas")[0].innerHTML = eptitle;
    // document.querySelector(".episode-text-container .episode-text-epTitle").innerHTML = eptitle;
    document.getElementsByClassName("episode-text-epNumber")[0].innerHTML = epnumber;
    document.getElementsByClassName("episode-text-epTitle")[0].innerHTML = eptitle;
    document.getElementsByClassName("episode-text-epTitletext")[0].innerHTML = eptitletext;
    document.getElementsByClassName("kny-play-vid")[0].src = eplink;
    document.getElementsByClassName("kny-play-vid")[0].poster = epthumbnail;
    document.getElementsByClassName("kny-play-vid")[0].load(); //.load() .play()
  });

  // $(document).on('click','#id-x-icon-position', function(){
  //   // document.getElementById("kny-vid-jump-id").load();
  //   document.getElementsByClassName("kny-play-vid")[0].load();
  // });

  //Display Episode Video Button
  $("#id-ep-button-container").on('click','.ep-button',function() { 
    jumpVid();
    var currentRow  = $(this);
    
    var epnumber = currentRow.find("i").html();
    var eptitle = currentRow.find("p").html();
    var eptitletext = currentRow.find("i:nth-of-type(2)").html();
    var eplink = currentRow.find("video").attr('src');
    var epthumbnail = currentRow.find("video").attr('poster');

    document.getElementsByClassName("episode-text-epNumber")[0].innerHTML = epnumber;
    document.getElementsByClassName("episode-text-epTitle")[0].innerHTML = eptitle;
    document.getElementsByClassName("episode-text-epTitletext")[0].innerHTML = eptitletext;
    document.getElementsByClassName("kny-play-vid")[0].src = eplink;
    document.getElementsByClassName("kny-play-vid")[0].poster = epthumbnail;
    document.getElementsByClassName("kny-play-vid")[0].load(); //.load() .play()

  });

  //Delete Season
  $(document).on('click','#id_delete_season_btn', function (){
    var selectedSeasonName = $('#id-get-season option:selected').attr('data-select-season');
    document.getElementById('id-ssnNm-del').innerHTML = selectedSeasonName;

    var selectedSeasonId = $('#id-get-season').val();
    document.getElementById('id_inp_delete_season').value = selectedSeasonId;

    //Stop Video
    document.querySelector(".kny-vid-background .kny-play-vid").load();
  });

  //Edit Season
  $(document).on('click','#id_edit_season_btn', function (){
    var selectedSeasonName = $('#id-get-season option:selected').attr('data-select-season');
    document.getElementById('id_upd_season_name_old').value = selectedSeasonName;
    document.getElementById('id_upd_season_name').value = selectedSeasonName;

    var selectedSeasonId = $('#id-get-season').val();
    document.getElementById('id_upd_season_name_ID').value = selectedSeasonId;
    
    //Stop Video
    document.querySelector(".kny-vid-background .kny-play-vid").load();
  });

  //Add Episode
  $(document).on('click','#id_add_episode_btn', function(){
    var selectedSeasonId = $('#id-get-season').val();
    document.getElementById('id_inp_episode_season').value = selectedSeasonId;
  });

  //Edit Episode - send value to edit modal
  function episodeEdit($epNum, $epTitle, $epTitleJap, $epVid, $epVidLoc, $epThumb, $epThumbLoc, $epId, $epSeasonId){
    // document.getElementById('id_upd_episode_number').value = $epNum;
    //replace changed value
    document.getElementById('id_upd_episode_title').value = $epTitle.replace(/39DoU3b9Le3Q9t/g,"\"").replace(/39Si3N9gLe3Q9t/g,"\'");
    document.getElementById('id_upd_episode_title_jap').value = $epTitleJap.replace(/39DoU3b9Le3Q9t/g,"\"").replace(/39Si3N9gLe3Q9t/g,"\'");
    document.getElementById('id_upd_episode_video_name').value = $epVid.replace(/39DoU3b9Le3Q9t/g,"\"").replace(/39Si3N9gLe3Q9t/g,"\'");
    document.getElementById('id_upd_episode_video_loc').value = $epVidLoc.replace(/39DoU3b9Le3Q9t/g,"\"").replace(/39Si3N9gLe3Q9t/g,"\'");
    document.getElementById('id_upd_episode_thumbnail_name').value = $epThumb.replace(/39DoU3b9Le3Q9t/g,"\"").replace(/39Si3N9gLe3Q9t/g,"\'");
    document.getElementById('id_upd_episode_thumbnail_loc').value = $epThumbLoc.replace(/39DoU3b9Le3Q9t/g,"\"").replace(/39Si3N9gLe3Q9t/g,"\'");
    document.getElementById('id_upd_episode_id').value = $epId;
    document.getElementById('id_upd_episode_season_id').value = $epSeasonId;
    document.getElementById('id_cur_episode_video').innerHTML = "Current ep video: "+$epVid.replace(/39DoU3b9Le3Q9t/g,"\"").replace(/39Si3N9gLe3Q9t/g,"\'");
    document.getElementById('id_cur_episode_thumbnail').innerHTML = "Current ep thumbnail: "+$epThumb.replace(/39DoU3b9Le3Q9t/g,"\"").replace(/39Si3N9gLe3Q9t/g,"\'");

    var selectedSeasonId = $('#id-get-season').val();
    document.getElementById('id_upd_episode_season').value = selectedSeasonId;

    //Stop Video
    document.querySelector(".kny-vid-background .kny-play-vid").load();
  }

  //Delete Episode - send value to delete modal
  function episodeDelete($epId, $epNum, $epTitle){
    //replace changed value
    $epname="Episode "+$epNum+" - "+$epTitle.replace(/39DoU3b9Le3Q9t/g,"\"").replace(/39Si3N9gLe3Q9t/g,"\'");
    document.getElementById('id_inp_delete_episode').value = $epId;
    document.getElementById('id-epNm-del').innerHTML = $epname;

    //Stop Video
    document.querySelector(".kny-vid-background .kny-play-vid").load();
  }

</script>
