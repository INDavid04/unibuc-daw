<?php
$url = "https://www.imdb.com/chart/top/";

$ch = curl_init($url);

// Opțiuni cURL
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,       // returnează conținutul, nu îl afișează
    CURLOPT_FOLLOWLOCATION => true,       // urmează redirecționările
    CURLOPT_SSL_VERIFYPEER => false,      // (opțional) dezactivează verificarea SSL
    CURLOPT_CONNECTTIMEOUT => 10,         // timeout conexiune
    CURLOPT_TIMEOUT => 20,                // timeout total

    // Simulăm un browser real (ex: Chrome pe Windows)
    CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) '
                       . 'AppleWebKit/537.36 (KHTML, like Gecko) '
                       . 'Chrome/118.0.5993.90 Safari/537.36',

    // Antete suplimentare – opțional, dar pot ajuta
    CURLOPT_HTTPHEADER => [
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
        'Accept-Language: en-EN,en;q=0.9,en-US;q=0.8,en;q=0.7',
        'Connection: keep-alive',
        'Upgrade-Insecure-Requests: 1',
        'Cache-Control: max-age=0',
    ],
]);

$continut = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Eroare cURL: ' . curl_error($ch);
} else {
    
    $lista_filme=explode('<li class="ipc-metadata-list-summary-item">',$continut);
    for($i=1; $i<count($lista_filme);$i++)
    {


  $titlu=explode('<h3 class="ipc-title__text ipc-title__text--reduced">',$lista_filme[$i]);
  $titlu=explode("</h3>",$titlu[1]);
  $titlu=explode(". ",$titlu[0]);
  print_r($i.' '.$titlu[1].'<br/>');

  $info=explode('cli-title-metadata-item">',$lista_filme[$i]);
  $ani=explode("</span>",$info[1]);
  print_r( $ani[0].'<br/>');
  $durate=explode("</span>",$info[2]);
  $durata=explode("</span>",$durate[0]);
  print_r( $durata[0].'<br/>');

   $scor=explode('<span class="ipc-rating-star--rating">',$lista_filme[$i]);
  $scor=explode("</span>",$scor[1]);
  print_r( $scor[0].'<br/>');

    }

}

curl_close($ch);
?>
