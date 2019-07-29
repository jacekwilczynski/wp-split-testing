<?php
/**
 * Plugin Name: Split Testing
 * Plugin Author: Mobility Soft
 */

use MobilitySoft\SplitTesting\SplitTestManager;
use MobilitySoft\SplitTesting\SplitTestPlugin;

require_once __DIR__ . '/src/SplitTestPlugin.php';
require_once __DIR__ . '/src/SplitTestManager.php';

$splitTestManager = new SplitTestManager(
    apply_filters('split_test_request_param_prefix', 'st_'),
    apply_filters('split_test_cookie_prefix', 'st_')
);

(new SplitTestPlugin($splitTestManager))->run();
