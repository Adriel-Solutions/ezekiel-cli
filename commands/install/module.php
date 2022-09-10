<?php

    namespace commands\install;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Module extends Command {
        protected string $identifier = 'install:module';

        public function run(Application $app, ?string ...$args): void
        {
            if(empty($args)) {
                $app->output('Missing arguments');
                $app->output('Usage: php ezekiel install:module <name>');
                return;
            }

            if(!$app->is_available('git')) {
                $app->output('Missing dependencies');
                $app->output('This command requires git to be installed');
                return;
            }

            if(!$app->is_available('sed')) {
                $app->output('Missing dependencies');
                $app->output('This command requires sed to be installed');
                return;
            }

            if(!$app->is_available('grep')) {
                $app->output('Missing dependencies');
                $app->output('This command requires grep to be installed');
                return;
            }

            $name = $args[0];
            $repository_url = Constants::$GIT_ORGANIZATION_URL . "/ezekiel-module-$name";

            $app->execute("git clone $repository_url ./app/modules/$name");
            $app->execute("rm -rf ./app/modules/$name/.git");

            $line_opening = (int) $app->execute('cat ./app/bootstrap.php | grep -n "public static function setup" | grep -o "^[0-9]*"');
            $line_closing = $line_opening + (int) $app->execute('sed -n \'6,$p\' ./app/bootstrap.php | grep -nm 1 "}" | grep -o "^[0-9]*"');

            $instruction = "\t\t\tmodule(\"$name\")->activate();";
            $app->execute('sed -i "" -e $\'' . $line_closing - 2 . ' a\\\\\\n\'\'' . $instruction . '\' ./app/bootstrap.php');

            $app->output("Module $name installed and activated");
        }
    }
