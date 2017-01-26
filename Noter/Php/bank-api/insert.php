<?php
/**
 * Created by PhpStorm.
 * User: coag
 * Date: 23-10-2016
 * Time: 13:19
 */
require_once 'myLogger.php';
require_once 'controller/controllerIncludes.php';


//$fileName = "resource/15a.txt";
//$fileName = "resource/15b.txt";
//$fileName = "resource/15i.txt";
//$fileName = "resource/easj3.txt";

$fileName = "resource/15aEmailKeys.txt";
//$fileName = "resource/15bEmailKeys.txt";
//$fileName = "resource/15iEmailKeys.txt";
//$fileName = "resource/easj3EmailKeys.txt";
//$fileName = "resource/allEmailKey.txt";

$emailPostfix="@stud.kea.dk";
//$emailPostfix="@edu.easj.dk";

//insertStudents($fileName);
//insertStudentsWithKey($fileName);
//writeStudents($fileName, $emailPostfix);

function insertStudents($fileName) {
    $file = fopen($fileName, "r");
    if ($file) {
        while (($line = fgets($file)) !== false) {
            $userName = trim($line);
            var_format(UserController::insert($userName));
        }

        fclose($file);
    } else {
        // error opening the file.
        die("Unable to open file! ($fileName)");
    }
    $users = UserController::selectAll();
    var_format("Inserted into DB.");
    var_format($users);
}

function writeStudents($fileName, $emailPostfix) {
    $myfile = fopen($fileName, "w") or die("Unable to open file! ($fileName)");
    $users = UserController::selectAll();
    foreach ($users as $usr) {
        $userName = strtolower($usr->getName());
        $email = $userName . $emailPostfix;
        $key = $usr->getApikey();
        $txt = "$email $userName $key\n";
        fwrite($myfile, $txt);
    }
    fclose($myfile);
    var_format("Saved into file. ($fileName)");
    var_format($users);
}

function insertStudentsWithKey($fileName) {
    var_format("This will insert into DB or write to File");
    $file = fopen($fileName, "r");
    if ($file) {
        while (($line = fgets($file)) !== false) {
            $userInfo = explode(" ", trim($line));
            $userName = $userInfo[1];
            $key = $userInfo[2];
            var_format(UserController::insert($userName, $key));
        }

        fclose($file);
    } else {
        // error opening the file.
        die("Unable to open file! ($fileName)");
    }
    $users = UserController::selectAll();
    var_format("Inserted into DB.");
    var_format($users);
}