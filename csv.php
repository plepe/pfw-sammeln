<?php include "conf.php"; /* load a local configuration */ ?>
<?php include "modulekit/loader.php"; /* loads all php-includes */ ?>
<?php session_start(); ?>
<?php call_hooks("init"); /* initialize submodules */ ?>
<?php // App functionality
db_init();

$req = $db->query("select id, sammlerin, datum, eintrag_datum, (plz1010 + plz1020 + plz1030 + plz1040 + plz1050 + plz1060 + plz1070 + plz1080 + plz1090 + plz1100 + plz1110 + plz1120 + plz1130 + plz1140 + plz1150 + plz1160 + plz1170 + plz1180 + plz1190 + plz1200 + plz1210 + plz1220 + plz1230) as sum, plz1010, plz1020, plz1030, plz1040, plz1050, plz1060, plz1070, plz1080, plz1090, plz1100, plz1110, plz1120, plz1130, plz1140, plz1150, plz1160, plz1170, plz1180, plz1190, plz1200, plz1210, plz1220, plz1230, anmerkungen from unterschriften_listen order by eintrag_datum asc");

if (!$req) {
  print("Fehler beim Eintragen in die Datenbank: " . $db->errorInfo()[2]);
  exit(0);
}

$result = $req->fetchAll();

Header('Content-Type: text/csv; charset=utf-8');
Header('Content-Disposition: attachment; filename="platzfuerwien-listen.csv"');

print chr(239) . chr(187) . chr(191); // BOM

$fd = fopen('php://output', 'w');
fputcsv($fd, array_keys($result[0]));

foreach ($result as $entry) {
  fputcsv($fd, $entry);
}
