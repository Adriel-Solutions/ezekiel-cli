<?php

    namespace commands\show;

    use classes\Command;
    use classes\Application;

    class Routes extends Command {
        protected string $identifier = 'show:routes';
        protected string $description = 'List all the routes';
        protected string $help = 'Usage: ezekiel show:routes';

        public function run(Application $app, ?string ...$args): void
        {
            $this->exit_if_missing_dependencies($app);

            $script = $app->fill_template('/scripts/docker/routes.php', []);
            $output = $app->run_script($script);

            $routes = json_decode($output, true);

            if(!$app->is_csv_enforced())
                $app->output_with_pagination(
                    $app->to_table(
                        // Method | Url | Controller | Gateways (middlewares)
                        [ 'M', 'U' , 'C' ],
                        array_map(
                            fn($r) => [
                                $r['method'],
                                $r['url'],
                                (!empty($r['module']) ? '(' . $r['module'] . ')' : '' ) .$r['controller'] . '->' . $r['function'],
                            ],
                            $routes,
                        )
                    )
                );
            else
                $app->output_csv(
                    // Method | Url | Controller | Gateways (middlewares)
                    [ 'Method', 'Url' ,  'Module' , 'Controller' ,  'Function', 'Middlewares' ],
                    array_map(
                        fn($r) => [
                            $r['method'],
                            $r['url'],
                            empty($r['module']) ? 'None' : $r['module'],
                            empty($r['controller']) ? 'None' : $r['controller'],
                            $r['function'],
                            join(', ', $r['middlewares'])
                        ],
                        $routes,
                    )
                );
        }
    }


