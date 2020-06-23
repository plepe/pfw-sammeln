<?php
function form_save ($form_data) {
  global $db;
  global $id_gen;

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
    messages_add("Fehler beim Eintragen in die Datenbank: " . $db->errorInfo()[2], MSG_ERROR);
    return;
  }

  $result = $db->query('select * from unterschriften_listen where id=' . $db->quote($id));
  if ($result->rowCount() !== 1) {
    messages_add("Fehler beim Eintragen in die Datenbank: Kann den Eintrag nicht finden!", MSG_ERROR);
    return;
  }

  file_put_contents("data/{$id}.json", json_encode($data));

  return $id;
}
