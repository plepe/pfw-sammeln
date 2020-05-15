<?php include "conf.php"; /* load a local configuration */ ?>
<?php include "modulekit/loader.php"; /* loads all php-includes */ ?>
<?php call_hooks("init"); /* initialize submodules */ ?>
<?php // App functionality
$db = new PDOext($db_conf);
if (!$db->tableExists('unterschriften_listen')) {
  $db->query(<<<EOT
create table unterschriften_listen (
  sammlerin     tinytext        not null,
  datum         date            not null,
  anmerkungen   mediumtext      null,
  plz1010       int4            not null default 0,
  plz1020       int4            not null default 0,
  plz1030       int4            not null default 0,
  plz1040       int4            not null default 0,
  plz1050       int4            not null default 0,
  plz1060       int4            not null default 0,
  plz1070       int4            not null default 0,
  plz1080       int4            not null default 0,
  plz1090       int4            not null default 0,
  plz1100       int4            not null default 0,
  plz1110       int4            not null default 0,
  plz1120       int4            not null default 0,
  plz1130       int4            not null default 0,
  plz1140       int4            not null default 0,
  plz1150       int4            not null default 0,
  plz1160       int4            not null default 0,
  plz1170       int4            not null default 0,
  plz1180       int4            not null default 0,
  plz1190       int4            not null default 0,
  plz1200       int4            not null default 0,
  plz1210       int4            not null default 0,
  plz1220       int4            not null default 0,
  plz1230       int4            not null default 0
);
EOT
);
}

$form_def = array(
  "sammlerin" => array(
    "type" => "text",
    "name" => "Sammler*in",
    "req" => true,
  ),
  "datum" => array(
    "type" => "date",
    "name" => "Sammeldatum",
    "default" => Date('Y-m-d'),
    "req" => true,
  ),
  "anmerkungen" => array(
    "type" => "textarea",
    "name" => "Anmerkungen",
  ),
);

for ($i = 1; $i <= 23; $i++) {
  $form_def[sprintf("plz1%02d0", $i)] = array(
    "type" => "integer",
    "name" => "Unterschriften {$i}. Bezirk",
  );
}
$form_data = new form("data", $form_def);

if($form_data->is_complete()) {
  $data = $form_data->save_data();

  foreach ($data as $k => $v) {
    if ($v) {
      $set[] = $db->quoteIdent($k) . '=' . $db->quote($v);
    }
  }

  $set = implode(', ', $set);
  $result = $db->query("insert into unterschriften_listen set {$set}");
  if (!$result) {
    $error = "Fehler beim Eintragen in die Datenbank: " . $db->errorInfo()[2];
  }
}

if($form_data->is_empty()) {
  // load data
  $data = array("something" => "interesting");

  $form_data->set_data($data);
}

$body = $form_data->show();
?>
<!DOCTYPE HTML>
<html>
  <head>
    <title>Platz für Wien - Unterschriften sammeln</title>
    <?php print modulekit_to_javascript(); /* pass modulekit configuration to JavaScript */ ?>
    <?php print modulekit_include_js(); /* prints all js-includes */ ?>
    <?php print modulekit_include_css(); /* prints all css-includes */ ?>
    <?php print_add_html_headers(); /* print additional html headers */ ?>
  </head>
  <body>
<?php
if (isset($error)) {
  print $error;
}

if (!$form_data->is_complete()) {
?>
    <form enctype='multipart/form-data' method='post'>
    <?php print $body ?>
    <input type='submit' value='Submit'/>
    </form>
<?php
}
?>
  </body>
</html>
