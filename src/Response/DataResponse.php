<?php

namespace App\Response;

use App\Service\GitRevision;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\EventListener\ResponseListener;

class DataResponse
{
    const STATUS_ERROR = 0;
    const STATUS_SUCCESS = 1;
    private GitRevision $git;
    private bool $debug;
    private string $environment;

    public function __construct($debug, $environment, GitRevision $gitRevision)
    {
        $this->debug = $debug;
        $this->git = $gitRevision;
        $this->environment = $environment;
    }
    
    #[ArrayShape(['status' => "", 'result' => "", 'system' => "array"])]
    public function success($status, $result): array
    {
        return [
            'status' => $status,
            'result' => $result,
            'system' => $this->system()
        ];
    }


    #[ArrayShape(['status' => "", 'error' => "array", 'system' => "array"])]
    public function error($status, $messages): array
    {
        return [
            'status' => $status,
            'error' => [
                'messages' => $messages
            ],
            'system' => $this->system()
        ];
    }


    #[ArrayShape(['environment' => "", 'memory' => "mixed", 'gitDate' => "string", 'gitRev' => "string", 'time' => "int", 'debug' => ""])]
    private function system(): array
    {
        return [
            'environment' => $this->environment,
            'memory' => sys_getloadavg()[0],
            'gitDate' => $this->git->getDate(),
            'gitRev' => $this->git->getBuild(),
            'time' => time(),
            'debug' => $this->debug
        ];
    }
}
