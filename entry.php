<?php include "conf.php"; /* load a local configuration */ ?>
<?php include "modulekit/loader.php"; /* loads all php-includes */ ?>
<?php session_start(); ?>
<?php call_hooks("init"); /* initialize submodules */ ?>
<?php // App functionality
db_init();

$body = '<ul>' .
  '<li><a href=".">Zurück zur Übersicht</a></li>' .
  '<li><a href="csv.php?id=' . htmlentities($_REQUEST['id']) . '">Als CSV Datei exportieren</a></li>' .
  '</ul>';

$req = $db->query("select *, (plz1010 + plz1020 + plz1030 + plz1040 + plz1050 + plz1060 + plz1070 + plz1080 + plz1090 + plz1100 + plz1110 + plz1120 + plz1130 + plz1140 + plz1150 + plz1160 + plz1170 + plz1180 + plz1190 + plz1200 + plz1210 + plz1220 + plz1230) as sum from unterschriften_listen where id=" . $db->quote($_REQUEST['id']));
if (!$req) {
  messages_debug("Fehler beim Eintragen in die Datenbank: " . $db->errorInfo()[2]);
} else {
  $result = $req->fetchAll();

  $table = new table(array(
    'id' => array(
      'name' => 'Kürzel',
    ),
    'sammlerin' => array(
      'name' => 'Sammler*in',
    ),
    'datum' => array(
      'name' => 'Datum',
      'format' => '{{ datum|date("D, j.n.Y") }}',
    ),
    'eintrag_datum' => array(
      'name' => 'Zeitpunkt der Eintragung',
      'format' => '{{ eintrag_datum|date("D, j.n.Y G:i") }}',
    ),
    'plz1010' => array(
      'name' => 'Unterschriften 1. Bezirk',
    ),
    'plz1020' => array(
      'name' => 'Unterschriften 2. Bezirk',
    ),
    'plz1030' => array(
      'name' => 'Unterschriften 3. Bezirk',
    ),
    'plz1040' => array(
      'name' => 'Unterschriften 4. Bezirk',
    ),
    'plz1050' => array(
      'name' => 'Unterschriften 5. Bezirk',
    ),
    'plz1060' => array(
      'name' => 'Unterschriften 6. Bezirk',
    ),
    'plz1070' => array(
      'name' => 'Unterschriften 7. Bezirk',
    ),
    'plz1080' => array(
      'name' => 'Unterschriften 8. Bezirk',
    ),
    'plz1090' => array(
      'name' => 'Unterschriften 9. Bezirk',
    ),
    'plz1100' => array(
      'name' => 'Unterschriften 10. Bezirk',
    ),
    'plz1110' => array(
      'name' => 'Unterschriften 11. Bezirk',
    ),
    'plz1120' => array(
      'name' => 'Unterschriften 12. Bezirk',
    ),
    'plz1130' => array(
      'name' => 'Unterschriften 13. Bezirk',
    ),
    'plz1140' => array(
      'name' => 'Unterschriften 14. Bezirk',
    ),
    'plz1150' => array(
      'name' => 'Unterschriften 15. Bezirk',
    ),
    'plz1160' => array(
      'name' => 'Unterschriften 16. Bezirk',
    ),
    'plz1170' => array(
      'name' => 'Unterschriften 17. Bezirk',
    ),
    'plz1180' => array(
      'name' => 'Unterschriften 18. Bezirk',
    ),
    'plz1190' => array(
      'name' => 'Unterschriften 19. Bezirk',
    ),
    'plz1200' => array(
      'name' => 'Unterschriften 20. Bezirk',
    ),
    'plz1210' => array(
      'name' => 'Unterschriften 21. Bezirk',
    ),
    'plz1220' => array(
      'name' => 'Unterschriften 22. Bezirk',
    ),
    'plz1230' => array(
      'name' => 'Unterschriften 23. Bezirk',
    ),
    'sum' => array(
      'name' => 'Gesamtanzahl',
      'html_attributes' => 'style="text-align: right;"',
    ),
    'anmerkungen' => array(
      'name' => 'Anmerkungen',
      'format' => '{{ anmerkungen|nl2br }}'
    ),
  ), $result, array(
    'template_engine' => 'twig',
  ));

  $body .= $table->show('html-transposed');
}

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
