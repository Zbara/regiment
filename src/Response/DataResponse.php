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

    #[ArrayShape(['code' => "", 'result' => "array", 'system' => "array"])]
    public function success($code, $result): array
    {
        return [
            'code' => $code,
            'result' => $result,
            'system' => $this->system()
        ];
    }

    #[ArrayShape(['code' => "", 'error' => "array", 'system' => "array"])]
    public function error($code, $messages): array
    {
        return [
            'code' => $code,
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
