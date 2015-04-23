<?php

return [
    'console' => [
        'router' => [
            'routes' => [
                'spiffy-assetic.dump' => [
                    'options' => [
                        'route' => 'assetic dump [--verbose|-v]',
                        'defaults' => [
                            'controller' => 'Spiffy\AsseticModule\Controller\ConsoleController',
                            'action' => 'dump'
                        ]
                    ]
                ],
                'spiffy-assetic.watch' => [
                    'options' => [
                        'route' => 'assetic watch [--force|-f] [--verbose|-v] [--period=]',
                        'defaults' => [
                            'controller' => 'Spiffy\AsseticModule\Controller\ConsoleController',
                            'action' => 'watch',
                            'period' => 1
                        ]
                    ]
                ]
            ]
        ]
    ],
    'controllers' => [
        'invokables' => [
            'Spiffy\AsseticModule\Controller\AssetController' => 'Spiffy\AsseticModule\Controller\AssetController',
        ],
        'factories' => [
            'Spiffy\AsseticModule\Controller\ConsoleController' => 'Spiffy\AsseticModule\Controller\ConsoleControllerFactory',
        ]
    ],
    'listeners' => [
        // todo: implement
        //'Spiffy\Assetic\Mvc\RouteLoader',
        'Spiffy\AsseticModule\Mvc\RenderListener',
    ],
    'service_manager' => [
        'invokables' => [
            // mvc listener
            'Spiffy\AsseticModule\Mvc\RenderListener' => 'Spiffy\AsseticModule\Mvc\RenderListener',

            // plugins
            'Spiffy\AsseticModule\Plugin\AssetLoaderPlugin' => 'Spiffy\AsseticModule\Plugin\AssetLoaderPlugin',
            'Spiffy\AsseticModule\Plugin\FilterLoaderPlugin' => 'Spiffy\AsseticModule\Plugin\FilterLoaderPlugin',
            'Spiffy\AsseticModule\Plugin\ResolveAliasPlugin' => 'Spiffy\AsseticModule\Plugin\ResolveAliasPlugin',
            'Spiffy\AsseticModule\Plugin\TwigLoaderPlugin' => 'Spiffy\AsseticModule\Plugin\TwigLoaderPlugin',
        ],
        'factories' => [
            // twig
            'Spiffy\Assetic\Twig\AsseticExtension' => 'Spiffy\AsseticModule\Twig\AsseticExtensionFactory',

            // assetic filters
            'Assetic\Filter\LessFilter' => 'Spiffy\AsseticModule\Filter\LessFilterFactory',

            // spiffy assetic filters
            'Spiffy\Assetic\Filter\PhpCssAliasEmbedFilter' => 'Spiffy\AsseticModule\Filter\PhpCssAliasEmbedFilterFactory',

            // mvc listeners
            'Spiffy\Assetic\Mvc\RouteLoader' => 'Spiffy\Assetic\Mvc\RouteLoaderFactory',

            // plugins
            'Spiffy\Assetic\Plugin\DirectoryLoaderPlugin' => 'Spiffy\AsseticModule\Plugin\DirectoryLoaderPluginFactory',

            'Spiffy\Assetic\AsseticService' => 'Spiffy\AsseticModule\AsseticServiceFactory',
            'Spiffy\AsseticModule\ModuleOptions' => 'Spiffy\AsseticModule\ModuleOptionsFactory',
        ]
    ],
    'spiffy-assetic' => [
        'debug' => false,
        'autoload' => false,
        'cache_dir' => 'data/cache/assetic',
        'root_dir' => './',
        'output_dir' => 'public',
        'assets' => [],
        'filters' => [
            'cssmin' => 'Assetic\Filter\CssMinFilter',
            'cssaliasembed' => 'Spiffy\Assetic\Filter\PhpCssAliasEmbedFilter',
            'cssrewrite' => 'Assetic\Filter\CssRewriteFilter',
            'jsmin' => 'Assetic\Filter\JSMinFilter',
            'less' => 'Assetic\Filter\LessFilter',
        ],
        'filter_options' => [
            'less' => [
                'node_bin' => '/usr/bin/node',
                'node_paths' => ['/usr/lib/node_modules'],
                'load_paths' => [],
            ]
        ],
        'variables' => [],
        'console_plugins' => [
            'Spiffy\Assetic\Plugin\DirectoryLoaderPlugin',
        ],
        'parsers' => [
            'javascripts' => ['tag' => 'javascripts', 'output' => 'js/*.js'],
            'stylesheets' => ['tag' => 'stylesheets', 'output' => 'css/*.css'],
            'image' => ['tag' => 'image', 'output' => 'image/*', 'single' => true],
        ],
        'plugins' => [
            'asset_loader' => 'Spiffy\AsseticModule\Plugin\AssetLoaderPlugin',
            'filter_loader' => 'Spiffy\AsseticModule\Plugin\FilterLoaderPlugin',
            'resolve_alias' => 'Spiffy\AsseticModule\Plugin\ResolveAliasPlugin',
        ],
    ],
    'zfctwig' => [
        'extensions' => [
            'assetic' => 'Spiffy\Assetic\Twig\AsseticExtension'
        ],
    ]
];
