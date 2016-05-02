#!/usr/bin/python
# libraries
import sys
import urllib2
import json
import Adafruit_DHT
import sqlite3
import Adafruit_BMP.BMP085 as BMP085
import datetime
import luxreader
import time
luxrdr = luxreader.TSL2561()
am2302Pin = 21
counter=0
useAM2302 = True
useBMP180 = True
useTSL2561 = True

while True:
	humidity, temperature1 = Adafruit_DHT.read_retry(Adafruit_DHT.AM2302 , am2302Pin)
	print 'AM2302:'
	print 'Temp Sensor 1 = {0:0.1f} *C'.format(temperature1)
	print 'Humidity={0:0.1f}%'.format(humidity) + '\n'

	sensor = BMP085.BMP085(mode=BMP085.BMP085_ULTRAHIGHRES)

	pressure = sensor.read_pressure()
	altitude = sensor.read_altitude()
	slpressure = sensor.read_sealevel_pressure()
	temperature2 = sensor.read_temperature()
	print 'BMP180:'
	print 'Temp Sensor 2 = {0:0.2f} *C'.format(temperature2)
	print 'Pressure = {0:0.2f} Pa'.format(pressure)
	print 'Altitude = {0:0.2f} m'.format(altitude)
	print 'Sealevel Pressure = {0:0.2f} Pa'.format(slpressure) + '\n'

	lux = luxrdr.readLux()
	if (lux == 0 & counter < 7):
	    lux = luxrdr.readLux()
	    counter+=1
	lux = luxrdr.readLux()
	print 'TSL2561:'
	print 'Lux: ' + str(lux) + '\n'
	row_init=1
	avgTemp = ((temperature1 + temperature2 ) / 2)
	tempList = [temperature1,temperature2]
	highestTemp = max(tempList)
	lowestTemp = min(tempList)
	temp=avgTemp
	print "Writing to db"
	conn=sqlite3.connect('sensor.sqlite')
	curs=conn.cursor()
	curs.execute("INSERT INTO sensor values(datetime('now'),?,?,?,?,?,?,null)",(temp,humidity,pressure,altitude,slpressure,lux))
	conn.commit()
	conn.close()
	print "Highest: " + str(highestTemp)
	print "Lowest: " + str(lowestTemp)
	print "Average: " + str(avgTemp)
	print "Variance: " + str(highestTemp - lowestTemp) + '\n'
	print '************************\n'
	time.sleep(60)
