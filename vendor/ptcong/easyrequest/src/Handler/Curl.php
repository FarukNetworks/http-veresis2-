<?php

namespace EasyRequest\Handler;

use EasyRequest\Client;
use EasyRequest\Psr7\Response;
use Exception;
use Psr\Http\Message\RequestInterface;

class Curl implements HandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function send(RequestInterface $request, array $options = array())
    {
        $options += Client::$defaultOptions;

        $curlOptions = $options['curl'] + array(
            CURLOPT_URL            => (string) $request->getUri(),
            CURLOPT_CUSTOMREQUEST  => $request->getMethod(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER     => \EasyRequest\get_headers($request),
            CURLOPT_ENCODING       => $request->getHeaderLine('Accept-Encoding'),
            CURLOPT_NOBODY         => $options['nobody'],
            CURLOPT_CONNECTTIMEOUT => $options['timeout'],
            CURLOPT_HTTP_VERSION   => $request->getProtocolVersion() == '1.0'
                                        ? CURL_HTTP_VERSION_1_0
                                        : CURL_HTTP_VERSION_1_1,
        );

        if ($options['upload']) {
            $body = $request->getBody();
            $curlOptions += array(
                CURLOPT_UPLOAD       => true,
                CURLOPT_READFUNCTION => function ($ch, $fp, $length) use ($body) {
                    return $body->read($length);
                }
            );
        } elseif ($options['body']) {
            $curlOptions[CURLOPT_POSTFIELDS] = (string) $request->getBody();
        }

        if ($options['proxy']) {
            $curlOptions += array(
                CURLOPT_PROXY     => $options['proxy'],
                CURLOPT_PROXYTYPE => $options['proxy_type']
            );
            if ($options['proxy_userpwd']) {
                $curlOptions[CURLOPT_PROXYUSERPWD] = $options['proxy_userpwd'];
            }
        }

        if ($options['bindto']) {
            $curlOptions[CURLOPT_INTERFACE] = $options['bindto'];
        }

        $ch = curl_init();
        curl_setopt_array($ch, $curlOptions);
        $result = curl_exec($ch);

        if ($result === false) {
            throw new Exception(sprintf('%d - %s', curl_errno($ch), curl_error($ch)));
        }

        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        curl_close($ch);

        $header = substr($result, 0, $headerSize);
        $body = substr($result, $headerSize);

        return Response::parse($header, $body);
    }
}
