<?php
function form_init () {
  $form_def = array(
    "sammlerin" => array(
      "type" => "text",
      "name" => "Sammler*in(nen)",
      "req" => true,
    ),
    "datum" => array(
      "type" => "date",
      "name" => "Sammeldatum",
      "default" => Date('Y-m-d'),
      "req" => true,
    ),
  );

  for ($i = 1; $i <= 23; $i++) {
    $form_def[sprintf("plz1%02d0", $i)] = array(
      "type" => "integer",
      "name" => "Unterschriften {$i}. Bezirk",
    );
  }

  $form_def["anmerkungen"] = array(
    "type" => "textarea",
    "name" => "Anmerkungen",
  );

  return new form("data", $form_def);
}
