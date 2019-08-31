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
        add_shortcode('split_test', function (array $atts, ?string $content = '') {
            $activeVariationId = $this->splitTestManager->getVariationId($atts['id'] ?? $atts['key']);

            if (isset($atts['variation'])) {
                return $atts['variation'] == $activeVariationId ? $content : '';
            }

            if (isset($atts['variations'])) {
                $variations = explode(',', $atts['variations']);

                return in_array($activeVariationId, $variations)
                    ? $content
                    : '';
            }

            return $atts[$activeVariationId] ?? '';
        });
    }
}
