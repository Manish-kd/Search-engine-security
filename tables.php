<?php

$conn = mysqli_connect("localhost","root","","web");

    if (!$conn)
  {
    die('Could not connect: ' . mysqli_error());
	echo "not connected";
  }
//    echo "Great Job";
	
$password = '3sc3RLrpd17';		// password is the KEY
$method = 'aes-256-cbc';

// Must be exact 32 chars (256 bit)
$password = substr(hash('sha256', $password, true), 0, 32);
echo "Password:" . $password ;
echo "<br>";


// IV must be exact 16 chars (128 bit)
$iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

// DECRYPTION
//$decrypted = openssl_decrypt(base64_decode($encrypted), $method, $password, OPENSSL_RAW_DATA, $iv);	
	
	
	
	
    $a = $_GET['query'];
	$a = $a ;
echo $a;

//  $query1 = "INSERT INTO names(firstname, lastname, register) values('$a','$b','$c');";
   
 // mysqli_query($conn,$query1);

 // $query2 = "SELECT * FROM frontier WHERE keywords LIKE '%$a%' and title LIKE '%$a%';"; 
  $query2 = "SELECT * FROM frontier WHERE keywords LIKE '%$a%';";
//echo $query2;
  $run  = mysqli_query($conn,$query2);

if (!$run)
{
	echo "This project is doomed";
}

  $i=0;
  $j=0;

  while($result = mysqli_fetch_assoc($run))
  {
    $title[] = $result['title'];
    $description[] = $result['description'];
    $keywords[] = $result['keywords'];
    $url[] = $result['url'];
    $i++;
  }


//for debugging and not for normal use
//  print_r($title);
//  echo "\n";
//  print_r($description);
//  echo "\n";
//  print_r($keywords);
//  echo "\n";
//  print_r($i);
//  print_r($url);
//  echo "\n";

  mysqli_close($conn);

?>

<html>
<head>
  <title></title>
  <script type="text/javascript">
</script>

<style type="text/css">
  table, th, td {
    border: 1px solid black;
}

th,td{
  padding-left: 5px;
  padding-right: 5px;
}
</style>

  
</head>
<body>

<h1>THE RESULTS OF YOUR QUERY IS GIVEN BELOW: </h1>


<table style="margin-top: 20px; margin-left: 20px; ">
  <tr>
    <th>Title</th>
	<th>Description</th>
    <th>Keywords</th>
    <th>URL</th>
	</tr>
  <?php for($j=1; $j<=$i; $j++)
  {;
    ?>
  <tr>
    <td><?php
$title1[$j-1] = openssl_decrypt(base64_decode($title[$j-1]), $method, $password, OPENSSL_RAW_DATA, $iv);	
	echo $title1[$j-1]; ?></td> 
    <td><?php
$description1[$j-1] = openssl_decrypt(base64_decode($description[$j-1]), $method, $password, OPENSSL_RAW_DATA, $iv);	
	echo $description[$j-1]; ?></td>
    <td><?php
//$keywords1[$j-1] = openssl_decrypt(base64_decode($keywords[$j-1]), $method, $password, OPENSSL_RAW_DATA, $iv);	
	echo $keywords[$j-1]; ?></td>  
    <td><a href="<?php
$url1[$j-1] = openssl_decrypt(base64_decode($url[$j-1]), $method, $password, OPENSSL_RAW_DATA, $iv);	
	echo $url1[$j-1]; ?>" target="_blank"><?php echo $url1[$j-1]; ?></a></td>  
  </tr>
  <?php }; ?>
  
</table>





</body>
</html>

