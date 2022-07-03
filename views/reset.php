<?php

$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ( isset( $_POST['submit'] ) ) {
	// Skip this conditional. ChessGame's FEN is the default, new game FEN and doesn't need to be set again.

    $gameid = $_REQUEST['gameid'];
    $keyword = $_REQUEST['keyword'];
    $filegameid="";
    $matched=0;
    $gamefilename=".\livemove\livegame.txt";
    if(file_exists($gamefilename)){
        $file = fopen($gamefilename,"r");

        while(!feof($file)) {
                $line = fgets($file);
                if (strpos($line, '$gameid=') !== false) {
                $splitted = explode( '$gameid=',$line);
                $filegameid = $splitted[1];
                break;
                }
            }
            fclose($file);

            if  (($keyword=="satyendra") || ($gameid.PHP_EOL ==$filegameid)) {
                unlink($gamefilename);
                echo '<p> " File Deleted Successfully" <p/> ';
            }
        }
        else
        {
            echo '<p> "There is no such Game File" <p/>  ';

        }
    }?>

<form action="reset.php" method="post">
<input type="password" name="keyword" placeholder="keyword" />
<input type="text" name="gameid" placeholder="gameid" />
<input type="submit" name="submit" />
</form>