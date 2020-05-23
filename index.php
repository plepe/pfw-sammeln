<?php include "conf.php"; /* load a local configuration */ ?>
<?php include "modulekit/loader.php"; /* loads all php-includes */ ?>
<?php session_start(); ?>
<?php call_hooks("init"); /* initialize submodules */ ?>
<?php // App functionality
db_init();

$body = '<ul>' .
  '<li><a href="enter.php">Neue Unterschriftenliste eintragen</a></li>' .
  '<li><a href="csv.php">Alles als CSV Datei exportieren</a></li>' .
  '</ul>';

$req = $db->query("select *, (plz1010 + plz1020 + plz1030 + plz1040 + plz1050 + plz1060 + plz1070 + plz1080 + plz1090 + plz1100 + plz1110 + plz1120 + plz1130 + plz1140 + plz1150 + plz1160 + plz1170 + plz1180 + plz1190 + plz1200 + plz1210 + plz1220 + plz1230) as sum from unterschriften_listen order by eintrag_datum desc limit 20");
if (!$req) {
  messages_debug("Fehler beim Eintragen in die Datenbank: " . $db->errorInfo()[2]);
} else {
  $result = $req->fetchAll();

  $table = new table(array(
    'id' => array(
      'name' => 'Kürzel',
      'link' => 'entry.php?id={{ id }}',
    ),
    'sammlerin' => array(
      'name' => 'Sammler*in',
    ),
    'datum' => array(
      'name' => 'Datum',
      'format' => '{{ datum|date("D, j.n.Y") }}',
    ),
    'sum' => array(
      'name' => 'Gesamtanzahl',
      'html_attributes' => 'style="text-align: right;"',
    ),
  ), $result, array(
    'template_engine' => 'twig',
  ));

  $body .= "Neueste Eintragungen:";
  $body .= $table->show();
}

$count_homepage = '';
if ($db->tableExists('wp_participants_database')) {
  $count_homepage = $db->query("select * from wp_participants_database where last_accessed>='2020-04-15'")->rowCount();
}

$count_listen =
  $db->query("select sum(plz1010 + plz1020 + plz1030 + plz1040 + plz1050 + plz1060 + plz1070 + plz1080 + plz1090 + plz1100 + plz1110 + plz1120 + plz1130 + plz1140 + plz1150 + plz1160 + plz1170 + plz1180 + plz1190 + plz1200 + plz1210 + plz1220 + plz1230) as sum from unterschriften_listen")->fetchAll()[0]['sum'];
$body .= "<div>Aktueller Stand:<br/>Via Homepage: {$count_homepage}, via Unterschriftenlisten: {$count_listen}, Summe: ". ((int)$count_homepage + (int)$count_listen) . "</div>";

$template = explode('@@', file_get_contents('template.html'));

print $template[0];
?>
    <?php print modulekit_to_javascript(); /* pass modulekit configuration to JavaScript */ ?>
    <?php print modulekit_include_js(); /* prints all js-includes */ ?>
    <?php print modulekit_include_css(); /* prints all css-includes */ ?>
    <?php print_add_html_headers(); /* print additional html headers */ ?>
<?php
print $template[1];
print messages_print();

?>
<?php print $body ?>
<?php
print $template[2];
