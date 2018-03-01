<?php
// THIS PROJECT IS FOR WEB MINING J COMPONENT.
// THIS CODE IS USED TO CRAWL THE WEB PAGE AND EXTRACT INFORMATION.
// ITS A TROPICAL CRAWLER.
// IT FOLLOWS BREADTH FIRST ALGORITHM.

$start = "https://www.w3schools.com/";
//$start = "https://www.tutorialspoint.com/";
//$start = "https://www.csstutorial.net/";

//DATABSE INITIALISATION...
 $dbhost="localhost";
 $dbuser="root";
 $dbpass="";
 $dbname="web";
 $connection=mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);
  if(mysqli_connect_errno()){
    die("Database connection failed:"
      );
  }
 //$query="INSERT INTO frontier(title,description,keywords,url) values('mak','mak','mak','mak.com');";
	
$already_crawled = array();
$crawling = array();


// THIS FUNCTION EXTRACTS THE DETAIL OF THE URL GIVEN TO IT....
function get_details($url) {

	global $connection;
	$options = array('http'=>array('method'=>"GET", 'headers'=>"User-Agent: MAKBOT/1.0\n"));
	$context = stream_context_create($options);
	$doc = new DOMDocument();
	@$doc->loadHTML(@file_get_contents($url, false, $context));

	//EXTRACTING TITLE, DESCRIPTION AND KEYWORDS...
	$title = $doc->getElementsByTagName("title");
	$title = $title->item(0)->nodeValue;
	$description = "";
	$keywords = "";
	
	
	
	
	$metas = $doc->getElementsByTagName("meta");
	for ($i = 0; $i < $metas->length; $i++) {
		$meta = $metas->item($i);
		if (strtolower($meta->getAttribute("name")) == "description")
			$description = $meta->getAttribute("content");
		if (strtolower($meta->getAttribute("name")) == "keywords")
			$keywords = $meta->getAttribute("content");
	}
	
	//$title     = mysql_real_escape_string($title);
	//$description     = mysql_real_escape_string($description);
	//$keywords     = mysql_real_escape_string($keywords);
	//$url     = mysql_real_escape_string($url);



	
	
	
	$metas = $doc->getElementsByTagName("meta");
	for ($i = 0; $i < $metas->length; $i++) {
		$meta = $metas->item($i);
		if (strtolower($meta->getAttribute("name")) == "description")
			$description = $meta->getAttribute("content");
		if (strtolower($meta->getAttribute("name")) == "keywords")
			$keywords = $meta->getAttribute("content");
	}
	
	//$title     = mysql_real_escape_string($title);
	//$description     = mysql_real_escape_string($description);
	//$keywords     = mysql_real_escape_string($keywords);
	//$url     = mysql_real_escape_string($url);

	
	
	$password = '3sc3RLrpd17';
	$method = 'aes-256-cbc';

// Must be exact 32 chars (256 bit)
$password = substr(hash('sha256', $password, true), 0, 32);


// IV must be exact 16 chars (128 bit)
$iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

// ENCRYPTION
$title1 = base64_encode(openssl_encrypt($title, $method, $password, OPENSSL_RAW_DATA, $iv));
//$description1 = base64_encode(openssl_encrypt($descryption, $method, $password, OPENSSL_RAW_DATA, $iv));
//$keywords1 = base64_encode(openssl_encrypt($keywords, $method, $password, OPENSSL_RAW_DATA, $iv));
$url1 = base64_encode(openssl_encrypt($url, $method, $password, OPENSSL_RAW_DATA, $iv));
	
	
	//FOR DEBUGGING...
	echo "$title1</br>";
	echo "<br>";
//	echo "$description1</br>";
	echo "<br>";
//	echo "$keywords1</br>";
	echo "<br>";
	echo "$url1</br>";
	echo "<br>";
	echo "<br>";
	echo "$title</br>";
	echo "<br>";
	echo "$description</br>";
	echo "<br>";
	echo "$keywords</br>";
	echo "<br>";
	echo "$url</br>";
	echo "<br>";



	//DATABASE ACCESSING AND INSERTION...
	$query="INSERT INTO frontier(title,description,keywords,url) values('{$title1}','{$description}','{$keywords}','{$url1}');";
	//$query="INSERT INTO frontier(title,description,keywords,url) values($title,$description,$keywords,$url);";
	$result=mysqli_query($connection,$query);
    if($result)
      $contact="success";
    else
      $contact="failure";
	echo "$contact.<p></p>";
 	
	//RETURNING THE TITLE, DESCRIPTION AND KEYWORDS...
	return '{ "Title": "'.str_replace("\n", "", $title).'", "Description": "'.str_replace("\n", "", $description).'", "Keywords": "'.str_replace("\n", "", $keywords).'", "URL": "'.$url.'"},';
}

// THIS FUNCTION EXTRACTS ALL THE LINKS FROM THE URL PROVIDED TO IT.
function follow_links($url) {
	global $already_crawled;
	global $crawling;
	$options = array('http'=>array('method'=>"GET", 'headers'=>"User-Agent: MAKBOT/1.0\n"));
	$context = stream_context_create($options);
	$doc = new DOMDocument();
	@$doc->loadHTML(@file_get_contents($url, false, $context));
	$linklist = $doc->getElementsByTagName("a");
	foreach ($linklist as $link) {
		
		// SAVING THE LINK OR URL IN $L VARIABLE...
		$l =  $link->getAttribute("href");
		
		// EDITING THE LINK FOR RUNING IT IN THE BROWSER....
		if (substr($l, 0, 1) == "/" && substr($l, 0, 2) != "//") {
			$l = parse_url($url)["scheme"]."://".parse_url($url)["host"].$l;
		} else if (substr($l, 0, 2) == "//") {
			$l = parse_url($url)["scheme"].":".$l;
		} else if (substr($l, 0, 2) == "./") {
			$l = parse_url($url)["scheme"]."://".parse_url($url)["host"].dirname(parse_url($url)["path"]).substr($l, 1);
		} else if (substr($l, 0, 1) == "#") {
			$l = parse_url($url)["scheme"]."://".parse_url($url)["host"].parse_url($url)["path"].$l;
		} else if (substr($l, 0, 3) == "../") {
			$l = parse_url($url)["scheme"]."://".parse_url($url)["host"]."/".$l;
		} else if (substr($l, 0, 11) == "javascript:") {
			continue;
		} else if (substr($l, 0, 5) != "https" && substr($l, 0, 4) != "http") {
			$l = parse_url($url)["scheme"]."://".parse_url($url)["host"]."/".$l;
		}
		
		// CHECKING FOR ALREADY CRAWLED. NOTE: A DATABASE WILL BE CREATED ON THE LOCALHOST TO STORE ALL THE URLS WHICH ARE ALREADY CRAWLED, OR IT WILL CHECK FROMTHE RANKING TABLE.
		if (!in_array($l, $already_crawled)) {
				$already_crawled[] = $l;
				$crawling[] = $l;

				echo get_details($l)."<p></p>";
		}
	}
	array_shift($crawling);
	foreach ($crawling as $site) {
		follow_links($site);
	}
}
follow_links($start);
?>

