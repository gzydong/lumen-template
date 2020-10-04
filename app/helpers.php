<?php
/**
 *  公共函数方法
 */


/**
 * 格式化查询时间
 *
 * @param float $seconds sql 查询时间
 * @return string
 */
function format_duration(float $seconds)
{
    if ($seconds < 0.001) {
        return round($seconds * 1000000) . 'μs';
    } elseif ($seconds < 1) {
        return round($seconds * 1000, 2) . 'ms';
    }

    return round($seconds, 2) . 's';
}


/**
 * 获取 Service 服务
 *
 * @return  \App\Services\Service
 */
function services()
{
    return app('services');
}
