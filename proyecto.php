<?php

header("Content-type:text/html; charset='utf-8'");
$previsionTiempo = "";
$error = "";
if (array_key_exists('ciudad', $_GET)) {

    $ciudad = urlencode($_GET['ciudad']);
    $contenido = file_get_contents("http://api.openweathermap.org/data/2.5/weather?q=" . $ciudad . "&appid=APIKEY&lang=es");

    $array = json_decode($contenido, true);
    $nombre = $array['name'];
    //Calculo de la temperatura
    $tempKel = $array['main']['temp'];
    $tempCel = $tempKel - 273.15;
    $descripcion = $array['weather'][0]['description'];
    $longitud = $array['coord']['lon'];
    $latitud = $array['coord']['lat'];
    $previsionTiempo = "El tiempo en " . $nombre . " es actualmente: " . $descripcion . " y la temperatura es de " . $tempCel . "° Celsius";
}


?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/css/bootstrap.min.css" integrity="sha384-VCmXjywReHh4PwowAiWNagnWcLhlEJLA5buUprzK8rxFgeH0kww/aWY76TfkUoSX" crossorigin="anonymous">
    <title>MAPS</title>

    <!-- /MAPS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.4.3/css/ol.css" type="text/css">
    <script src="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.4.3/build/ol.js"></script>

    <style type="text/css">
        body {
            margin: 0;
            padding: 0;
            background-image: url("img/nubes.jpg");
            background-attachment: fixed;
            background-repeat: no-repeat;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }

        #contenedor {
            width: 450px;
            margin-top: 200px;
            text-align: center;
        }

        h2 {
            font-size: 30px;
        }

        #encabezado {
            font-size: 13px;
        }

        #ciudad,
        #botonEnviar,
        #previsionTiempo {
            margin-top: 15px;
        }

        .map {
            height: 400px;
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="container content-center" id="contenedor">
        <h2>¿Qué tiempo hace?</h2>
        <p id="encabezado">Introduce el nombre de una ciudad</p>
        <form>
            <div class="row justify-content-center">
                <div class="col-sm-12">
                    <input type="text" class="form-control" placeholder="Ej. Londres, Tokio" name="ciudad" id="ciudad">
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-sm-3">
                    <button type="submit" class="btn btn-primary" id="botonEnviar">Enviar</button>
                </div>
            </div>
        </form>
        <div id="previsionTiempo">
            <?php
            if (strlen($previsionTiempo) > 1) //ya completó el PHP
            {
                if ($tempCel < -100) {
                    echo '<div class="alert alert-danger" role="alert">Ciudad no encontrada, intenta nuevamente</div>';
                } else {
                    echo '<div class="alert alert-info" role="alert">' . $previsionTiempo . '</div>';
                }
            }
            ?>
        </div>

        <!-- MAPA INCRUSTADO -->
        <div id="map" class="map"></div>
        <script type="text/javascript">
            function initMap() {
                // The location of the city
                var city = {
                    lat: <?php echo $latitud?>,
                    lng: <?php echo $longitud?>
                };
                // The map, centered at City
                var map = new google.maps.Map(
                    document.getElementById('map'), {
                        zoom: 12,
                        center: city
                    });
                // The marker, positioned at City
                var marker = new google.maps.Marker({
                    position: city,
                    map: map
                });
            }
        </script>
    </div>


    <!-- Google Maps API -->
    <script defer src="https://maps.googleapis.com/maps/api/js?key=APIKEY&callback=initMap"> </script>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/js/bootstrap.min.js" integrity="sha384-XEerZL0cuoUbHE4nZReLT7nx9gQrQreJekYhJD9WNWhH8nEW+0c5qq7aIo2Wl30J" crossorigin="anonymous"></script>
</body>

</html>
