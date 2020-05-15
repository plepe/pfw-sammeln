<?php
function rand_init () {
  $id_gen = new RandomIdGenerator(array(
    'chars' => 'ABCDEFGHJKLMNPQRTUVWXYZ12346789',
    'length' => 4,
  ));
  $id_gen->setCheckFun(function ($id) {
    global $db;

    $res = $db->query("select count(*) as c from unterschriften_listen where id=" . $db->quote($id));
    $elem = $res->fetch();
    $res->closeCursor();

    return !!$elem['c'];
  });

  return $id_gen;
}
