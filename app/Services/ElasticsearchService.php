<?php

namespace App\Services;

use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Support\Facades\Auth;
use App\Models\Word;

class ElasticsearchService
{
    protected $client;

    public function __construct()
    {
        $this->client = ClientBuilder::create()
            ->setHosts(config('elasticsearch.hosts'))
            ->build();
    }

    public function index($params)
    {
        return $this->client->index($params);
    }

    public function search($params)
    {
        return $this->client->search($params);
    }

    public function delete($params)
    {
        return $this->client->delete($params);
    }

    public function indexData($data)
    {
        try {
            //dd($data);
            $this->client->index([
                'index' => config('elasticsearch.index'),
                'body' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function syncDataToElastic($words)
    {
        foreach ($words as $word) {
            unset($word['updated_at']);
            unset($word['created_at']);
            try {
                $this->indexData($word);
            }catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()]);
            }
        }
    }

    public function searchDataVietnamese($query)
    {
        if (empty($query)) {
            return response()->json(['error' => 'Query is empty'], 400);
        }
        $params = [
            'index' => config('elasticsearch.index'),
            'body'  => [
                'query' => [
                    'match' => [
                        'vi' => $query,
                    ]
                ]
            ]
        ];
        try {
            $response = $this->client->search($params);
            $data = $response->asArray();
            return response()->json($data['hits']['hits']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function searchDataEnglish($query)
    {
        if(empty($query))
        {
            return response()->json(['error' => 'Query is empty'], 400);
        }
        $params = [
            'index' => config('elasticsearch.index'),
            'body'  => [
                'query' => [
                    'match' => [
                        'eng' => $query
                    ]
                ]
            ]
        ];
        try {
            $response = $this->client->search($params);
            $data = $response->asArray();
            return response()->json($data['hits']['hits']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    public function searchData($query)
    {
        if (empty($query)) {
            return response()->json(['error' => 'Query is empty'], 400);
        }
        $params = [
            'index' => config('elasticsearch.index'),
            'body'  => [
                '_source' => ['eng', 'vi', 'example'],
                'query' => [
                    'multi_match' => [
                        'query' => $query,
                        'fields' => ['eng', 'vi', 'example'],
                        'fuzziness' => 'AUTO'
                    ],

                ]
            ]
        ];
        try {
            $response = $this->client->search($params);
            $data = $response->asArray();
            return response()->json($data['hits']['hits']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    public function getAllData()
    {
        $params = [
            'index' => config('elasticsearch.index'),
            'body'  => [
                'query' => [
                    'match_all' => new \stdClass()
                ]
            ]
        ];

        try {
            $response = $this->client->search($params);
            $data = $response->asArray();
            return response()->json($data['hits']['hits']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    public function deleteAllData()
    {
        $params = [
            'index' => config('elasticsearch.index'),
            'body'  => [
                'query' => [
                    'match_all' => new \stdClass()
                ]
            ]
        ];

        try {
            $response = $this->client->deleteByQuery($params);

            return response()->json(['status' => 'success', 'message' => 'All data deleted successfully', 'data' => $response]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function deleteIndex($index)
    {
        $params = [
            'index' => $index
        ];

        $response = $this->client->indices()->delete($params);
        return $response;
    }

    public function createWordIndexMapping()
    {
        $params = [
            'index' => config('elasticsearch.index'),
            'body'  => [
                'settings' => [
                    'number_of_shards' => 1,
                    'number_of_replicas' => 0
                ],
                'mappings' => [
                    'properties' => [
                        'user_id' => [
                            'type' => 'integer'
                        ],
                        'eng' => [
                            'type' => 'text',
                            'analyzer' => 'standard'
                        ],
                        'vi' => [
                            'type' => 'text'
                        ],
                        'type' => [
                            'type' => 'text'
                        ],
                        'example' => [
                            'type' => 'text'
                        ],
                        'date_created' => [
                            'type' => 'date',
                            'format' => 'yyyy-MM-dd'
                        ]

                    ]
                ]
            ]
        ];

        $response = $this->client->indices()->create($params);
        return $response;
    }
}
