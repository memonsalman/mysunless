
<!doctype html>
<html>
    <head>
        <title>googles recaptcha php code example | onlinecode.org</title>
        <script src='https://www.google.com/recaptcha/api.js'></script>
    </head>
    <body>
        <form method="post" action="index.php">
            <div class="g-recaptcha" data-sitekey="6LdssQsUAAAAAESMSzcr353bz6tVQAbJefbjrvF3"></div>
            <input type="submit" />
        </form>
    </body>
</html>

<?php
// http://qnimate.com/googles-recaptcha-php-code-example/
   
    if($_SERVER["REQUEST_METHOD"] === "POST")
    {
        //form submitted

        //check if other form details are correct
	//	Site key = 6LdssQsUAAAAAESMSzcr353bz6tVQAbJefbjrvF3
	//	Secret key = 6LdssQsUAAAAAGuUgBEt06cA9HwegPp3zzBEtbWx
        //verify captcha
        $recaptcha_secret = "6LdssQsUAAAAAESMSzcr353bz6tVQAbJefbjrvF3";
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$recaptcha_secret."&response=".$_POST['g-recaptcha-response']);
        $response = json_decode($response, true);
        if($response["success"] === true)
        {
            echo "Logged In Successfully";
        }
        else
        {
            echo "You are a robot";
        }
    }
	
	?>