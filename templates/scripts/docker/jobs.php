<?php
    require './native/index.php';
    $service = default_service('jobs');
    $jobs = $service->get_all([ 'order' => [ 'pk' => 'ASC' ] ]);
    print(json_encode($jobs));
