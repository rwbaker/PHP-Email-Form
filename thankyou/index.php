<?php

error_reporting(E_ALL ^ E_NOTICE);

/*
95% of this code is from FormToEmail:http://formtoemail.com/FormToEmail.txt
Thank you for choosing FormToEmail by FormToEmail.com; Version 2.5 April 16th 2009
---------------------------------------------------------------------------------------------------

SETUP INSTRUCTIONS

Step 1: To put the form on your webpage, copy the code below as it is, and paste it into your webpage:

<form action="thankyou/" method="post">
<table border="0" style="background:#ececec" cellspacing="5">
<tr align="left"><td>Name</td><td><input type="text" size="30" name="name"></td></tr>
<tr align="left"><td>Email address</td><td><input type="text" size="30" name="email"></td></tr>
<tr align="left"><td valign="top">Comments</td><td><textarea name="comments" rows="6" cols="30"></textarea></td></tr>
<tr align="left"><td>&nbsp;</td><td><input type="submit" value="Send"></td></tr>
</table>
</form>

Step 2: Enter your email address.

Enter the email address below to send the contents of the form to.  You can enter more than one email address separated by commas, like so: $my_email = "info@example.com"; or $my_email = "bob@example.com,sales@example.co.uk,jane@example.com";
*/

$my_email = "admin@rwbaker.com";

/*
Optional.  Enter a From: email address.  By default, the email you get from the script will show the visitor's email address as the From: address.  In most cases this is desirable.  On the majority of setups this won't be a problem but a minority of hosts insist that the From: address must be from a domain on the server.  
*/
$from_email = "";

/* Subject line */
$subject = "Contact from RWBAKER.COM";

/* Site URL */
$site_url = "http://www.rwbaker.com";

/* Site Name */
$site_name = "RWBAKER.COM";

/* 
Optional.  Enter the continue link to offer the user after the form is sent.  If you do not change this, your visitor will be given a continue link to your homepage.
If you do change it, remove the "/" symbol below and replace with the name of the page to link to, eg: "mypage.htm" or "http://www.elsewhere.com/page.htm"
*/
$continue = "/";

/*
Step 3: Save this file (/thankyou/index.php) and upload it together with your webpage containing the form to your webspace.  IMPORTANT - The file name is case sensitive!  You must save it exactly as it is named above!
*/

$errors = array();

// Remove $_COOKIE elements from $_REQUEST.

if(count($_COOKIE)){foreach(array_keys($_COOKIE) as $value){unset($_REQUEST[$value]);}}

// Validate email field.

if(isset($_REQUEST['email']) && !empty($_REQUEST['email']))
{

$_REQUEST['email'] = trim($_REQUEST['email']);

if(substr_count($_REQUEST['email'],"@") != 1 || stristr($_REQUEST['email']," ")){$errors[] = "Email address is invalid";}else{$exploded_email = explode("@",$_REQUEST['email']);if(empty($exploded_email[0]) || strlen($exploded_email[0]) > 64 || empty($exploded_email[1])){$errors[] = "Email address is invalid";}else{if(substr_count($exploded_email[1],".") == 0){$errors[] = "Email address is invalid";}else{$exploded_domain = explode(".",$exploded_email[1]);if(in_array("",$exploded_domain)){$errors[] = "Email address is invalid";}else{foreach($exploded_domain as $value){if(strlen($value) > 63 || !preg_match('/^[a-z0-9-]+$/i',$value)){$errors[] = "Email address is invalid"; break;}}}}}}

}

// Check referrer is from same site.

if(!(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']) && stristr($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_HOST']))){$errors[] = "You must enable referrer logging to use the form";}

// Check for a blank form.

function recursive_array_check_blank($element_value)
{

global $set;

if(!is_array($element_value)){if(!empty($element_value)){$set = 1;}}
else
{

foreach($element_value as $value){if($set){break;} recursive_array_check_blank($value);}

}

}

recursive_array_check_blank($_REQUEST);

if(!$set){$errors[] = "You cannot send a blank form";}

unset($set);

// Display any errors and exit if errors exist.

if(count($errors)){foreach($errors as $value){print "$value<br>";} exit;}

if(!defined("PHP_EOL")){define("PHP_EOL", strtoupper(substr(PHP_OS,0,3) == "WIN") ? "\r\n" : "\n");}

// Build message.

function build_message($request_input){if(!isset($message_output)){$message_output ="";}if(!is_array($request_input)){$message_output = $request_input;}else{foreach($request_input as $key => $value){if(!empty($value)){if(!is_numeric($key)){$message_output .= str_replace("_"," ",ucfirst($key)).": ".build_message($value).PHP_EOL.PHP_EOL;}else{$message_output .= build_message($value).", ";}}}}return rtrim($message_output,", ");}

$message = build_message($_REQUEST);

$message = $message . PHP_EOL.PHP_EOL."-- ".PHP_EOL."Thank you for using FormToEmail from http://FormToEmail.com";

$message = stripslashes($message);

$subject = stripslashes($subject);

if($from_email)
{

$headers = "From: " . $from_email;
$headers .= PHP_EOL;
$headers .= "Reply-To: " . $_REQUEST['email'];

}
else
{

$from_name = "";

if(isset($_REQUEST['name']) && !empty($_REQUEST['name'])){$from_name = stripslashes($_REQUEST['name']);}

$headers = "From: {$from_name} <{$_REQUEST['email']}>";

}

mail($my_email,$subject,$message,$headers);

/*
<b>Thank you <?php if(isset($_REQUEST['name'])){print stripslashes($_REQUEST['name']);} ?></b>
*/

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="refresh" content="5;url=<?php print $site_url; ?>">
	<title>Thank you for contacting <?php print $site_name; ?></title>
	<style>
		body 		{font-family:Arial, sans-serif; font-size:13px;}
		p 			{line-height:1.5em;}
		#thankyou 	{margin:200px auto 0px; width:400px; background:#efefef; border:1px solid #ccc; padding:20px;
					-moz-border-radius: 10px; 
					-webkit-border-radius: 10px; 
					border-radius:10px;
					-webkit-box-shadow: 0px 3px 3px #eee;
					-moz-box-shadow: 0px 3px 3px #eee;
					box-shadow: 0px 3px 3px #eee;}
	</style>
</head>
<body>
	<div id="thankyou">
		<p><strong>Thank you for contacting us, your message has been sent.</strong></p>
		<p>If you're not redirected in 5 seconds, then <a href="<?php print $continue; ?>">click here to go to the homepage.</a></p>
	</div>
</body>
</html>
