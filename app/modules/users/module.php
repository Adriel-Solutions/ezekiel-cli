<?php

    namespace app\modules\users;

    use native\libs\Router;
    use native\libs\Hooks;
    use native\libs\Module as BaseModule;

    /**
     * @module Users
     */
    class Module extends BaseModule {
        public function activate() : void 
        {
            Hooks::register('before_mount_front', function(Router $router) {
                $router->mount(module_router('users', 'front'));
            });

            Hooks::register('before_mount_api', function(Router $router) {
                $router->mount(module_router('users', 'api'));
            });

            Hooks::register('before_mount_webhooks', function(Router $router) {
                $router->mount(module_router('users', 'webhooks'));
            });
        }
    }