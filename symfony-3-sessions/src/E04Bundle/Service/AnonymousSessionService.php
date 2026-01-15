<?php

namespace App\E04Bundle\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class AnonymousSessionService
{
    private const SESSION_NAME_KEY = 'anonymous_name';
    private const SESSION_CREATED_AT_KEY = 'anonymous_created_at';
    private const SESSION_LAST_REQUEST_KEY = 'anonymous_last_request';
    private const SESSION_DURATION = 60;

    private array $animals = [
        'dog', 'cat', 'lion', 'tiger', 'bear',
        'fox', 'wolf', 'eagle', 'shark', 'panda'
    ];

    public function __construct(
        private RequestStack $requestStack
    ) {}

    public function handle(): array
    {
        $session = $this->requestStack->getSession();
        $now = time();

        if (
            !$session->has(self::SESSION_NAME_KEY) ||
            ($now - $session->get(self::SESSION_CREATED_AT_KEY)) >= self::SESSION_DURATION
        ) {
            $animal = $this->animals[array_rand($this->animals)];

            $session->set(self::SESSION_NAME_KEY, 'Anonymous ' . $animal);
            $session->set(self::SESSION_CREATED_AT_KEY, $now);
            $session->set(self::SESSION_LAST_REQUEST_KEY, $now);
        }

        $createdAt = $session->get(self::SESSION_CREATED_AT_KEY);
        $lastRequest = $session->get(self::SESSION_LAST_REQUEST_KEY);

        $session->set(self::SESSION_LAST_REQUEST_KEY, $now);

        return [
            'name' => $session->get(self::SESSION_NAME_KEY),
            'seconds_since_last_request' => $now - $lastRequest,
            'seconds_remaining' => max(
                0,
                self::SESSION_DURATION - ($now - $createdAt)
            ),
        ];
    }
}