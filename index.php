<?php include "conf.php"; /* load a local configuration */ ?>
<?php include "modulekit/loader.php"; /* loads all php-includes */ ?>
<?php call_hooks("init"); /* initialize submodules */ ?>
<?php // App functionality
$form_def = array(
  "something" => array(
    "type" => "text",
    "name" => "Something"
  ),
);
$form_data = new form("data", $form_def);

if($form_data->is_complete()) {
  $data = $form_data->save_data();

  // save to database/file/whatever
  print "<pre>\n";
  print_r($data);
  print "</pre>\n";
}

if($form_data->is_empty()) {
  // load data
  $data = array("something" => "interesting");

  $form_data->set_data($data);
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
    <form enctype='multipart/form-data' method='post'>
    <?php print $body ?>
    <input type='submit' value='Submit'/>
    </form>
  </body>
</html>
