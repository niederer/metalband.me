<!DOCTYPE html>
<?php

  //gonna be ugly for the first cut, don't judge
  define('CSV_WORD', 0);
  define('CSV_IS_NOUN', 1);
  define('CSV_IS_ADJECTIVE', 2);
 
  $nouns = array();
  $adjectives = array();


  if (($handle = fopen("../words.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
      if ($data[CSV_IS_NOUN] == 'is_noun') {
        continue;
      }

      if ($data[CSV_IS_NOUN] == 'TRUE') {
        $nouns[] = $data[CSV_WORD];
      }

      if ($data[CSV_IS_ADJECTIVE] == 'TRUE') {
        $adjectives[] = $data[CSV_WORD];
      }
    }
    fclose($handle);
  }

?>
<html lang="en">
  <head>
    <title>Metal band name generator ϟ metalband.me</title>

    <meta charset="UTF-8">
    <meta name="description" content="Use this heavy metal band name generator to unearth a band name as hardcore as you are.">

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">

    <link href='http://fonts.googleapis.com/css?family=Vollkorn:400,700' rel='stylesheet' type='text/css'>
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
  </head>
  <body>

    <h2>
      Your metal band name is...
    </h2>

    <h3>
      <?= $adjectives[rand(0, count($adjectives) - 1)] ?> <?= $nouns[rand(0, count($nouns) - 1)] ?>
    </h3>    

    <form action="/">
      <button type="submit">
        <i class="fa fa-bolt"></i>
        <span>
          Metal Band Me!
        </span>
      </button>
    </form>

    <script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="/js/index.js"></script>
  </body>
</html>
