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
