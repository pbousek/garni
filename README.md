Visualization of data from weather station Garni 940.

Data from this waether station can be uploaded via Wi-Fi to 4 different weather servers and 1 user's own server. 


Set you device to upload data to your server and script via http GET in "WS view: application  

!! https is not supported (and curretly not planned)

![WS view device setup](/ws_view.png)

I use 
- Debian linux - Buster
- Influxdb 1.8.6 https://computingforgeeks.com/install-influxdb-on-debian-10-buster-linux/
- Grafana 8.0.0 https://grafana.com/docs/grafana/latest/installation/debian/
  - Grafana Gauge panel for wind direction visualization https://github.com/briangann/grafana-gauge-panel

- influxdb php client from https://github.com/influxdata/influxdb-client-php

As garni send values in Anglo-American measurement units - script tries to convert values to more common units in our country:
- F -> C
- InHg -> hPa
- mph -> m/s
- in -> mm

And some settings leaves untouched - for example for weeklyrainin - week starts on sunday. 

Finally it's quite easy to connect grafana to influxdb datasource and add graphs for individual values.

![grafana](/grafana.png)
