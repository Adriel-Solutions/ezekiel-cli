<?php
    
    namespace classes;

    class Application {
        private Commands $commands;

        public function __construct()
        {
            $this->commands = new Commands();
        }

        public function register(Command $cmd) : void
        {
            $this->commands[$cmd->get_identifier()] = $cmd;
        }

        public function process(array $args) : void
        {
            if(count($args) < 2) {
                $this->output('Missing arguments');
                $this->output('Use `list` to view all the supported commands');
                $this->output('Use `help` to display the manual');
                return;
            }

            $cmd = $args[1];

            if(!isset($this->commands[$cmd])) {
                $this->output('This command is not supported');
                $this->output('Type `list` to view all the supported commands');
                $this->output('Type `help` to display the manual');
                return;
            }

            $cmd = $this->commands[$cmd];
            $args = array_slice($args, 2);

            $cmd->run($this, ...$args);
        }

        public function cls() : void
        {
            print("\033\033");
            print("\n");
        }

        public function output(string $str) : void
        {
            print($str);
            print("\n");
        }

        public function prompt(string $str) : string
        {
            return readline($str);
        }

        public function is_available(string $program) : string
        {
            return !empty(shell_exec("which $program"));
        }

        public function execute(string $command) : ?string
        {
            $this->output("Running command : $command");
            exec($command, $stdout, $exit_code);
            return join("\n", $stdout);
        }
    }

    // @TODO ajout autoloader
    // @TODO composer.json avec vendor bin 
    // @WANT ezekiel make:module
    // @WANT ezekiel make:service
    // @WANT ezekiel make:controller
    // @WANT ezekiel make:thirdparty
    // @WANT ezekiel make:router
    // @WANT ezekiel make:job
    // @WANT ezekiel make:resource (crud)

    // Reflexion 
    /* $app = new Application(); */

    /* $app->register_command(new CommandHelp()); */
    /* $app->register_command(new CommandList()); */
