<?php

/**
 * Register application modules
 */
$application->registerModules(array(
    'frontend' => array(
        'className' => 'Mdg\Frontend\Module',
        'path' => __DIR__ . '/../apps/frontend/Module.php'
    ),
    'member' => array(
        'className' => 'Mdg\Member\Module',
        'path' => __DIR__ . '/../apps/member/Module.php'
    ),
    'manage' => array(
        'className' => 'Mdg\Manage\Module',
        'path' => __DIR__ . '/../apps/manage/Module.php'
    ),
    'store' => array(
        'className' => 'Mdg\Store\Module',
        'path' => __DIR__ . '/../apps/store/Module.php'
    ),
    'wuliu' => array(
        'className' => 'Mdg\Wuliu\Module',
        'path' => __DIR__ . '/../apps/wuliu/Module.php'
    ),
    'api' => array(
        'className' => 'Mdg\Api\Module',
        'path' => __DIR__ . '/../apps/api/Module.php'
    )
));
