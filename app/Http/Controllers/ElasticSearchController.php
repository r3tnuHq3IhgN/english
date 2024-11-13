<?php

namespace App\Http\Controllers;

use App\Services\ElasticsearchService;
use Illuminate\Http\Request;
use App\Models\Word;

class ElasticSearchController extends Controller
{
    //
    protected $elasticService;
    public function __construct(ElasticsearchService $elasticService)
    {
        $this->elasticService = $elasticService;
    }

    public function syncDataFromTable(Request $request)
    {
        $modelClass = $request->input('model');
        $model = app("App\\Models\\{$modelClass}");
        $words = $model::all()->toArray();
        $this->elasticService->syncDataToElastic($words);
        return response()->json(['message' => "{$modelClass} data indexed to Elasticsearch"]);
    }

    public function createIndexWordWithMapping(Request $request)
    {
        try {
            $this->elasticService->createWordIndexMapping();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
       return response()->json(['message' => 'Index with mapping created successfully']);
    }
}
