<?php
//cant have output before setcookie
if(!isset($_COOKIE['test'])){
	setcookie('test','55',time()+3600);
}

?>

<pre>
	<?php print_r($_COOKIE); ?>
</pre>
<p> <a href="cookie.php"> Click Me! </a> or press Refresh </p>