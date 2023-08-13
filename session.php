<?php
	session_start();
	if(!isset($_SESSION['session-instance'])){
		echo "<p> Session empty</p><br>";
		$_SESSION['session-instance']=0;
	}
	elseif(isset($_SESSION['session-instance']) && $_SESSION['session-instance'] <3){
		$_SESSION['session-instance']=$_SESSION['session-instance']+1;
		echo "<p> Added one...</p><br>";
	}
	else{
		session_destroy();
		session_start();
		echo "<p>Session Restarted</p><br>";
	}
?>

<p><a href="session.php">Click Me!</a></p>
<p> Our Session ID is:<?php echo(session_id());?></p>
<pre>
	<?php print_r($_SESSION); ?>
</pre>