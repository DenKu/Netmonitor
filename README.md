# Netmonitor

## Requirements
Python 3 (https://www.python.org), tested on 3.11

Webserver (e.g. lighttpd)

PHP (https://www.php.net/), tested on 8.1.16


## Dependencies
Chart.js (https://github.com/chartjs), tested on 4.2.1

## Results
The public IP information is written to input/ip.csv.
All the results of the pings are stored in a numeric file (0.csv, 1.csv, ...) according to the set ID below "results/".


## Installation
1. Define the hosts to monitor in netmonitor/script/input/listServers.csv. The script expects the input file "input/listServers.csv" where you can define the hosts which should be checked in a separate line each following the scheme "ID;IP-Address;Name;Description". The is a numeric value which defines the order of the hosts in the results. Use ';' as delimiter.

2. Host the "index.php" and "netmonitor.css" in the desired page directory of your webserver. Also place the folder "netmonitor" there. Do not forget to restrict direct access to the folder as needed in your webserver configuration.

3. Run the python script "/networkmonitor/script/netmonitor.py" to write the result files which are parsed by the php application. You can run the script e.g. in crontab on a regular base.
