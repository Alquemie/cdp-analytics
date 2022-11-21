<?php return array(
    'root' => array(
        'pretty_version' => '1.0.0+no-version-set',
        'version' => '1.0.0.0',
        'type' => 'wordpress-plugin',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'reference' => NULL,
        'name' => 'alquemie/segment-cdp',
        'dev' => true,
    ),
    'versions' => array(
        'alquemie/segment-cdp' => array(
            'pretty_version' => '1.0.0+no-version-set',
            'version' => '1.0.0.0',
            'type' => 'wordpress-plugin',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'reference' => NULL,
            'dev_requirement' => false,
        ),
        'composer/installers' => array(
            'pretty_version' => 'v1.12.0',
            'version' => '1.12.0.0',
            'type' => 'composer-plugin',
            'install_path' => __DIR__ . '/./installers',
            'aliases' => array(),
            'reference' => 'd20a64ed3c94748397ff5973488761b22f6d3f19',
            'dev_requirement' => false,
        ),
        'roave/security-advisories' => array(
            'pretty_version' => 'dev-master',
            'version' => 'dev-master',
            'type' => 'metapackage',
            'install_path' => NULL,
            'aliases' => array(),
            'reference' => '5317244268eb40e418f1cf8afa6d1d9df4e1f4a3',
            'dev_requirement' => true,
        ),
        'roundcube/plugin-installer' => array(
            'dev_requirement' => false,
            'replaced' => array(
                0 => '*',
            ),
        ),
        'segmentio/analytics-php' => array(
            'pretty_version' => '3.5.0',
            'version' => '3.5.0.0',
            'type' => 'library',
            'install_path' => __DIR__ . '/../segmentio/analytics-php',
            'aliases' => array(),
            'reference' => '2ed390d1c1e03328f3d5b6c43a72a4ce6a8e7f85',
            'dev_requirement' => false,
        ),
        'shama/baton' => array(
            'dev_requirement' => false,
            'replaced' => array(
                0 => '*',
            ),
        ),
    ),
);
