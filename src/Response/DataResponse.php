<?php

namespace App\Response;

use App\Service\GitRevision;
use JetBrains\PhpStorm\ArrayShape;

class DataResponse
{
    const STATUS_ERROR = 0;
    const STATUS_SUCCESS = 1;
    private GitRevision $git;

    public function __construct(GitRevision $gitRevision)
    {
        $this->git = $gitRevision;
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

    #[ArrayShape(['environment' => "mixed", 'memory' => "mixed", 'gitDate' => "string", 'gitRev' => "string", 'time' => "int"])]
    private function system(): array
    {
        return [
            'environment' => $_ENV['APP_ENV'],
            'memory' => sys_getloadavg()[0],
            'gitDate' => $this->git->getDate(),
            'gitRev' => $this->git->getBuild(),
            'time' => time()
        ];
    }
}
