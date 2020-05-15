<?php include "conf.php"; /* load a local configuration */ ?>
<?php include "modulekit/loader.php"; /* loads all php-includes */ ?>
<?php call_hooks("init"); /* initialize submodules */ ?>
<?php // App functionality
db_init();
$id_gen = rand_init();
$form_data = form_init();

if($form_data->is_complete()) {
  $id = form_save($form_data);

  if ($id) {
    messages_add("Danke für das Eintragen der Unterschriften. Bitte schreibe auf die Liste(n) das Kürzel \"" . htmlspecialchars($id) . "\", damit später nachvollziehbar ist, ob alle Unterschriftenlisten den Weg ins Büro geschafft haben.");
  }
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
  print "<p><a href='.'>Weitere Unterschriftenliste eintragen</a></p>";
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
