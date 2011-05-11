
<?php
    ob_start('ob_gzhandler');
$handle = fopen("hash.dat", "r");
while ($hashinfo = fscanf($handle, "%s\n")) {
   list ($info_hash) = $hashinfo;


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

    $source = fopen("cache.dat","r");

    $i=0;


    while ($info = fscanf ($source, "%d %d %d\n")) {
        list ($seeders, $completed,$leechers) = $info;
       if (mysql_query("
UPDATE `namemap` SET `seeds` = '$seeders',
`leechers` = '$leechers',
`finished` = '$completed',
`lastupdate` = NOW( ) WHERE `info_hash` = '$info_hash' LIMIT 1 ;
"))
       $i++;
       else mysql_error();
    }
	
    echo "Successfully exported $i torrents\n";
    fclose($source);
}
fclose($handle);

?>