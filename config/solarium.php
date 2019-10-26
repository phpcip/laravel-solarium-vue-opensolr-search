<?php
/**
 * Created by PhpStorm.
 * User: ciprian
 * Date: 25/10/2019
 * Time: 19:51
 */


return [
    'endpoint' => [
        'opensolr' => [
            'host' => env('SOLR_HOST', 'useast612.solrcluster.com'),
            'port' => env('SOLR_PORT', '80'),
            'path' => env('SOLR_PATH', '/'),
            'core' => env('SOLR_CORE', 'opensolr')
        ]
    ]
];
