<?php
namespace Alfred;

use Dayjo\JSON;

class Workflow
{
    private $commands = [];
    private $args;

    /**
     * FUnction to create a new one
     * @param [type] $configTemplate [description]
     */
    public function __construct(array $configTemplate = null, String $configPath = "workflow-config.json")
    {

        // If they've specified a config template,
        // then it's likely they want a config file
        if ($configTemplate) {

            // Create the file
            $this->config = new JSON($configPath);

            // Create a new Item List
            $List = new ItemList;

            // Loop through the template and create items to set
            foreach ($configTemplate as $configItem => $defaultValue) {

                // Add the new item to the list
                $List->add(new Item([
                    'title' => $cmd,
                    'arg' => ":config {$configItem} {$defaultValue}",
                    'autocomplete' => ":config {$configItem}"])
                );
            }
            // Output the list of tasks to
            echo $List->output();
            exit
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
    public function run()
    {
        global $argv;
        $called = false;
        $this->args = $argv[1];

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
            $this->runCommand($defaultCommand);
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
