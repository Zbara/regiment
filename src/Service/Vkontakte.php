<?php

namespace App\Service;

use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Vkontakte
{
    public const VK_AUTH_ERROR_INVALID_CLIENT = 'invalid_client';
    public const VK_AUTH_ERROR_NEED_VALIDATION = 'need_validation';
    public const VK_AUTH_ERROR_NEED_CAPTCHA = 'need_captcha';

    protected HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }


    public function getUserId(string $url, string $access_token, bool $return = false)
    {
        if (is_numeric($url)) {
            return $url;
        }
        if (mb_strpos($url, 'https://') > -1) {
            $url = mb_substr($url, 8);
        }
        if (mb_strpos($url, 'http://') > -1) {
            $url = mb_substr($url, 7);
        }
        $parts = explode('/', $url);
        if (2 === \count($parts) && ('vk.com' === $parts[0] || 'm.vk.com' === $parts[0])) {
            if ($vk = self::getApi('https://api.vk.com/method/utils.resolveScreenName',
                [
                    'screen_name' => $parts[1],
                    'v' => '5.135',
                    'access_token' => (string)$access_token,
                ])) {
                if (isset($vk['response']['object_id'])) {
                    return $return ? $vk['response']['object_id'] : $vk['response'];
                }
            }
        }
        return 0;
    }

    public function getApi($url, $params, $returnType = 'arr', $file = false, $method = 'POST')
    {
        try {
            if ($file) {
                $formFields = [
                    'photo' => DataPart::fromPath($file),
                ];
                $formData = new FormDataPart($formFields);

                $response = $this->httpClient->request('POST', $url, [
                    'headers' => $formData->getPreparedHeaders()->toArray(),
                    'body' => $formData->bodyToIterable(),

                ]);
            } else {
                $response = $this->httpClient->request($method, $url, [
                    'body' => $params,
                    //'proxy' => 'http://:@127.0.0.1:8888',
                    //'verify_peer' => false,
                    //'verify_host' => false,
                ]);
            }
            $response->getHeaders();

            if (Response::HTTP_OK === $response->getStatusCode()) {
                if ('object' === $returnType) {
                    return $response->getContent();
                }
                return self::getParse($response->toArray(), $url, $params, $returnType = 'arr');
            }
        } catch (TransportExceptionInterface $e) {

            dump($e->getMessage());

            return false;
        } catch (ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface  $e) {
            return $e->getResponse()->toArray(false);
        } catch (DecodingExceptionInterface $e) {
            return $e->getMessage();
        }
    }

    private function getParse($response, $url, $params)
    {
        switch (key($response)) {
            case 'error':
                switch ($response['error']['error_code']) {
                    case 14:
                        copy($response['error']['captcha_img'], __DIR__ . '/../../var/captcha/image_' . $response['error']['captcha_sid'] . '.jpg');

                        return $this->getCaptcha($url, $params, $response['error']['captcha_sid']);

                    default:
                        return $response;
                }
            // no break
            default:
                return $response;
        }
    }

    private function getCaptcha($url, $params, $captcha_sid)
    {
        /** @var $captcha */
        $captcha = new Captcha($captcha_sid);
        /** @var $code */
        $code = $captcha->upload();

        switch ($code['status']) {
            case 1:
                $params['captcha_sid'] = $captcha_sid;
                $params['captcha_key'] = $code['code'];

                return $this->getApi($url, $params);

            case 3:
                return self::getCaptcha($url, $params, $captcha_sid);
        }
    }

    public static function getMessagesApi($error): array
    {
        return match ($error['error'] ?? 0) {
            self::VK_AUTH_ERROR_INVALID_CLIENT => self::throwError(72, $error),
            self::VK_AUTH_ERROR_NEED_CAPTCHA => self::throwError(73, [
                'captchaId' => (int)$error['captcha_sid'],
                'o' => $error,
            ]),
            self::VK_AUTH_ERROR_NEED_VALIDATION => self::throwError(74, [
                'validationId' => $error['validation_sid'],
                'phone' => $error['phone_mask'],
                'o' => $error,
            ]),
            default => self::throwError(69, $error),
        };
    }

    private static function getErrorTextById(int $id): string
    {
        $textErrors = [
            69 => 'Неизвестная ошибка',
            72 => '<b>Не удается войти.</b> Пожалуйста, проверьте правильность написания логина и пароля.',
            73 => '%system% NeedCaptcha',
            74 => '%system% NeedValidation',
        ];

        return $textErrors[$id];
    }

    #[ArrayShape(['errorId' => '', 'message' => 'string', 'extra' => 'array'])]
    private static function throwError($errorId, $extra = false): array
    {
        $data = [
            'errorId' => $errorId,
            'message' => self::getErrorTextById($errorId),
        ];
        if ($extra) {
            $data['extra'] = [];
            foreach ($extra as $key => $value) {
                $data['extra'][$key] = $value;
            }
        }

        return $data;
    }
}
