<?php include "conf.php"; /* load a local configuration */ ?>
<?php include "modulekit/loader.php"; /* loads all php-includes */ ?>
<?php call_hooks("init"); /* initialize submodules */ ?>
<?php // App functionality
if (!isset($db_conf)) {
  print "\$db_conf has not been defined.";
  exit(1);
}

try {
  $db = new PDOext($db_conf);
}
catch (Exception $e) {
  print "Can't connect to database: " . $e->getMessage();
  exit(1);
}

if (!$db->tableExists('unterschriften_listen')) {
  $db->query(<<<EOT
create table unterschriften_listen (
  id            char(4)         not null primary key,
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

$id_gen = new RandomIdGenerator(array(
  'chars' => 'ABCDEFGHJKLMNPQRTUVWXYZ0123456789',
  'length' => 4,
));
$id_gen->setCheckFun(function ($id) {
  global $db;

  $res = $db->query("select count(*) as c from unterschriften_listen where id=" . $db->quote($id));
  $elem = $res->fetch();
  $res->closeCursor();

  return !!$elem['c'];
});

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

  $id = $id_gen->get();
  $set[] = "id=" . $db->quote($id);
  $set = implode(', ', $set);
  $result = $db->query("insert into unterschriften_listen set {$set}");
  if (!$result) {
    messages_debug("Fehler beim Eintragen in die Datenbank: " . $db->errorInfo()[2]);
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
print messages_print();

if ($id) {
  print "<p>Danke für das Eintragen der Unterschriften. Bitte schreibe auf die Liste(n) das Kürzel \"" . htmlspecialchars($id) . "\", damit später nachvollziehbar ist, ob alle Unterschriftenlisten den Weg ins Büro geschafft haben.</p>";
  print "<a href='.'>Weitere Unterschriftenliste eintragen</a>";
}
else {
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
