<?php

declare(strict_types=1);

namespace MobilitySoft\SplitTesting;

class SplitTestManager
{
    /**
     * @var array
     */
    private $choices = [];

    /**
     * @var string
     */
    private $cookiePrefix;

    /**
     * @var string
     */
    private $requestParamPrefix;

    public function __construct(?string $requestParamPrefix = 'st_', ?string $cookiePrefix = 'st_')
    {
        $this->requestParamPrefix = $requestParamPrefix;
        $this->cookiePrefix       = $cookiePrefix;
    }

    /**
     * @param int|string $testId
     *
     * @return int|string
     */
    public function getVariationId($testId)
    {
        if ( ! array_key_exists($testId, $this->choices)) {
            $this->initializeTest($testId);
        }

        return $this->choices[$testId];
    }

    /**
     * @param int|string $testId
     */
    private function initializeTest($testId): void
    {
        $variationId = $this->chooseVariationId($testId);
        $this->persistChoice($testId, $variationId);
        $this->choices[$testId] = $variationId;
    }

    /**
     * @param int|string $testId
     *
     * @return int|string
     */
    private function chooseVariationId($testId)
    {
        if (isset($_GET[$this->requestParamPrefix . $testId])) {
            return htmlspecialchars($_GET[$this->requestParamPrefix . $testId]);
        } elseif (isset($_COOKIE[$this->cookiePrefix . $testId])) {
            return htmlspecialchars($_COOKIE[$this->cookiePrefix . $testId]);
        } else {
            return 1;
        }
    }

    /**
     * @param int|string $testId
     * @param int|string $variationId
     */
    private function persistChoice($testId, $variationId): void
    {
        setcookie(
            $this->cookiePrefix . $testId,
            (string)$variationId,
            time() + 60 * 60 * 24 * 365
        );
    }
}
