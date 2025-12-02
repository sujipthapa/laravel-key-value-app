<?php
namespace App\Services;

use App\Exceptions\API\NotFoundException;
use App\Exceptions\API\ServerException;
use App\Models\KeyValue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class KeyValueService
{
    public function paginated()
    {
        try {
            return KeyValue::query()
                ->select('key', 'value')
                ->latest()
                ->paginate();
        } catch (Throwable $exception) {
            $traceId = Str::uuid();

            throw new ServerException("Failed to retrieve data", [
                'trace_id' => $traceId,
            ]);
        }
    }

    public function store(array $validated)
    {
        DB::beginTransaction();

        try {
            $record = KeyValue::create($validated);

            DB::commit();

            return $record;
        } catch (Throwable $exception) {
            DB::rollBack();

            $traceId = Str::uuid();

            throw new ServerException("Failed to store the key-value pair", [
                'trace_id' => $traceId,
            ]);
        }
    }

    public function findByKey(string $key, $timestamp)
    {
        try {
            $records = KeyValue::query()
                ->where('key', $key)
                ->when($timestamp, fn($q) => $q->where('created_at', '<=', Carbon::createFromTimestamp($timestamp)))
                ->orderByDesc('created_at')
                ->paginate();
        } catch (Throwable $exception) {
            $traceId = Str::uuid();

            throw new ServerException("Failed to retrieve data by key: {$key}, timestamp: {$timestamp}", [
                'trace_id' => $traceId,
            ]);
        }

        if (! $records->getCollection()->count()) {
            $traceId = Str::uuid();

            throw new NotFoundException("No match found.", 'NOT_FOUND', [
                'trace_id'  => $traceId,
                'key'       => $key,
                'timestamp' => $timestamp,
            ]);
        }

        return $records;
    }
}
