<html>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
       <div id="chart_div" style="width: 400px; height: 120px;"></div>
    <head>
        <meta charset="utf-8" />
        <title>Raspberry Pi Sensors</title>
    </head>
    <?php
    header("Refresh:5");
class MyDB extends SQLite3
{
    function __construct()
    {
        $this->open('sensor.sqlite');
    }
}
$db = new MyDB();
if(!$db){
      echo $db->lastErrorMsg();
   } else {
      echo "Success\n";
  }
 $result = $db->query('SELECT * FROM sensor WHERE id = (SELECT MAX(id) FROM sensor)') or die('Query failed');
while ($row = $result->fetchArray())
{
  $temp=$row['temp'];
  $pressure=$row['pressure'];
  $humidity=$row['humidity'];
  $lux=$row['lux'];
//echo "time: {$row['timestamp']}\n temp: {$row['temp']}\n humidity: {$row['humidity']}\n pressure: {$row['pressure']}\n Light: {$row['lux']}\n";
//echo "new data";
//echo $temp;
//echo $pressure;
//echo $humidity;
//echo $lux;
}
$graph=$db->query('SELECT * from sensor order by id desc limit 20');
//for($i=0;$<=20;$x++)
echo $graph;


//print_r(SQLite3::version());
//echo "<br>";
//echo phpversion();
?>
<script type="text/javascript">
var php_temp = "<?php echo $temp; ?>";
var php_hum="<?php echo $humidity?>";
var php_press="<?php echo $pressure?>";
var php_lux="<?php echo $lux?>";
php_lux=php_lux/100;
php_press=(php_press/100)*0.00098692;
google.charts.load('current', {'packages':['gauge']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        php_temp=eval(php_temp);
        php_press=eval(php_press);
        php_lux=eval(php_lux);
        php_hum=eval(php_hum);
        var data = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['Temperature', php_temp],
          ['Humidity', php_hum],
          ['Light', php_lux],
          ['Pressure', php_press]
        ]);

        var options = {
          width: 800, height: 200,
          redFrom: 35, redTo: 100,
          yellowFrom:20, yellowTo: 35,
          greenFrom:0, greenTo:20,
          minorTicks: 5
        };

        var chart = new google.visualization.Gauge(document.getElementById('chart_div'));

        chart.draw(data, options);

        setInterval(function() {
          //data.setValue(0, 1, 40 + Math.round(60 * Math.random()));
          chart.draw(data, options);
        }, 13000);
        setInterval(function() {
          //data.setValue(1, 1, 40 + Math.round(60 * Math.random()));
          chart.draw(data, options);
        }, 5000);
        setInterval(function() {
          //data.setValue(2, 1, 60 + Math.round(20 * Math.random()));
          chart.draw(data, options);
        }, 26000);
      }
</script>
</html> 