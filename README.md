# Workflow Class

Specify list of commands i.e.

```php
$Workflow = new AlfredWorkflow();
$Workflow->addCommand( new AlfredWorkflowCommand(
  [
    'prefix' => '*', // default (no extra command, i.e. "keyword myTask"
    'command' => TimeTracker@trackTime
  ]
) );
```

1. User enters the workflow Keyword
List of possible commands are output.



## Example Uses;
```php
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
        $tasks = ['joel','dayjo'];

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
```

```php
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
```