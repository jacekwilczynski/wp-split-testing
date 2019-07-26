<?php

declare(strict_types=1);

namespace MobilitySoft\SplitTesting;

class SplitTestPlugin
{
    /**
     * @var SplitTestManager
     */
    private $splitTestManager;

    public function __construct(SplitTestManager $splitTestManager)
    {
        $this->splitTestManager = $splitTestManager;
    }

    public function run(): void
    {
        add_shortcode('split_test', function (array $atts) {
            $key         = $atts['key'];
            $variationId = $this->splitTestManager->getVariationId($key);

            return $atts[$variationId] ?? '';
        });
    }
}
