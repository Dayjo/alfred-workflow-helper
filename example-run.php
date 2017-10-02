<?php
spl_autoload_register(function ($class_name) {
    $class_name = str_replace("\\", DIRECTORY_SEPARATOR, $class_name);
    include  $class_name . '.php';
});
//
// spl_autoload_extensions(".php"); // comma-separated list
//     spl_autoload_register();

use Alfred\Workflow as Workflow;
use Alfred\Command as Command;
use Alfred\ItemList as ItemList;
use Alfred\Item as Item;

$Workflow = new Workflow();

$Workflow->addCommand(new Command(
  [
    'prefix' => 'start', // default (no extra command, i.e. "keyword myTask"
    'command' => function ($input) {
        echo "STARTING $input";
    }
  ]
));

$Workflow->run();
