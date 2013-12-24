<?php
include_once 'fb.php';

$username = "code_lixir";
$password = "codechabi";
$hostname = "localhost"; 

//connection to the database
$dbhandle = mysql_connect($hostname, $username, $password)
  or die("Unable to connect to MySQL");

$selected = mysql_select_db("code_lixir",$dbhandle)
or die("Could not select db");

$result = mysql_query("SELECT page, pos FROM quranfb where id=1");
$row = mysql_fetch_object($result);

$page=$row->page;
$pos=$row->pos;

$api_path='http://api.globalquran.com/page/'.$page.'/en.pickthall';
$json=json_decode(file_get_contents($api_path),true);

$data=$json['quran']['en.pickthall'];

/*$keys=array_keys($data);

$start=array_shift($keys);
$end=array_pop($keys);

if($pos==0)
	$pos=$start;

$output="";*/

foreach ($data as $i=>$d){
	//if($i>=$pos)
		$output.=$d['surah'].':'.$d['ayah'].' '.$d['verse'].'
		';
	
	/*if(strlen($output)>300){
		break;
	}*/	
}

/*if($i<$end)
	$pos=$i+1;
else{
	$pos=0;
	$page++;
}*/

FB::post($output);

$page++;

$query = mysql_query("update quranfb set page=$page,pos=$pos where id=1");
$result = mysql_query($query);

FB::extend_token();
?>