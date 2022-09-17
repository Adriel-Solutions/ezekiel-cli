<?php

    namespace commands\show;

    use classes\Command;
    use classes\Application;

    class Modules extends Command {
        protected string $identifier = 'show:modules';
        protected string $description = 'List all the installed modules';
        protected string $help = 'Usage: ezekiel show:modules';

        public function run(Application $app, ?string ...$args): void
        {
            $app_dir = $app->dir_app();
            $modules_dir = $app_dir . '/modules';

            $entries = scandir($modules_dir);
            $entries = array_filter($entries, fn($e) => !in_array($e, [ '.' , '..' ]));
            $entries = array_filter($entries, fn($e) => is_dir($modules_dir . '/' .$e));

            if(empty($entries)) {
                $app->output('No module installed');
                return;
            }

            $modules = [];
            foreach($entries as $e) {
                $metadata = json_decode(file_get_contents("$modules_dir/$e/module.json"), true);
                $modules[$e] = $metadata;
            }

            $bootstrap_code = file_get_contents("$app_dir/bootstrap.php");
            foreach($modules as $name => &$metadata) {
                $pattern = "/module\(['\"]" . $name . "['\"]\)->activate\(\)/";
                $metadata['activated'] = (bool) preg_match($pattern, $bootstrap_code);
            }

            $app->output_table(
                [ 'Module' , 'Version' , 'Activated' ],
                array_map(
                    fn($m) => [ $m['name'] , $m['version'] , $m['activated'] ? 'Yes' : 'No' ],
                    $modules,
                )
            );
        }
    }
