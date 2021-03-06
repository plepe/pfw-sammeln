<?php include "conf.php"; /* load a local configuration */ ?>
<?php include "modulekit/loader.php"; /* loads all php-includes */ ?>
<?php session_start(); ?>
<?php call_hooks("init"); /* initialize submodules */ ?>
<?php // App functionality
db_init();
$id_gen = rand_init();
$form_data = form_init();

if($form_data->is_complete()) {
  $id = form_save($form_data);

  if ($id) {
    messages_add("Danke für das Eintragen der Unterschriften. Bitte schreibe auf die Liste(n) das Kürzel \"" . htmlspecialchars($id) . "\", damit später nachvollziehbar ist, ob alle Unterschriftenlisten den Weg ins Büro geschafft haben.");
    page_reload();
  }
}

$body =
  "Neue Unterschriftenliste eintragen:<br/>" .
  $form_data->show();

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
<form enctype='multipart/form-data' method='post'>
<?php print $body ?>
<input id='submit' type='submit' value='Eintragen'/>
</form>
<?php
?>
<script>
let submit_button = document.getElementById('submit')
if (submit_button) {
  submit_button.onclick = () => {
    setTimeout(() => submit_button.disabled = true, 0)
  }
}
</script>
<?php
print $template[2];
