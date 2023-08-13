<?php
  session_start();
  include("config.php");
  
  //redirect to login page if no session
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
  $trailerid = 2;
  
  //Edit Story
  if(isset($_POST['submit_edit_story'])){

    //Check if story exists in table
    $sql="SELECT * FROM story WHERE story_id = '{$storyid}'";
    $resultStory=$conn->query($sql);
    $resultStory->fetch(PDO::FETCH_ASSOC);

    if($resultStory->rowCount()>0){

      $sql="UPDATE story SET story_paragraph = :storyPg WHERE story_id = :storyId";
      $stmt=$conn->prepare($sql);
      $stmt->execute([':storyPg'=>str_replace('\'','’',$_POST['inp_story_paragraph']),
                    ':storyId'=>$storyid ]);

      echo "<script> window.location.href = 'index.php'; alert('Story Updated Successfully.'); </script>";
    }
    else {
      echo "<script> window.location.href = 'index.php'; alert('Editing story failed! Story (ID:2) does NOT Exist in story table.'); </script>";
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
        <a class="dropdown-menu-button" href="p4-episodes.php">Highlights</a>
      </div>
    </div>

    <!-- Navigation Bar END -->

    <!-- content -->
    <div class="overflw">
      <!-- Homepage Pic -->
      <div class="story-pic">
        <img src="assets/img/strawhat.jpg" alt="strawhat" class="image" />
      </div>

      <!-- Story -->
      <div class="darkblue">
        <div
          class="container content center padding-64"
          style="max-width: 800px"
          id="Story"
        >
          <?php 
            $sql="SELECT * FROM story WHERE story_id = '{$storyid}'";
            $resultStory=$conn->query($sql);
            $rowStory=$resultStory->fetch(PDO::FETCH_ASSOC);

            //replace special characters for onclick to work
            $storyPgChange = str_replace("'",'39Si3N9gLe3Q9t', str_replace('"','39DoU3b9Le3Q9t',$rowStory['story_paragraph']) );
            $storyPgChange = str_replace("\r",'36Re3T6u3R6n', str_replace("\n",'36Ne3W6Li3N6e',$storyPgChange) );

          ?>
          <h2 class="wide red title" id="id_story">Story<?php if($login_access=="Admin") {?><button type="button" class="btn btn-primary" id="id_edit_story_btn" data-bs-toggle="modal" data-bs-target="#edit_story_modal" onclick="storyEdit('<?php echo $storyPgChange; ?>')">Edit</button><?php }?></h2>
          <!-- Story Paragraph -->
          <p class="justify title-text" id="id_story_paragraph"><?php echo $rowStory['story_paragraph']; ?></p>
        </div>
      </div>

      <!-- Story Pic -->
      <div class="pic-container">
        <img src="assets/img/story.jpg" alt="story" class="image" />
      </div>
      <!-- End Pic -->
      <div class="pic-container">
          <img
            src="assets/img/wanpos.jpg"
            alt="wanpos"
            class="image"
          />
      </div>

    <!--------------------------- edit Story Modal ------------------------>
    <div class="modal fade" id="edit_story_modal" tabindex="-1" role="dialog" aria-labelledby="modal_container" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="modal_container">Edit Story</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
              </button>
          </div>
          <div class="modal-body">
            <form action="" method="POST">
              <div class="mb-3">
                <label class="form-label">Press Enter to add new line. Delete the '&lt;br/>&lt;br/>' to remove new line.</label>
                <textarea class="form-control" cols="1" rows="7" id="id_inp_story_paragraph" placeholder="Enter Paragraph here." name="inp_story_paragraph" required></textarea>
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
    <script src="page-script.js"></script>
  </body>
</html>
<script>

  //send value to edit modal
  function storyEdit($storyPrg){
    //replace changed value to original value
    document.getElementById('id_inp_story_paragraph').value = $storyPrg.replace(/39DoU3b9Le3Q9t/g,"\"").replace(/39Si3N9gLe3Q9t/g,"\'").replace(/36Re3T6u3R6n/g,"\r").replace(/36Ne3W6Li3N6e/g,"\n");
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

  //Prevent new line in story textarea when pressed Enter
  $('#id_inp_story_paragraph').keypress(
  function(event){
    if (event.which == '13') {
      event.preventDefault();
    }
  });

  // Input Break Line in story textarea when pressed Enter
  $(function (){
    $('#id_inp_story_paragraph').keyup(function (e){
        if(e.keyCode == 13){
            var curr = getCaret(this);
            var val = $(this).val();
            var end = val.length;
            $(this).val( val.substr(0, curr) + '<br/><br/>' + val.substr(curr, end));
            var prev = curr+10;
            setCaretPosition("id_inp_story_paragraph", prev);
        }
    })
  });

</script>
