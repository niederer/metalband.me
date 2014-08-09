<!DOCTYPE html>
<?php

  setlocale(LC_ALL, 'en_US.UTF8');
  function toSlug($str, $replace=array(), $delimiter='-') {
    if( !empty($replace) ) {
      $str = str_replace((array)$replace, ' ', $str);
    }

    $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
    $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
    $clean = strtolower(trim($clean, '-'));
    $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

    return $clean;
  }

  //gonna be ugly for the first cut, don't judge
  require_once('../../metal_db_credentials.php');
  $mysqli = new mysqli(METAL_DB_HOST, METAL_DB_USER, METAL_DB_PASSWORD, METAL_DB_DATABASE);
  if (!$mysqli->connect_errno) {
    $adjective_result = $mysqli->query("SELECT word FROM words WHERE is_adjective = 1 ORDER BY rand() LIMIT 1");
    $adjective_row = $adjective_result->fetch_assoc();
    $adjective = $adjective_row['word'];

    $noun_result = $mysqli->query("SELECT word FROM words WHERE is_noun = 1 ORDER BY rand() LIMIT 1");
    $noun_row = $noun_result->fetch_assoc();
    $noun = $noun_row['word'];

    $band_name = implode(' ', array($adjective, $noun));
    $slug = toSlug($band_name);
    $mysqli->query("INSERT INTO bands (slug, name, view_count) VALUES ('{$mysqli->real_escape_string($slug)}', '{$mysqli->real_escape_string($band_name)}', 1) ON DUPLICATE KEY UPDATE view_count=view_count+1");
  } else {
    define('CSV_WORD', 0);
    define('CSV_IS_NOUN', 1);
    define('CSV_IS_ADJECTIVE', 2);

    $nouns = array();
    $adjectives = array();
    ini_set("auto_detect_line_endings", true);

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
    $band_name = $adjectives[rand(0, count($adjectives) - 1)] . ' ' . $nouns[rand(0, count($nouns) - 1)];
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

    <h1>
      Your metal band name is...
    </h1>

    <h2>
      <?= $band_name ?>
    </h2>

    <form action="/">
      <button type="submit">
        <i class="fa fa-bolt"></i>
        <span>
          Metal Band Me!
        </span>
      </button>
    </form>

    <a href="https://twitter.com/share" class="twitter-share-button" data-lang="en" data-count="none" data-text="My metal band name is <?= $band_name ?>! via @metalbandme">Tweet</a>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-47663705-2', 'auto');
      ga('send', 'pageview');

    </script>

    <script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="/js/index.js"></script>
  </body>
</html>
