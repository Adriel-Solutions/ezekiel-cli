<?php
    require './native/index.php';

    $output_routes = [];

    // [METHOD -> ROUTES]
    $all_routes = $app->get_routes();

    // Loop through all Method => Routes
    foreach($all_routes as $method => $routes) {
        // Loop through all Routes (a route is like that : [ '/route' , $handler1, $handler2 ])
        foreach($routes as $route) {
            $url = $route[0];
            $handlers = array_slice($route, 1);
            $last_handler = end($handlers);

            if(!is_array($last_handler))
                continue;

            // If handler is like that : [$var, 'method']
            // Then it's necessarily a controller
            $class = get_class($last_handler[0]);
            $type = get_parent_class($last_handler[0]);
            $function = $last_handler[1];
            $module = str_contains($class, 'modules') ? ucfirst(explode("\\", $class)[2]) : '';

            $class = explode("\\", $class); 
            $class = end($class);

            $output_routes[$url] = [
                'method' => $method,
                'function' => $function,
                'controller' => $class,
                'middlewares' => [],
                'module' => $module,
                'url' => $url
            ];
        }
    }

    // For every route found, loop through all routes, and find middlewares via url matching
    foreach($output_routes as $url => &$metadata) {
        $route_potential_middlewares = [];

        foreach($all_routes as $method => $routes) {
            if($method != $metadata['method']) continue;

            foreach($routes as $route) {
                $route_url = $route[0];
                $route_handlers = array_slice($route, 1);

                $regex = '^'.str_replace('/', '\\/', $route_url).'$';
                if(strpos($regex, ':') !== false)
                    $regex = preg_replace('/\:([a-zA-Z_]+)/', '(?<$1>[a-zA-Z0-9_-]+)', $regex);

                if(!preg_match('/'.$regex.'/', $url)) continue;

                for($i = 0; $i < count($route_handlers); $i++) {
                    $route_handler = $route_handlers[$i];

                    if(is_array($route_handler) && $i == count($route_handlers) - 1)
                        continue;

                    if(is_object($route_handler)) {
                        $middleware = explode("\\", get_class($route_handler));
                        $middleware = end($middleware);
                    }
                    else
                        $middleware = $route_handler[1];

                    $route_potential_middlewares[] = $middleware;
                }

                if(empty($route_potential_middlewares))
                    continue;
            }
        }
        $metadata['middlewares'] = $route_potential_middlewares;
    }

    usort(
        $output_routes,
        function($a, $b) {
            if($a['module'] == $b['module'])
                if($a['controller'] == $b['controller'])
                    return $a['method'] <=> $b['method'];
                else
                    return $a['controller'] <=> $b['controller'];

            return $a['module'] <=> $b['module'];
        }
    );
    echo json_encode(array_values($output_routes));
