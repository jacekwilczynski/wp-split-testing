<?php

declare(strict_types=1);

namespace MobilitySoft\SplitTesting;

class SplitTestManager
{
    const COOKIE_PREFIX = 'split_test_';

    /**
     * @var array
     */
    private $choices = [];

    /**
     * @var string
     */
    private $requestParamPrefix;

    public function __construct(?string $requestParamPrefix = '')
    {
        $this->requestParamPrefix = $requestParamPrefix;
    }

    public function getVariationId($testId)
    {
        if ( ! array_key_exists($testId, $this->choices)) {
            $this->initializeTest($testId);
        }

        return $this->choices[$testId];
    }

    private function initializeTest($testId): void
    {
        $variationId = $this->chooseVariationId($testId);
        $this->persistChoice($testId, $variationId);
        $this->choices[$testId] = $variationId;
    }

    private function chooseVariationId($testId)
    {
        if (isset($_GET[$this->requestParamPrefix . $testId])) {
            return htmlspecialchars($_GET[$this->requestParamPrefix . $testId]);
        } elseif (isset($_COOKIE[self::COOKIE_PREFIX . $testId])) {
            return htmlspecialchars($_COOKIE[self::COOKIE_PREFIX . $testId]);
        } else {
            return 1;
        }
    }

    private function persistChoice($testId, $variationId)
    {
        setcookie(
            self::COOKIE_PREFIX . $testId,
            (string)$variationId,
            time() + 60 * 60 * 24 * 365
        );
    }
}
