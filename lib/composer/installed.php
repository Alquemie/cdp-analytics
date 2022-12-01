<?php return array(
    'root' => array(
        'name' => 'alquemie/cdp-analytics',
        'pretty_version' => 'dev-main',
        'version' => 'dev-main',
        'reference' => 'f0d2109a5ecc5ebb90ea4ac218910c2515695cc8',
        'type' => 'wordpress-plugin',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => true,
    ),
    'versions' => array(
        'alquemie/cdp-analytics' => array(
            'pretty_version' => 'dev-main',
            'version' => 'dev-main',
            'reference' => 'f0d2109a5ecc5ebb90ea4ac218910c2515695cc8',
            'type' => 'wordpress-plugin',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'composer/installers' => array(
            'pretty_version' => 'v1.12.0',
            'version' => '1.12.0.0',
            'reference' => 'd20a64ed3c94748397ff5973488761b22f6d3f19',
            'type' => 'composer-plugin',
            'install_path' => __DIR__ . '/./installers',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'roave/security-advisories' => array(
            'pretty_version' => 'dev-master',
            'version' => 'dev-master',
            'reference' => 'c4ccfdab77bd92444c6a2f7813bc20f29dbb9d13',
            'type' => 'metapackage',
            'install_path' => NULL,
            'aliases' => array(),
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
            'reference' => '2ed390d1c1e03328f3d5b6c43a72a4ce6a8e7f85',
            'type' => 'library',
            'install_path' => __DIR__ . '/../segmentio/analytics-php',
            'aliases' => array(),
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
