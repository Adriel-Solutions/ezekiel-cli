<?php

    namespace commands\install;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Ui extends Command {
        protected string $identifier = 'install:ui';
        protected string $description = 'Install the remote ui library inside the project by downloading its code';
        protected string $help = 'Usage: ezekiel install:ui';
        protected array $dependencies = [ 'git' , 'sed' , 'awk' , 'grep' ];

        public function run(Application $app, ?string ...$args): void
        {
            $this->exit_if_missing_dependencies($app);

            if(empty($args)) {
                $app->output('Missing arguments');
                $app->output('Usage: ezekiel install:module <name>');
                return;
            }

            $name = $args[0];

            if(file_exists("./app/modules/$name/module.json")) {
                $metadata = json_decode(file_get_contents("./app/modules/$name/module.json"), true);
                $version = $metadata['version'];
                $app->output("Module $name installed already in version $version");
                return;
            }

            $repository_url = Constants::$GIT_ORGANIZATION_URL . "/ezekiel-module-$name";
            $app->execute('git ls-remote ' . $repository_url . ' 2>/dev/null');
            $repository_exists = 0 === $app->get_last_exit_code();

            if(!$repository_exists) {
                $app->output("Module $name does not exist");
                return;
            }

            $app->execute("git clone $repository_url ./app/modules/$name");
            $app->execute("rm -rf ./app/modules/$name/.git");

            $line_opening = (int) $app->execute('cat ./app/bootstrap.php | grep -n "public static function setup" | grep -o "^[0-9]*"');
            $line_closing = $line_opening + (int) $app->execute('sed -n \'6,$p\' ./app/bootstrap.php | grep -nm 1 "}" | grep -o "^[0-9]*"');

            $instruction = "\t\t\tmodule(\"$name\")->activate();";
            $app->execute('sed -i "" -e $\'' . $line_closing - 2 . ' a\\\\\\n\'\'' . $instruction . '\' ./app/bootstrap.php');

            $app->ezekiel('run:migrations');

            $app->output("Module $name installed and activated");
        }
    }

