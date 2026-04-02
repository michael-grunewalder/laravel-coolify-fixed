<?php

declare(strict_types=1);

namespace Stumason\Coolify\Detectors;

use Laravel\Horizon\Horizon;

class HorizonDetector implements PackageDetector
{
    public function name(): string
    {
        return 'Laravel Horizon';
    }

    public function isInstalled(): bool
    {
        return class_exists(Horizon::class);
    }

    public function getSupervisorConfig(): ?string
    {
        return <<<'CONF'
[program:worker-horizon]
process_name=%(program_name)s
command=php /app/artisan horizon
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
numprocs=1
startsecs=0
stopwaitsecs=3600
stdout_logfile=/var/log/worker-horizon.log
stderr_logfile=/var/log/worker-horizon.log
CONF;
    }

    public function getNginxLocationBlocks(): array
    {
        return [];
    }

    public function getPhpExtensions(): array
    {
        // Horizon requires Redis - pcntl is for signal handling
        return ['redis', 'pcntl'];
    }
}
