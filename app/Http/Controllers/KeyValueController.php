<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreKeyValueRequest;
use App\Http\Resources\KeyValueResource;
use App\Services\KeyValueService;
use Illuminate\Http\Request;

class KeyValueController extends Controller
{
    public function __construct(private KeyValueService $service)
    {}

    public function index()
    {
        return KeyValueResource::collection(
            $this->service->paginated()
        );
    }

    public function store(StoreKeyValueRequest $request)
    {
        $record = $this->service->store($request->validated());

        return new KeyValueResource($record);
    }

    public function show($key)
    {
        $timestamp = request()->query('timestamp');

        $records = $this->service->findByKey($key, $timestamp);

        return KeyValueResource::collection($records);
    }
}
