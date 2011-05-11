#! /bin/bash

clear
while :
do
sleep 0 &
pid=$!
exec 6>&1
exec 1>cache.dat
echo "- - -\n"
exec 1>&-
exec 1>&6
exec 6>&-
echo "Scraping torrents..."
/Applications/MAMP/bin/php5/bin/php -f /Applications/MAMP/htdocs/tools/import.php
VAR=$(cat in.dat)
./scrapec $VAR & sleep 15; kill -9 $!
/Applications/MAMP/bin/php5/bin/php -f /Applications/MAMP/htdocs/tools/export.php
rm cache.dat
wait $pid
done