<?php

namespace Raideer\Tweech\Command;

class CommandRegistry
{
    /**
     * Holds the loaded commands.
     *
     * @var CommandInterface[]
     */
    protected $commands = [];

    /**
     * Symbol that identifies the command.
     *
     * @var string
     */
    protected $id = '!';

    /**
     * Registers a command.
     *
     * @param CommandInterface $command
     *
     * @return void
     */
    public function register(CommandInterface $command)
    {
        /*
         * Gets the name of the command
         * @var string
         */
        $name = $command->getCommand();

        /*
         * Check if the command isn't already registered
         * otherwise throw an exception
         */
        if (array_key_exists($name, $this->commands)) {
            throw new CommandException("Command with name '$name' already registered");
        }

        /*
         * Adds the command to the array
         */
        $this->commands[$name] = $command;
    }

    /**
     * Returns an array of commands.
     *
     * @return array
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * Check if the given string contains a command
     * if so, return the registered command.
     *
     * @param string $string Received Message
     *
     * @return Command Returns the command or null
     */
    public function getCommandIfExists($string)
    {
        if (starts_with($string, $this->id)) {
            foreach ($this->commands as $commandName => $command) {
                if (starts_with($string, $this->id.$commandName)) {
                    return $command;
                }
            }
        }
    }
}
