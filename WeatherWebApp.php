<!DOCTYPE html>
<?php
			$ApiUrl = "https://api.weatherlink.com/v1/NoaaExt.json?user=USERNAME&pass=PASSWORDMg&apiToken=E33543E468A04D80B93A78082E33D173";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, $ApiUrl);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_VERBOSE, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$response = curl_exec($ch);
			curl_close($ch);
			$data = json_decode($response);
			$currentTime = time();

      $apiKey = "e8c4641c4164b99ababa5ae02c7d462a";
      $cityId = "733905"; //Βέροια
      $ApiUrlforecast = "http://api.openweathermap.org/data/2.5/forecast?id=" . $cityId . "&lang=el&units=metric&APPID=" . $apiKey;
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_URL, $ApiUrlforecast);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
      curl_setopt($ch, CURLOPT_VERBOSE, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      $response = curl_exec($ch);
      curl_close($ch);
      $dataf = json_decode($response);

			//Fahrenheit to celsius
			function fahrenheit_to_celsius($given_value)
			{
				$celsius=5/9*($given_value-32);
				return $celsius ;
			}
      // Knotes  to kilometers per hour
      function kt_to_kmph ($given_value)
      {
        $kmph = 1.8519984 * $given_value;
        return $kmph ;
      }

      //Inches to mm
      function in_to_mm ($given_value)
      {
        $mm = 25.4 * $given_value;
        return $mm ;
      }

      //AM PM to 24h
      function ampm_to_24h($s)
      {
        $tarr = explode(':', $s);
        if(strpos( $s, 'AM') === false && $tarr[0] !== '12'){
            $tarr[0] = $tarr[0] + 12;
        }elseif(strpos( $s, 'PM') === false && $tarr[0] == '12'){
            $tarr[0] = '00';
        }
        $mynewdate=preg_replace("/[^0-9 :]/", '', implode(':', $tarr));
        return$mynewdate;
      }      
		?>

<html>
  <title>Veria Central Public Library Weather APP</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Roboto'>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="css/weather-icons.css">
   <style>
    html,body,h1,h2,h3,h4,h5,h6 {font-family: "Roboto", sans-serif}
  </style>

  <body class="w3-light-grey">
<!-- Page Container -->
  <div class="w3-content " style="max-width:1310px;">
<!-- Πλέγμα -->
  <div class="w3-row-padding">
<!-- Αριστερή στήλη -->
    <div class="w3-third">
      <div class="w3-white w3-text-grey w3-card-4 ">
        <div class="w3-display-container">
<!-- Προβολή εικόνας Κάμερας -->
            <a target="_blank" href="http://penteli.meteo.gr/stations/veroiatown/webcam.jpg"><img src="http://penteli.meteo.gr/stations/veroiatown/webcam.jpg" class="w3-border w3-padding" width="100%" height="100%" alt="WebCam"></a>
            
        <div class="w3-display-bottomleft w3-container w3-text-black">
      </div>
    </div>
<!-- Τελευταία ενημέρωση -->
    <div class="w3-container w3-center">  
      <p style="font-size:20px"><?php echo $data->observation_time; ?></p>
      <hr> 
    </div> 
<!-- θερμοκρασία -->
    <div class="w3-container w3-center">  
      <p style="font-size:20px">Θερμοκρασία : <i class="wi wi-thermometer"></i> <?php echo $data->temp_c; ?> <i class="wi wi-celsius"></i> /  
      <i class="wi wi-thermometer"></i> <?php echo $data->temp_f; ?> <i class="wi wi-fahrenheit"></i><br>
       
      Αίσθηση : <i class="wi wi-thermometer"></i> <?php echo $data->heat_index_c; ?> <i class="wi wi-celsius"></i> /  <i class="wi wi-thermometer"></i> <?php echo $data->heat_index_f; ?> <i class="wi wi-fahrenheit"></i><br>
       
       Ελάχιστη :<i class="wi wi-thermometer"></i> <?php echo round(fahrenheit_to_celsius($data->davis_current_observation->temp_day_low_f),1); ?>
       <i class="wi wi-celsius"></i> στις <?php echo ampm_to_24h($data->davis_current_observation->temp_day_low_time); ?> <br>
       
       Μέγιστη : <i class="wi wi-thermometer"></i> <?php echo round(fahrenheit_to_celsius($data->davis_current_observation->temp_day_high_f),1); ?> 
       <i class="wi wi-celsius"></i> στις <?php echo ampm_to_24h($data->davis_current_observation->temp_day_high_time); ?><br></p>
      <hr> 
    </div>
<!-- Υγρασία -->
    <div class="w3-container w3-center">  
      <p style="font-size:20px">Σχετική Υγρασία :  <?php echo $data->davis_current_observation->relative_humidity_in; ?> <i class="wi wi-humidity"></i> <br>

      Ελάχιστη :  <?php echo $data->davis_current_observation->relative_humidity_day_low; ?> <i class="wi wi-humidity"></i> 
      στις <?php echo ampm_to_24h($data->davis_current_observation->relative_humidity_day_low_time); ?> <br>

      Μέγιστη : <?php echo $data->davis_current_observation->relative_humidity_day_high; ?> <i class="wi wi-humidity"></i>
      στις  <?php echo ampm_to_24h($data->davis_current_observation->relative_humidity_day_high_time); ?><br></p>
      <hr> 
    </div>

<!-- Βαρομετρική Πίεση -->
    <div class="w3-container w3-center">  
      <p style="font-size:20px">Βαρομετρική Πίεση : <i class="wi wi-barometer"></i> <?php echo $data->pressure_mb; ?>  hPa<br>
      
      Ελάχιστη : <?php echo $data->davis_current_observation->pressure_day_low_in; ?> hPa<i class="wi wi-barometer"></i>
      στις <?php echo ampm_to_24h($data->davis_current_observation->pressure_day_low_time); ?> <br>
      
      Μέγιστη : <?php echo $data->davis_current_observation->pressure_day_high_in; ?> hPa 
      <i class="wi wi-barometer"></i> 
      στις <?php echo ampm_to_24h($data->davis_current_observation->pressure_day_high_time); ?><br></p>
      <hr> 
    </div>
<!-- Άνεμος -->
    <div class="w3-container w3-center">  
      <p style="font-size:20px">Ένταση Ανέμου : <i class="wi wi-strong-wind"></i> <?php echo round(kt_to_kmph($data->wind_kt),1); ?>  km/h<br>
      Διεύθυνση Ανέμου : <i class="wi wi-strong-directions"></i> <?php echo $data->wind_degrees; ?> <i class="wi wi-degrees"></i></p>
      <hr> 
    </div>
<!-- Υετός -->
    <div class="w3-container w3-center">  
      <p style="font-size:20px"> Υετός <i class="wi wi-raindrop"></i> <br>
      
      Ραγδαιότητα <?php echo round(in_to_mm($data->davis_current_observation->rain_rate_in_per_hr),2); ?>  mm/h<br>
    
      Ημερήσιος Υετός : <i class="wi wi-strong-directions"></i> <?php echo round(in_to_mm($data->davis_current_observation->rain_day_in),2); ?> mm </i><br>
    
      Μηνιαίος Υετός : <i class="wi wi-strong-directions"></i> <?php echo round(in_to_mm($data->davis_current_observation->rain_month_in),2); ?> mm </i><br>

      Ετήσιος Υετός : <i class="wi wi-strong-directions"></i> <?php echo round(in_to_mm($data->davis_current_observation->rain_year_in),2); ?> mm </i>
    </p>
      <hr> 
    </div>

  </div>
  <br>

<!-- Τέλος Αριστερής στήλης -->
</div>
  
<!-- Δεξιά στήλη -->
  <div class="w3-twothird">   
   
     
        <div class="w3-white w3-text-grey w3-card-4 w3-responsive w3-centered ">   

        
            <table class="w3-table w3-centered w3-striped w3-responsive">
                
            <?php
                $counter = 0;
                for ($y = 0; $y <= 39; $y++) { 
                    ?><tr><?
                   // for ($x = 0; $x < 1; $x++) { ?>
                        <td><br>
                                <b><?php echo date('d/m/Y', $dataf->list[$counter]->dt);?><br>
                                <?php echo date('H:i', $dataf->list[$counter]->dt);?></b> <br>
                                <u><?php echo $dataf->list[$counter]->weather[0]->description;?></u>
                   </td>
                   <td>     
                                <img width="70px" src="http://openweathermap.org/img/w/<?php echo $dataf->list[$counter]->weather[0]->icon; ?>.png" class="weather-icon" /><br>
                                <strong><?php echo $data->temp_c; ?> <i class="wi wi-celsius"></i></strong>
                        </td>
                        <td><br>
                                <b>Αίσθηση : </b> <? echo round($dataf->list[$counter]->main->feels_like,1);?> <i class="wi wi-degrees"></i>C<br><?php
                                
                                ?><b>Υγρασία : </b><?echo $dataf->list[$counter]->main->humidity;?> %<br>   
                        </td>        
                        <td><br>
                                <b>Ταχ. Ανέμου: </b><?echo $dataf->list[$counter]->wind->speed;?> m/s<br><?php  
                                ?><b>Κατεύθ. Ανέμου : </b><?echo $dataf->list[$counter]->wind->deg;?> <i class="wi wi-degrees"></i><br><?php  
                                ?><b>Ριπή Ανέμου : </b><?echo $dataf->list[$counter]->wind->gust;?> m/s<br>  
                                </td>
                                <td style="text-align:center"><br>
                                <b>Βαρομετρική Πίεση : </b><? echo $dataf->list[$counter]->main->pressure;?> hPa<br><?php  
                                ?><b>Ορατότητα : </b><?echo $dataf->list[$counter]->visibility/1000;?> km<br>
                        </td>
                                <?php $counter++; ?>
                       
                        </td><?
                   // }
                    
            ?> 
                    </tr> 
                <?php
                }
                ?>
            </table>
              </div>
        
   
<!-- Τέλος Δεξιάς στήλης -->
    </div>

<!-- Τέλος Πλέγματος -->
  </div>
  
<!-- Τέλος περιεχομένου σελίδας -->
</div>

<footer class="w3-container w3-teal w3-center w3-margin-top">
  <p>Βρείτε μας στα social media.</p>
  <a target="_blank" href="https://www.facebook.com/libveria/"><i class="fa fa-facebook-official w3-hover-opacity"></i></a> 
  <a target="_blank" href="https://www.instagram.com/verialibrary/"><i class="fa fa-instagram w3-hover-opacity"></i></a> 
  <a target="_blank" href="https://twitter.com/@verlib"><i class="fa fa-twitter w3-hover-opacity"></i></a> 

  <p>Powered by <a href="https://www.w3schools.com/w3css/default.asp" target="_blank">w3.css</a></p>
</footer>
</body>
</html>
