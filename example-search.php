<?php
spl_autoload_register(function ($class_name) {
    $class_name = str_replace("\\", DIRECTORY_SEPARATOR, $class_name);
    include  $class_name . '.php';
});

use Alfred\Workflow as Workflow;
use Alfred\Command as Command;
use Alfred\ItemList as ItemList;
use Alfred\Item as Item;

$Workflow = new Workflow();

$Workflow->addCommand(new Command(
  [
    'prefix' => '', // default (no extra command, i.e. "keyword myTask"
    'command' => function ($input) {
        $tasks = ['joel','david','day'];

        $List = new ItemList;
        foreach ($tasks as $task) {
            if (stristr($task, $input)) {
                $List->add(new Item([
                    'title' => 'Start Tracking "' . $task. '"',
                    'arg' => 'start ' . $task,
                    'autocomplete' => $task])
                );
            }
        }

        echo $List->output();
    }
  ]
));

$Workflow->run();
