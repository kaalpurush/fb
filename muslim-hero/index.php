 <?php
include_once 'fb.php';
if(!FB::logged())
	header('location:'.FB::login_url());

//$page_info=FB::getSignedRequest();
//echo "<pre>";
//print_r($page_info);

$user=FB::api('/me');

$name=$user['name'];
$gender=$user['gender'];

$name='Kaal Purush';
$gender='male'; 

$heroes=array(
			array('name'=>'Saladin','image'=>'saladin.jpg','description'=>'','wikipedia'=>'http://en.wikipedia.org/wiki/Saladin'),
			array('name'=>'Tariq Ibn Ziyad','image'=>'tariq.jpg','description'=>'','wikipedia'=>'http://en.wikipedia.org/wiki/Tariq_Ibn_Ziyad'),
			array('name'=>'Sultan Mehmed','image'=>'mehmed.jpg','description'=>'','wikipedia'=>'http://en.wikipedia.org/wiki/Mehmed_the_Conqueror'),
			array('name'=>'Parviz Khosrau','image'=>'parviz.jpg','description'=>'','wikipedia'=>'http://en.wikipedia.org/wiki/Khosrau_II_of_Persia'),
			array('name'=>'Mahmud of Ghazni','image'=>'mahmud.jpg','description'=>'','wikipedia'=>'http://en.wikipedia.org/wiki/Mahmud_of_Ghazni'),
			array('name'=>'Shah Jahan','image'=>'shahjahan.jpg','description'=>'','wikipedia'=>'http://en.wikipedia.org/wiki/Shah_Jahan'),
		);

$heroines=array(
			array('name'=>'Razia Sultana','image'=>'razia.jpg','description'=>'','wikipedia'=>'http://en.wikipedia.org/wiki/Razia_Sultana'),
			array('name'=>'Queen Arwa al-Sulayhi','image'=>'arwa.jpg','description'=>'','wikipedia'=>'http://en.wikipedia.org/wiki/Arwa_al-Sulayhi'),
			array('name'=>'Mihrimah Sultana','image'=>'mihrimah.jpg','description'=>'','wikipedia'=>'http://en.wikipedia.org/wiki/Mihrimah_Sultana'),
			array('name'=>'Roxelana','image'=>'roxelana.jpg','description'=>'','wikipedia'=>'http://en.wikipedia.org/wiki/Mihrimah_Sultana'),
		);

$candidates=($gender=='male')?$heroes:$heroines;

$count=count($candidates);

$nameval=0;
for($i=0;$i<strlen($name);$i++){
	$nameval+=ord($name{$i});
}

$hero=$candidates[$nameval%$count];

FB::post($hero);
?>

<div style="width:760px">
	<div style="margin: 0 auto;width:300px;text-align: center;">
		<h1>You are <?php echo $hero['name'];?>.</h1>
		<img src="<?php echo $hero['image'];?>"><br /><br />
		<a href="<?php echo $hero['wikipedia'];?>"><?php echo $hero['wikipedia'];?></a>
	</div>
</div>