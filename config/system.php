<?php

/**
 * 自定义的配置信息
 *
 */
return [
    // SQL查询日志(生产环境请关闭)
    'sql_query_log' => [
        // 是否开启
        'enabled' => env('SQL_QUERY_LOG', false),
        // 慢查询时间/单位毫秒
        'slower_than' => env('SQL_QUERY_SLOWER', 0),
    ],
];
