<?php
$API_URL_REQUEST =  "https://api.epackenvios.com/v1/Quote";
$API_URL_CODEZIP = "https://api.epackenvios.com/v1/postalcodes/45190";

function obtener_datos($url){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

$responseCode = json_decode(obtener_datos($API_URL_CODEZIP), true);
echo "<pre>";
print_r($responseCode['data']['colonias'][0]['cp']);
echo "</pre>";

        function requets_send($url, $data_request_body){
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Access-Control-Allow-Origin: *",
                "Content-Type: application/json;charset=utf-8",
                'x-api-key: a43832802fd546a2c165fc34ac4ea8f41fb5cada9198d48ee1f2e43d0c03ff2c',
            ));
            $json_data = json_encode($data_request_body);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
        }
        $data_request = [
            "ltl"              => "0",
            "shipperZip"       => "45188",
            "recipientZip"     => "44100",
            "weight"           => "5",
            "large"            => "15",
            "width"            => "15",
            "height"           => "15",
            "secure"           => 1,
            "secureValue"      => "$ 10000",
            "pickup"           => "0",
            "international"    => 0,
            "content"          => "cotizacion",
            "shipperCountry"   => "MX",
            "shipperCity"      => "Guadalajara",
            "shipperState"     => "Jalisco",
            "recipientCountry" => "MX",
            "recipientCity"    => "Guadalajara",
            "recipientState"   => "Jalisco",
            "isDocument"       => 0,
            
        ];
        $responseRequest = json_decode(requets_send($API_URL_REQUEST, $data_request), true); 

        
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./public/css/style.css">
    <title>API</title>
</head>
<body>

    <section class="container">
        <h1 class="h1">Cotizar envío</h1>
        <div class="cointainer__cotizar">
            <div class="container__input">
                <label for="number">Origen</label><br>
                <input type="number" name="number" id="origen" title="origen" placeholder="Codigo Postal">
            </div><br>
    
            <div class="container__input">
                <label for="number">Destino</label><br>
                <input type="number" name="number" id="destino" title="destino" placeholder="Codigo Postal">
            </div><br>
    
            <div class="container__input container__input--tipo">
                <label for="tipoEnvio">Tipo de envio</label><br>
                <select title="tipoEnvio">
                    <option value="Caja">Caja</option>
                    <option value="Sobre">Sobre</option>
                  </select>
                  
            </div><br>
            
            <div class="container__input">
                <label for="peso">Peso(kg)</label><br>
                <input type="number" name="peso" id="peso" placeholder="kg">
            </div><br>
            
            <div class="container__input container__input--tamanos">
                <label for="tamano">Tamaño de caja(cm)</label><br>
                <div class="input_tamano">
                <input type="number" name="tamanoAlto" id="tamanoAlto" title="tamano" placeholder="cm">
                <input type="number" name="tamanoLargo" id="tamanoLargo" title="tamano" placeholder="cm">
                <input type="number" name="tamanoAncho" id="tamanoAncho" title="tamano" placeholder="cm">
                </div>
            </div><br>
            <div class="container__input container__input--btn">
                <button class="btn__cotizar" id="btnCotizar">Cotizar</button>
            </div>
        </div>
    </section>

        
        <?php 
        foreach($responseRequest['data'] as $dato){?>
    <section id="container__contentDinamic">
            <div class="contentDinamic__container">
                <p><?=$dato['Service'];?></p>
                <div class="container__itemsDinamic">
                    <p><?=$dato['Service'];?></p>
                    <p><b><?=$dato['Currier'];?></b></p>
                    <?php if(!isset($dato['EstimatedDate'])){
                        $dato['EstimatedDate'] = "";
                    }?>
                    <p><?=$dato['EstimatedDate']?></p>
                </div>
                <p><?=$dato['CurrierWeight'];?></p>
                <p><b><?=$dato['Price'];?></b></p>
                <button class="btn__start">¡Comienza ahora!</button>
            </div>
    </section>  
    <?}?>

</body>
</html>