<?php
    
    namespace classes;

    class Application {
        private Commands $commands;
        private int $last_exit_code;

        public function __construct()
        {
            $this->commands = new Commands();
            $this->last_exit_code = 0;
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

        public function execute(string $command) : ?string
        {
            $this->output("Running command : $command");
            exec($command, $stdout, $this->last_exit_code);
            return join("\n", $stdout);
        }

        public function ezekiel(string $command) : void
        {
            $this->process(array_merge(['ezekiel'], explode(' ', $command)));
        }

        // ---

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

        public function output_table(array $headers, array $rows) : void
        {
            $max_widths = [];
            for($i = 0; $i < count($headers); $i++) {
                $h = $headers[$i];
                $max_width = strlen($h);

                foreach($rows as $r) {
                    $cell_width = strlen($r[$i]);

                    if($max_width > $cell_width) continue;

                    $max_width = $cell_width;
                }

                $max_widths[$h] = $max_width;
            }

            // First line
            print("+");
            foreach($headers as $h) {
                print(str_repeat("-", $max_widths[$h] + 2));
                print("+");
            }
            print("\n");

            // Headers
            print('|');
            foreach($headers as $h) {
                print(" " . str_pad($h, $max_widths[$h]) . " ");
                print("|");
            }
            print("\n");

            // Line between headers and body
            print('+');
            foreach($headers as $h) {
                print(str_repeat("-", $max_widths[$h] + 2));
                print("+");
            }
            print("\n");

            // Body rows
            foreach($rows as $r) {
                for($i = 0; $i < count($headers); $i++) {
                    $h = $headers[$i];
                    $cell = $r[$i];

                    print('|');
                    print(" " . str_pad($cell, $max_widths[$h]) . " ");
                }
                print("|");

                print("\n");

                // Inter-row line separator
                print('+');
                foreach($headers as $h) {
                    print(str_repeat("-", $max_widths[$h] + 2));
                    print("+");
                }

                print("\n");
            }
            print("\n");
        }

        public function prompt(string $str, string $default = "") : string
        {
            return readline($str) ?: $default;
        }

        public function is_available(string $program) : string
        {
            return !empty(shell_exec("which $program"));
        }

        // ---

        public function get_last_exit_code() : int
        {
            return $this->last_exit_code;
        }

        public function get_commands() : Commands
        {
            return $this->commands;
        }

        // ---
        
        public function dir_app() : string
        {
            return './app';
        }

        public function dir_root() : string
        {
            return '.';
        }

        public function dir_templates() : string
        {
            return __DIR__ . '/../templates' ;
        }

        // ---

        public function fill_template(string $name, array $variables) : string
        {
            $content = file_get_contents($this->dir_templates() . $name);
            foreach($variables as $k => $v)
                $content = str_replace("<$k>", $v, $content);
            return $content;
        }

    }
