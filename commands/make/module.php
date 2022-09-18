<?php

    namespace commands\make;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Module extends Command {
        protected string $identifier = 'make:module';
        protected string $description = 'Scaffold a new module';
        protected string $help = 'Usage: ezekiel make:module <name>';

        public function run(Application $app, ?string ...$args): void
        {
            if(empty($args)) {
                $app->output('Missing arguments');
                $app->output('Usage: ezekiel make:module <name>');
                return;
            }

            $name = strtolower($args[0]);

            if(file_exists("./app/modules/$name/module.json")) {
                $metadata = json_decode(file_get_contents("./app/modules/$name/module.json"), true);
                $version = $metadata['version'];
                $app->output("Module $name exists already in version $version");
                return;
            }

            $name = ucfirst($args[0]);

            $variables = [
                'PLURAL' => $name,
                'TABLE' => strtolower($name),
                'NAME' => $name,
                'LOWER_SINGULAR' => strtolower(rtrim($name, 's')),
                'LOWER_NAME' => strtolower($name)
            ];

            $name = strtolower($name);

            $app_dir = $app->dir_app();

            // Create module dir
            $app->execute("mkdir $app_dir/modules/$name");

            // Create a default file for every possible need (except routers, specific instructions later)
            $files = [
                'controller' => $app->fill_template('/ezekiel/modules/controller.php', $variables),
                'service' => $app->fill_template('/ezekiel/modules/service.php', $variables),
                'adapter' => $app->fill_template('/ezekiel/modules/adapter.php', $variables),
                'job' => $app->fill_template('/ezekiel/modules/job.php', $variables)
            ];

            foreach($files as $f => $output) {
                $dir = $f . 's';

                $app->execute("mkdir $app_dir/modules/$name/$dir");
                $app->output_file("$app_dir/modules/$name/$dir/$name.php",$output);
            }

            // Create routers
            $app->execute("mkdir $app_dir/modules/$name/routers");
            foreach([ 'front' , 'api' , 'webhooks' ] as $r)
                $app->output_file(
                    "$app_dir/modules/$name/routers/$r.php",
                    $app->fill_template("/ezekiel/modules/router.php", [ ...$variables, 'ROUTER_NAME' => ucfirst($r) ])
                );

            // Create views directories
            $app->execute("mkdir $app_dir/modules/$name/views");
            $app->execute("mkdir $app_dir/modules/$name/views/assets");
            $app->execute("mkdir $app_dir/modules/$name/views/emails");
            $app->execute("mkdir $app_dir/modules/$name/views/pages");
            $app->execute("mkdir $app_dir/modules/$name/views/partials");

            // Create assets directories
            $app->execute("mkdir $app_dir/modules/$name/views/assets/scripts");
            $app->execute("mkdir $app_dir/modules/$name/views/assets/styles");
            $app->execute("mkdir $app_dir/modules/$name/views/assets/images");

            // Create migrations dir
            $app->execute("mkdir $app_dir/modules/$name/migrations");

            // Create module loader
            $app->output_file(
                "$app_dir/modules/$name/module.php",
                $app->fill_template("/ezekiel/module.php", $variables)
            );

            // Create module metadata
            $app->output_file(
                "$app_dir/modules/$name/module.json",
                $app->fill_template("/ezekiel/module.json", $variables)
            );

            // Activate module
            $instruction = "\t\t\tmodule(\"$name\")->activate();";
            $line_opening = (int) $app->execute('cat ./app/bootstrap.php | grep -n "public static function setup" | grep -o "^[0-9]*"');
            $line_closing = $line_opening + (int) $app->execute('sed -n \'6,$p\' ./app/bootstrap.php | grep -nm 1 "}" | grep -o "^[0-9]*"');
            $app->execute('sed -i "" -e $\'' . $line_closing - 2 . ' a\\\\\\n\'\'' . $instruction . '\' ./app/bootstrap.php');
        }
    }
