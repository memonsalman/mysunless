<?php

require_once "header.inc.php";
require_once "../src/functions.inc.php";

$token = skydrive_tokenstore::acquire_token(); // Call this function to grab a current access_token, or false if none is available.
/*
$token = "EwAgA61DBAAUGCCXc8wU/zFu9QnLdZXy+YnElFkAAUrvH2XmiPsr7yRQBzedv3nwEe4HGO4Ld9AEW1LZZ+aYN5NE6FxPZUHO7gvH68Dybr+ZaRplhnnae/4Kqva3ZCkZxuRm+FqWNrJmXvcbkAT14gyi1ID7k3LwTN1H5rANUU+l+FetGkFLTFAPhCYqc3+N/DytpDstEgl4udTMoqDc0UaRF5IO+gxtmLB55DOR4PAktrbjT9TOjzwf0TX1SkK8zdTnmhQ6JAlrX/CvGtHEeq7AiobYez7habC7cLIlRtTbtnIGGoYu1BlLJssAAD0ANRpU+BJ4MLrKb903szIhVCBQpRwLiSr0xqhc47Y6uzb+Mkm1NZLOhfJaVllNonoDZgAACKHSczmCJlNw8AFtnRZtS7+L2hX1bcyqJWtMa0VRB62rcuSeUPDiyZO+NZQzwasmoy3IXcQKaqO/7CcElwCLIOe8S8QBEeFd9Mv9zyOtXpw8attXrsB5PKk9cjdS+SNiBAnQ4NQyGDpbAqjsGhwZ+1gLpbwMR+TBuh1TgY6jL3uYy00wwyDhM2ZMvx4kSXSBOAcJ72d7JOHPEW3snJiCmQ0uy/8CQgejDBYUW1hhapDt1DqB7WS5Br7HBJlkp/co1xFI7hj9WbAM0D/6VJs3HkX0qcvSbYwuaT+jtmDCDDiTcbCpBuuLFE4Zz2Av/ghMVuTLHtKAo3DtwVeFYbjgVD5Y7+ZW+vtgfY8SESAyka6yEGlNzmdahut+S6LtvzfftThxCVccof9i2hahDvxq6TAwFcmtCfAMK3ndlG2Ww47BnEEeMyg/6C8Eqz9mVfU63uywNSI+AQFZ8tmS+efLwqpgEMOBwLG8s3bRTxUmHGAK0BAMeqY0e4R5hGTwoh5vLyJosXXoyL7zlhD3fBzvvMXBPQ1PgMaxAeKEWugzpN0hDi8X6p9by8S0TsZM1ySbnTh54obssSfLS4gQZQzR9wKpeVrQF9SWPwwMIT0M4neWGL/tGQOXHFw5vEr1eLAZBSqJxrYYvD6cj8QfcDNsoBi8y/5i+hxz+uygIAI=";
$temp_name = "testfolder23";
$sd = new skydrive($token);
try {
	$response = $sd->create_folder(null, $temp_name, 'Description');
	print_r($response);
} catch (Exception $e) {
	// An error occured, print HTTP status code and description.
	echo "Error: ".$e->getMessage();
	exit;
}	
exit; */
if (!$token) { // If no token, prompt to login. Call skydrive_auth::build_oauth_url() to get the redirect URL.
	echo "<div>";
	echo "<img src='statics/key-icon.png' width='32px' style='vertical-align: middle;'>&nbsp";
	echo "<span style='vertical-align: middle;'><a href='".build_oauth_url()."'>Login with SkyDrive</a></span>";
	echo "</div>";
	
} else {

	if (empty($_POST['foldername'])) {
		echo 'Error - no new folder name specified';
	} else {
		$sd = new skydrive($token);
		try {
			if (empty($_POST['currentfolderid'])) {
				$response = $sd->create_folder(null, $_POST['foldername'], 'Description');
			} else {
				$response = $sd->create_folder($_POST['currentfolderid'], $_POST['foldername'], 'Description');				
			}
			// Folder was created, return metadata.
			print_r($response);
		} catch (Exception $e) {
			// An error occured, print HTTP status code and description.
			echo "Error: ".$e->getMessage();
			exit;
		}		
	}


}
require_once "footer.inc.php";
?>