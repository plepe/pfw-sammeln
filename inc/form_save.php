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
    messages_debug("Fehler beim Eintragen in die Datenbank: " . $db->errorInfo()[2]);
  }

  return $id;
}
