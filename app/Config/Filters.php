<?php
namespace Config;

use CodeIgniter\Config\BaseConfig;

class Filters extends BaseConfig
{
    public array $aliases = [
        'csrf'          => \CodeIgniter\Filters\CSRF::class,
        'toolbar'       => \CodeIgniter\Filters\DebugToolbar::class,
        'honeypot'      => \CodeIgniter\Filters\Honeypot::class,
        'invalidchars'  => \CodeIgniter\Filters\InvalidChars::class,
        'secureheaders'=> \CodeIgniter\Filters\SecureHeaders::class,
        'cors'          => \CodeIgniter\Filters\Cors::class,
        'forcehttps'    => \CodeIgniter\Filters\ForceHTTPS::class,
        'pagecache'     => \CodeIgniter\Filters\PageCache::class,
        'performance'   => \CodeIgniter\Filters\PerformanceMetrics::class,
        'auth'          => \App\Filters\AuthFilter::class,
        'nostore'       => \App\Filters\NoStoreFilter::class,
    ];
    // HTML varies by login state, so page caching is not enabled.
    public array $required = ['before' => ['forcehttps'], 'after' => ['toolbar']];
    public array $globals = ['before' => [], 'after' => ['nostore']];
    public array $methods = [];
    public array $filters = [];
}
