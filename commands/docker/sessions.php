<?php

    namespace commands\docker;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Sessions extends Command {
        protected string $identifier = 'docker:sessions';
        protected string $description = 'Display sessions inside Docker FPM container';
        protected string $help = 'Usage: ezekiel docker:sessions';
        protected array $dependencies = [ 'docker' ];

        private function _to_long_type(string $short_type) {
            return [
                'a' => 'array',
                'b' => 'bool',
                's' => 'string',
                'o' => 'object',
                'i' => 'int',
                'f' => 'float'
            ][$short_type];
        }

        public function run(Application $app, ?string ...$args): void
        {
            $this->exit_if_missing_dependencies($app);
            $container = $app->execute("cat docker-compose.dev.yml | grep -E \"container_name: .*-fpm\" | sed -E 's/container_name://' | tr -d '\" '");
            $command = 'find /tmp/ -name sess* -print | xargs -I{} sh -c "cat {} && echo"';
            $output = $app->execute("docker exec -it $container sh -c '$command'");

            if(empty($output)) exit();
    
            // Multi-line to array
            $sessions = explode("\n", $output);

            // Removing last ; of each line
            $sessions = array_map(fn($s) => rtrim($s, ';'), $sessions);

            // Turning every line into a proper PHP array representation
            $sessions = array_map(
                function($s) {
                    $attributes = explode(';', $s);
                    $attributes = array_map(
                        function($a) {
                            $first_parts = explode('|', $a);
                            $second_parts = explode(':', $first_parts[1]);

                            return [
                                'name' => $first_parts[0],
                                'type' => $this->_to_long_type($second_parts[0]),
                                'length' => $second_parts[0] !== 's' ? 'NA' : $second_parts[1],
                                'value' => $second_parts[0] === 's' ? trim($second_parts[2], '"') : $second_parts[1]
                            ];
                        },
                        $attributes
                    );
                    return $attributes;
                },
                $sessions
            );

            // Turn attributes into a simple string \n-delimited
            $sessions = array_map(
                fn($s) => implode(" / ", array_map( fn($a) => $a['name'] . ' (' . $a['type'] . ') = ' . $a['value'] , $s)),
                $sessions
            );

            // Print table
            $headers = [ 'ID' , 'SESSION' ];
            $rows = []; for($i = 0; $i < count($sessions); $i++) $rows[] = [ $i + 1 , $sessions[$i] ];

            $app->output_table($headers, $rows);
        }
    }

