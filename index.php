<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<script src="js/strlen.js"></script>
	<script src="js/jquery-1.8.0.js"></script>
	<script src="js/jquery.jqEasyCharCounter.min.js" type="text/javascript"></script>
	<script type="text/javascript">
	$(document).ready(function(){
		$('#tweet').jqEasyCounter({
			'maxChars': 140,
			'maxCharsWarning': 135
		});
	});
	</script>
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<link rel="stylesheet" href="fonts/asap/stylesheet.css" media="all" type="text/css"/>
	<link rel="stylesheet" href="fonts/beb/stylesheet.css" media="all" type="text/css"/>
	<link rel="stylesheet" href="fonts/col/stylesheet.css" media="all" type="text/css"/>
	
	<title>Boooommm!</title>
</head>
<body>

<?php
if(!empty ($_POST['tweet'])){

require 'core/tmhOAuth.php';
require 'core/tmhUtilities.php';
$tmhOAuth = new tmhOAuth(array(
  'consumer_key'    => 'Qi0SAj0cr5BJwTydsBM9zg',
  'consumer_secret' => 'Xg2yHEn71oUvVSL5705X9o5OlAm3CwTZ8zM5yyoPUc',
  'user_token'      => '109829124-cjf6bBagKGVwbqAJ8vVMFiqAEHtyGD2GEGR3af0I',
  'user_secret'     => 'B64uzKRTCr7foPSkjhhHfzhL5uFAlOGiVMq0jYiLM',
));

	$trends_url = "http://api.twitter.com/1/statuses/followers/masteg_.json";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $trends_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$curlout = curl_exec($ch);
	curl_close($ch);
	$response = json_decode($curlout, true);

		foreach($response as $friends){
			$thumb = $friends['profile_image_url'];
			$url = $friends['screen_name'];
			$name = $friends['name'];
		}

	if($_FILES['image']['name'] != ""){
		$tweet = $_POST['tweet'];
		$image = "@{$_FILES['image']['tmp_name']};type={$_FILES['image']['type']};filename={$_FILES['image']['name']}";
		
		$code = $tmhOAuth->request(
		'POST',
		'https://upload.twitter.com/1/statuses/update_with_media.json',
		array(
			'media[]'  => $image,
			'status'   => $tweet,
		),true,true);

			if($code == 200){
				//tmhUtilities::pr(json_decode($tmhOAuth->response['response']));
				echo "Tweet sent!";
			}else{
				//tmhUtilities::pr($tmhOAuth->response['response']);
				echo "Something wrong!";
			}
	}else{
		$tweet = $_POST['tweet'];
		
		$code = $tmhOAuth->request(
		'POST', $tmhOAuth->url('1/statuses/update'), array(
		'status' => $tweet
		));

			if ($code == 200) {
			  //tmhUtilities::pr(json_decode($tmhOAuth->response['response']));
			  echo "Tweet sent!";
			} else {
			  //tmhUtilities::pr($tmhOAuth->response['response']);
			  echo "Something wrong!";
			}
	}
	
}
?>
<script>
function setbg(color)
{
document.getElementById("tweet").style.background=color
}
function checkValue(){
	var tweet = document.getElementById("tweet").value;
	if(tweet == ''){
		alert('Type something :9');
		return false;
	}
}
</script>

<form action="index.php" method="POST" enctype="multipart/form-data">
  <div id="wrapper-tweet">
	<div id="logo">
	<a href="index.php"><img src="img/logo.png"></img></a>
	</div>
	<center>
    <textarea id="tweet" name="tweet" cols="50" rows="5" onfocus="setbg('#e5fff3');" onblur="setbg('white')"></textarea><br/>
	</center>
	<div id="prop">
	<input type="file" name="image" /><p/>
	<input id="submit" type="submit" value="Tweet this!" onClick="javascript:checkValue();"/>
	</div>
  </div>
</form>

</body>
</html>