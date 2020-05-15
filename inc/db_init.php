<?php
function db_init () {
  global $db_conf;
  global $db;

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
}
