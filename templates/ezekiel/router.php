<?php
    namespace app\routers;

    use native\libs\Router;

    class <PLURAL> extends Router {
        protected function load() : void
        {
            // $controller = module_ctrl('controller');
            // $controller = native_ctrl('controller');
            // $controller = ctrl('controller');

            $this->get('/route', [$controller, 'handle']);
        }
    }
