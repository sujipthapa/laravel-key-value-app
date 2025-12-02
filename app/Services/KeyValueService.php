<?php
namespace App\Services;

use App\Exceptions\API\NotFoundException;
use App\Exceptions\API\ServerException;
use App\Models\KeyValue;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class KeyValueService
{
    public function paginated(): LengthAwarePaginator
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

    public function store(array $validated): KeyValue
    {
        DB::beginTransaction();

        try {
            $record = KeyValue::create($validated);

            DB::commit();

            return $record;
        } catch (QueryException $exception) {
            DB::rollBack();

            $traceId = Str::uuid();

            // Log real DB error for developers to watch.

            throw new ServerException("Failed to store the key-value pair", [
                'trace_id' => $traceId,
            ]);
        } catch (Throwable $exception) {
            DB::rollBack();

            $traceId = Str::uuid();

            throw new ServerException("Failed to store the key-value pair", [
                'trace_id' => $traceId,
            ]);
        }
    }

    public function findByKey(string $key, $timestamp): LengthAwarePaginator
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
