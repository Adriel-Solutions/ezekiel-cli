<?php
    
    namespace classes;

    class Application {
        private Commands $commands;
        private int $last_exit_code;
        private bool $is_quiet;
        private bool $is_dry;
        private bool $is_globally_quiet;
        private bool $is_csv_enforced;
        private bool $is_tailwind_disabled;

        public function __construct()
        {
            $this->commands = new Commands();
            $this->last_exit_code = 0;
            $this->is_quiet = false;
            $this->is_dry = false;
            $this->is_globally_quiet = false;
            $this->is_csv_enforced = false;
            $this->is_tailwind_disabled = false;
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
                $this->output('Use `list` to view all the supported commands');
                $this->output('Use `help` to display the manual');
                return;
            }

            $cmd = $this->commands[$cmd];
            $args = array_slice($args, 2);

            if(in_array('--dry-run', $args))
                $this->is_dry = true;

            if(in_array('--quiet', $args))
                $this->is_globally_quiet = true;

            if(in_array('--csv', $args))
                $this->is_csv_enforced = true;

            if(in_array('--no-tw', $args))
                $this->is_tailwind_disabled = true;

            $cmd->run($this, ...$args);
        }

        public function execute(string $command) : ?string
        {
            if(!$this->is_quiet && !$this->is_globally_quiet)
                $this->output("Running command : $command");

            if(!$this->is_dry)
                exec($command, $stdout, $this->last_exit_code);

            if(!$this->is_globally_quiet)
                $this->is_quiet = false;

            return join("\n", $stdout);
        }

        public function ezekiel(string $command) : void
        {
            if(!$this->is_dry)
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

        public function output_with_pagination(string $str) : void
        {
            /* $instructions = "q: quit\t\t\t\t\t\tup/down: Next/Prev\t\t\t\t\t\tspace: Next\n: "; */

            $page_width = $this->quiet()->execute('tput cols');
            $page_height = $this->quiet()->execute('tput lines') - 4;

            $pages = [];

            // Page filling algorithm
            $virtual_line_width = 0;
            $virtual_page_height = 0;
            $virtual_page_content = "";
            foreach(str_split($str) as $character) {
                $virtual_page_content .= $character;
                $virtual_line_width++;

                if($character === "\n") {
                    $virtual_line_width = 0;
                    $virtual_page_height++;
                }

                if($virtual_line_width > $page_width) {
                    $virtual_line_width = 0;
                    $virtual_page_height ++;
                }

                if($virtual_page_height > $page_height) {
                    $pages[] = $virtual_page_content;
                    $virtual_line_width = 0;
                    $virtual_page_height = 0;
                    $virtual_page_content = "";
                }
            }

            // Append remaining buffer to the page
            if(!empty($virtual_page_content))
                $pages[] = $virtual_page_content;

            // Display
            $cur = 0; $stop = false;
            while($cur < count($pages) && $stop !== true) {
                $this->output($pages[$cur]);

                if($cur === count($pages) - 1) break;

                $character = $this->prompt_character(":");

                switch($character) {
                    case 'q': $stop = true; break; // Q
                    default: $cur++; break; // Down / Space / Other
                }

                print("\n");
            }
        }

        public function output_table(array $headers, array $rows) : void
        {
            print($this->to_table($headers, $rows));
        }

        public function output_file(string $filename, string $content) : void
        {
            if(!$this->is_dry)
                file_put_contents($filename, $content);
            else
                print($content);
        }

        public function output_csv(array $headers, array $rows) : void
        {
            print($this->to_csv($headers, $rows));
        }

        public function prompt(string $str, string $default = "") : string
        {
            return readline($str) ?: $default;
        }

        public function prompt_character(string $str) : string
        {
            readline_callback_handler_install($str, function() {});
            $char = stream_get_contents(STDIN, 1);
            readline_callback_handler_remove();
            return $char;
        }

        public function is_available(string $program) : string
        {
            return !empty(shell_exec("which $program"));
        }

        public function quiet() : Application
        {
            $this->is_quiet = true;
            return $this;
        }

        public function is_csv_enforced() : bool
        {
            return $this->is_csv_enforced;
        }

        public function is_tailwind_disabled() : bool
        {
            return $this->is_tailwind_disabled;
        }

        public function to_table(array $headers, array $rows) : string
        {
            $output = "";

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
            $output .= "+";
            foreach($headers as $h) {
                $output .= str_repeat("-", $max_widths[$h] + 2);
                $output .= "+";
            }
            $output .= "\n";

            // Headers
            $output .= '|';
            foreach($headers as $h) {
                $output .= " " . str_pad($h, $max_widths[$h]) . " ";
                $output .= "|";
            }
            $output .= "\n";

            // Line between headers and body
            $output .= '+';
            foreach($headers as $h) {
                $output .= str_repeat("-", $max_widths[$h] + 2);
                $output .= "+";
            }
            $output .= "\n";

            // Body rows
            foreach($rows as $r) {
                for($i = 0; $i < count($headers); $i++) {
                    $h = $headers[$i];
                    $cell = $r[$i];

                    $output .= '|';
                    $output .= " " . str_pad($cell, $max_widths[$h]) . " ";
                }
                $output .= "|";

                $output .= "\n";

                // Inter-row line separator
                $output .= '+';
                foreach($headers as $h) {
                    $output .= str_repeat("-", $max_widths[$h] + 2);
                    $output .= "+";
                }

                $output .= "\n";
            }
            $output .= "\n";

            return $output;
        }

        public function to_csv(array $headers, array $rows) : string
        {
            $csv_output = "";

            $csv_output .= join(";", $headers);
            $csv_output .= "\n";
            foreach($rows as $row) {
                $csv_output .= join(";", $row);
                $csv_output .= "\n";
            }

            return $csv_output;
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
            $content = rtrim($content, "\n");
            return $content;
        }

        // --- 

        public function run_script(string $script) : string
        {
            $handle = tmpfile();

            $handle_uri = stream_get_meta_data($handle)['uri'];
            $new_handle_uri = $handle_uri . '.php';
            $handle_filename = pathinfo($new_handle_uri, PATHINFO_BASENAME);

            fwrite($handle, $script);
            rename($handle_uri, $new_handle_uri);

            return $this->execute("php -f /tmp/$handle_filename");
        }

        public function run_docker_script(string $container, string $script) : string
        {
            $handle = tmpfile();

            $handle_uri = stream_get_meta_data($handle)['uri'];
            $new_handle_uri = $handle_uri . '.php';
            $handle_filename = pathinfo($new_handle_uri, PATHINFO_BASENAME);

            fwrite($handle, $script);
            rename($handle_uri, $new_handle_uri);

            $this->execute("docker cp $new_handle_uri $container:/tmp/$handle_filename");
            return $this->execute("docker exec -w /app -it $container sh -c 'php -f /tmp/$handle_filename'");
        }
    }
