<?php
namespace Alfred;

use Dayjo\JSON;

class Workflow
{
    private $commands = [];
    private $args;
    public $state = 1;
    const STATE_SEARCHING = 1;
    const STATE_RUNNING = 2;

    /**
     * FUnction to create a new one
     * @param array $configTemplate A simple array of default config values
     * i.e. ["name" => "John Smith", "debug" = 1]
     */
    public function __construct(array $configTemplate = null, String $configPath = "workflow-config.json")
    {
        // If they've specified a config template,
        // then it's likely they want a config file
        if ($configTemplate) {

            // Create the file
            $this->configFile = new JSON($configPath);
            $this->config =& $this->configFile->data;

            // Loop through the template and create items to set
            foreach ($configTemplate as $configItem => $defaultValue) {
                $commands[] = $configItem;

                if (empty($this->config->$configItem)) {
                    $this->config->$configItem = $defaultValue;
                }
            }

            /**
             * Add the command for generating reports
             */
            $this->addCommand(new Command(
              [
                'prefix' => ':config',
                'command' => function ($input) use ($commands) {
                    if ($this->state == static::STATE_SEARCHING) {
                        // Create a new Item List
                        $List = new ItemList;

                        // Loop through all of the existing task names
                        foreach ($commands as $cmd) {
                            $inp = str_replace($cmd, "", $input);

                            // If the input matches the task name, output the task
                            if (trim($inp) == '' || ((stristr($cmd, $input) || stristr($input, $cmd)))) {
                                if (stristr($input, $cmd)) {
                                    $to = " to: " . $inp;
                                }

                                // Add the new item to the list
                                $List->add(new Item([
                                    'title' => "Set " . $cmd . " (".  $this->config->{$cmd} . ")" . $to,
                                    'arg' => ":config {$cmd} {$inp}",
                                    'autocomplete' => ":config " . $cmd . " " . ($to ? "  {$inp} " : "")
                                    ]
                                ));
                            }
                        }

                        // Output the list of tasks to
                        echo $List->output();
                    } else {
                        $input = str_replace("  ", " ", $input);
                        $inputs = explode(" ", trim($input));
                        $this->config->{$inputs[0]} = $inputs[1];

                        echo "Successfully set " . $inputs[0] . " to " . $inputs[1];
                    }
                }
              ]
            ));
        }
    }

    /**
     * Add a command to the commands arrays
     * @param Command $command [description]
     */
    public function addCommand(Command $command)
    {
        $this->commands[] = $command;
    }

    /**
     * Actually run this workflow using the input from Alfred
     * @return [type] [description]
     */
    public function run($query = null)
    {
        global $argv;
        $called = false;

        $this->args = $query ? $query : $argv[1];

        // The command that has been sent
        $commandPrefix = explode(' ', $this->args)[0];

        // Loop through the commands
        foreach ($this->commands as $cmd) {

            // If it's set to be default (no prefix) we'll look for this after the loop
            if ($cmd->prefix == '') {
                $defaultCommand = $cmd;
            }

            // Identify if this is the command being run
            if ($cmd->prefix == $commandPrefix) {
                $called = $this->runCommand($cmd);
                break;
            }
        }

        if (!$called) {

            // Loop through the commands
            foreach ($this->commands as $cmd) {
                // Identify if this is the command being run
                if (substr($cmd->prefix, 0, strlen($commandPrefix)) == $commandPrefix) {
                    $called = $this->runCommand($cmd);
                    break;
                }
            }

            if (!$called) {
                $this->runCommand($defaultCommand);
            }
        }
    }

    /**
     * Runs the the specified command
     * @param  Command $cmd [description]
     * @return Command
     */
    private function runCommand(Command $cmd)
    {
        // Run the command
        if (is_callable($cmd->command)) {
            call_user_func($cmd->command, trim(substr($this->args, strlen($cmd->prefix))));
        } else {
            throw new Exception("Command (" . $cmd->prefix . ") doesn't exist");
        }

        return $cmd;
    }
}
