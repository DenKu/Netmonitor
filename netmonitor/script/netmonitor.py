#!/usr/bin/python
import os
import subprocess
import datetime
from requests import get

scriptpath = os.path.dirname(os.path.abspath(__file__))

path_servers = scriptpath + "/input/listServers.csv"
path_output = scriptpath + "/results/"
externalip = scriptpath + "/input/ip.csv"
const_MAXLINES = 200

num_servers = 0

starttime = datetime.datetime.now()
timestamp = starttime.strftime("%Y/%m/%d %H:%M:%S")

#Get external IP Address
ip = get('https://api.ipify.org').text
f = open(externalip,"w+")
f.write(timestamp + ";" + ip)
f.close()

file_servers = open(path_servers,"r")

for line in file_servers:
	if num_servers == 0:
		num_servers = 1
	else:
		sepLine = line.split(";",3)
		id = sepLine[0]
		host = sepLine[1]
		hostname = sepLine[2]
		hostdesc = sepLine[3]

		file_output = open(path_output+id+'.csv', 'a+')

		try:
			response = subprocess.check_output(['ping', '-c', '1', host],stderr=subprocess.STDOUT,universal_newlines=True)
			responseline = response.splitlines()
			for x in responseline:
				pass
			resultLine = x

			tmp_result = resultLine.split("=",2)
			result = tmp_result[1].split("/",2)
			result = result[0].strip()
			file_output.write(timestamp + ";" + host + ";" + hostname + ";" + result + "\n")
		except subprocess.CalledProcessError:
			file_output.write(timestamp + ";" + host + ";" + hostname + ";down" + "\n")

		#Logrotate
		file_output.close()
		linecount = 0

		file_output = open(path_output+id+'.csv', 'r')
		head, tail = file_output.read().split('\n', 1)
		file_output.seek(0)

		for inline in file_output:
			linecount = linecount + 1
		file_output.close()

		if linecount > const_MAXLINES:
			file_output = open(path_output+id+'.csv', 'w')
			file_output.write(tail)
