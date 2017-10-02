<?php
namespace Alfred;

class Command
{
    public function __construct(array $config)
    {
        $this->prefix = $config['prefix'];
        $this->command = $config['command'];

        return $this;
    }
}
