<?php
/**
 * Created by PhpStorm.
 * User: coag
 * Date: 23-10-2016
 * Time: 15:13
 */
echo "Send key start... <br>";

//require_once 'myLogger.php';
//require_once 'controller/controllerIncludes.php';

//$fileName = "resource/test.txt";
//$fileName = "resource/15aEmailKeys.txt";
//$fileName = "resource/15bEmailKeys.txt";
//$fileName = "resource/15iEmailKeys.txt";
//$fileName = "resource/easj3EmailKeys.txt";
$fileName = "resource/allEmailKey.txt";

$fromEmail="coag@kea.dk";
//$fromEmail="cogh@easj.dk";

//sendKeys($fileName, $fromEmail);

function sendKeys($fileName, $fromEmail) {
    echo "File: $fileName <br>";
    echo "fromEmail: $fromEmail <br>";

    $file = fopen($fileName, "r");
    if ($file) {
        while (($line = fgets($file)) !== false) {
            $userInfo = explode(" ", trim($line));
            $toEmail = $userInfo[0];
            $userName = $userInfo[1];
            $key = $userInfo[2];
            echo "Sending to $toEmail, $userName, $key, $fromEmail";
            sendEmailKey($toEmail, $userName, $key, $fromEmail);
        }

        fclose($file);
    } else {
        // error opening the file.
        die("Unable to open file! ($fileName)");
    }
    //var_format("Done sending.");
}

function sendEmailKey($toEmail, $userName, $key, $fromEmail) {
    $to = $toEmail;

    $subject = "Bank-api. Mandatory 2. The Bank Info";

    $message = "
<html>
<head>
<title>Bank-api. Mandatory 2. The Bank Info</title>
</head>
<body>
<p>Hi all,</p>
<p>We will oficialy start on Monday. This means that everything is going to be reseted on Monday (the 31st of Oct 2016) at 09:00am. </p>
<p></p>
<p></p>
<p>Best Regards,<br>
Alex</p>
</body>
</html>
";

    /*
    $subject = "Bank-api. Here is your apikey.";

    $message = "
<html>
<head>
<title>Bank-api. Here is your apikey.</title>
</head>
<body>
<p>This email contains the apikey for the Bank-api.</p>
<p>You will need this for your Second Mandatory Assignment (SWC 3 course).</p>
<p></p>
<table>
<tr>
<th>USER/currency</th>
<th>KEY</th>
</tr>
<tr>
<td>$userName</td>
<td><pre>$key</pre></td>
</tr>
</table>
<p></p>
<p>Best Regards,<br>
Alex</p>
</body>
</html>
";
*/
// Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
    $headers .= 'From: Constantin Alexandru Gheorghiasa <' . $fromEmail . '>' . "\r\n";
//    $headers .= 'Cc: myboss@example.com' . "\r\n";
    $status = mail($to, $subject, $message, $headers);
    echo ($toEmail);
    echo ($status);
}