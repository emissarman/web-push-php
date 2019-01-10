<?php

declare(strict_types=1);

/*
 * This file is part of the WebPush library.
 *
 * (c) Louis Lagrange <lagrange.louis@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Minishlink\WebPush;

class Subscription
{
    /** @var string */
    private $endpoint;

    /** @var null|string */
    private $publicKey;

    /** @var null|string */
    private $authToken;

    /** @var null|string */
    private $contentEncoding;

    /** @var null|string */
    private $localKey;

    /** @var null|string */
    private $sharedSecret;

    /** @var null|string */
    private $id;

    /** @var null|string */
    private $logId;

    /**
     * Subscription constructor.
     *
     * @param string $endpoint
     * @param null|string $publicKey
     * @param null|string $authToken
     * @param int $id
     * @param int $logId
     * @param null|string $localKey
     * @param null|string $sharedSecret
     * @param string $contentEncoding (Optional) Must be "aesgcm"
     * @throws \ErrorException
     */
    public function __construct(
        string $endpoint,
        string $publicKey = null,
        string $authToken = null,
        int $id,
        int $logId,
        ?string $localKey = null,
        ?string $sharedSecret = null,
        ?string $contentEncoding = 'aesgcm'
    )
    {
        $this->endpoint = $endpoint;

        if ($publicKey || $authToken || $contentEncoding) {
            $supportedContentEncodings = ['aesgcm', 'aes128gcm'];
            if ($contentEncoding && !in_array($contentEncoding, $supportedContentEncodings)) {
                throw new \ErrorException('This content encoding (' . $contentEncoding . ') is not supported.');
            }

            $this->id = $id;
            $this->logId = $logId;
            $this->publicKey = $publicKey;
            $this->authToken = $authToken;
            $this->contentEncoding = $contentEncoding ?: "aesgcm";

            $this->localKey = ($localKey);
            $this->sharedSecret = ($sharedSecret);
        }
    }

    /**
     * Subscription factory.
     *
     * @param array $associativeArray (with keys endpoint, publicKey, authToken, contentEncoding)
     * @return Subscription
     * @throws \ErrorException
     */
    public static function create(array $associativeArray): Subscription
    {
        if (array_key_exists('publicKey', $associativeArray) || array_key_exists('authToken', $associativeArray) || array_key_exists('contentEncoding', $associativeArray)) {
            return new self(
                $associativeArray['endpoint'],
                $associativeArray['publicKey'] ?? null,
                $associativeArray['authToken'] ?? null,
                $associativeArray['contentEncoding'] ?? "aesgcm"
            );
        }

        if (array_key_exists('keys', $associativeArray) && is_array($associativeArray['keys'])) {
            return new self(
                $associativeArray['endpoint'],
                $associativeArray['keys']['p256dh'] ?? null,
                $associativeArray['keys']['auth'] ?? null,
                $associativeArray['contentEncoding'] ?? "aesgcm"
            );
        }

        return new self(
            $associativeArray['endpoint']
        );
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * @return null|string
     */
    public function getPublicKey(): ?string
    {
        return $this->publicKey;
    }

    /**
     * @return null|string
     */
    public function getAuthToken(): ?string
    {
        return $this->authToken;
    }

    /**
     * @return null|string
     */
    public function getContentEncoding(): ?string
    {
        return $this->contentEncoding;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLogId()
    {
        return $this->id;
    }

    public function getLocalKey()
    {
        return $this->localKey;
    }

    public function getSharedSecret()
    {
        return $this->sharedSecret;
    }
}
