#! /usr/bin/php
<?php
	ob_start('ob_gzhandler');
	
include("config.php");
	
	function dbconn() 
		{
		global $mysql_host, $mysql_user, $mysql_pass, $mysql_db;
		if (!@mysql_connect($mysql_host, $mysql_user, $mysql_pass))
			{
	  		die('dbconn: mysql_connect: ' . mysql_error());
			}
		mysql_select_db($mysql_db)
	    	or die('dbconn: mysql_select_db: ' + mysql_error());
		}
	
	function hex2bin($hexdata) 
		{
	  	$bindata = "";
	  	for ($i=0;$i<strlen($hexdata);$i+=2) 
	  		{
	    	$bindata.=chr(hexdec(substr($hexdata,$i,2)));
	  		}
	  	return $bindata;
		}

	dbconn();
	
	header("Content-Type: text/plain");
	
	$source = fopen("in.dat","w");
	$source2 = fopen("hash.dat","w");

	$query = mysql_query("SELECT info_hash, announce_url FROM namemap WHERE skip='no' ORDER BY `lastupdate`  ASC LIMIT 0 , 1") or die(mysql_error());
	$i=0;
    $content = '';
    
	while ($row = mysql_fetch_array($query))
		{
		$info_hash = hex2bin($row['info_hash']);
		$announce_url = str_replace("/announce","/scrape",$row['announce_url']);
		$i++;
		$url = $announce_url;
		$content .= sprintf("%s %s\n",$url,$row['info_hash']);
		$content2 .= sprintf("%s\n",$row['info_hash']);
		}
	
	echo "Successfully imported $i torrents\n";
	fwrite($source,$content);
	fwrite($source2,$content2);
	fclose($source);
	
?>