<?php

namespace App\Providers;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class QueryLoggerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if (config('system.sql_query_log.enabled', false) === false || config('app.env') == 'production') {
            return;
        }

        DB::listen(function (QueryExecuted $query) {
            if ($query->time < config('system.sql_query_log.slower_than', 0)) {
                return;
            }

            $sqlWithPlaceholders = str_replace(['%', '?'], ['%%', '%s'], $query->sql);

            $bindings = $query->connection->prepareBindings($query->bindings);
            $pdo = $query->connection->getPdo();
            $realSql = $sqlWithPlaceholders;
            $duration = format_duration($query->time / 1000);

            if (count($bindings) > 0) {
                $realSql = vsprintf($sqlWithPlaceholders, array_map([$pdo, 'quote'], $bindings));
            }

            Log::debug(sprintf('[%s] [%s] %s | %s: %s', $query->connection->getDatabaseName(), $duration, $realSql, request()->method(), request()->getRequestUri()));
        });
    }
}
