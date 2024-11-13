<?php
return [
    'hosts' => [
        env('ELASTICSEARCH_HOST', 'elasticsearch:9200'), // Địa chỉ host Elasticsearch
    ],
    'index' => env('ELASTICSEARCH_INDEX', 'words'),
];
