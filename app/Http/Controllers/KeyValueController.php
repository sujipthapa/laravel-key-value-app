<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreKeyValueRequest;
use App\Http\Resources\KeyValueResource;
use App\Services\KeyValueService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class KeyValueController extends Controller
{
    public function __construct(private KeyValueService $service)
    {}

    public function index(): AnonymousResourceCollection
    {
        return KeyValueResource::collection(
            $this->service->paginated()
        );
    }

    public function store(StoreKeyValueRequest $request): KeyValueResource
    {
        $record = $this->service->store($request->validated());

        return new KeyValueResource($record);
    }

    public function show(string $key): AnonymousResourceCollection
    {
        $timestamp = request()->query('timestamp');

        $records = $this->service->findByKey($key, $timestamp);

        return KeyValueResource::collection($records);
    }
}
