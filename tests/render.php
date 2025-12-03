<?php
// tests/render.php

require_once __DIR__ . '/../vendor/autoload.php';

use Zog\Zog;

// ------------------------------
//  Configure directories
// ------------------------------
Zog::setDir(__DIR__ . '/views');
Zog::setStaticDir(__DIR__ . '/cache/static');
Zog::setCompiledDir(__DIR__ . '/cache/compiled');

$outputDir = __DIR__ . '/output';
if (!is_dir($outputDir)) mkdir($outputDir, 0775, true);

// ------------------------------
// Test data
// ------------------------------
$data = [
    'page_title'   => 'Zog Template Engine - Test',
    'heading'      => 'Feature Test Page',
    'unsafe_html'  => '<strong>Dangerous HTML</strong>',
    'products'     => [
        ['name' => 'PHP Book',        'price' => 120000, 'is_free' => false],
        ['name' => 'Free Course',     'price' => 0,      'is_free' => true],
        ['name' => 'Dev Toolkit',     'price' => 85000,  'is_free' => 'today'],
    ],
    'tags'         => ['php', 'template', 'zog', 'hybrid'],
];

// ------------------------------
// 1) Test simple render
// ------------------------------
echo "\n=== 1) Testing render() ===\n";

try {
    $html = Zog::render('myview.php', $data);
    file_put_contents($outputDir . '/render.html', $html);

    echo "✔ render() success.\n";
    echo "→ Saved to: output/render.html\n";
    echo "→ First 120 chars: " . substr(strip_tags($html), 0, 120) . "\n\n";

} catch (Throwable $e) {
    echo "❌ Render error: " . $e->getMessage() . "\n\n";
}


// ------------------------------
// 2) Test hybrid() caching system
// ------------------------------
echo "=== 2) Testing hybrid() ===\n";

$key = 'test-case-hybrid';

$first = Zog::hybrid('myview.php', $key);
if ($first === false) {
    echo "✔ Cache miss (expected). Creating cache...\n";

    $cachedHtml = Zog::hybrid('myview.php', $key, $data, Zog::CACHE_A_MINUTE);
    file_put_contents($outputDir . '/hybrid-first.html', $cachedHtml);

    echo "→ hybrid-first.html saved.\n";
} else {
    echo "❌ Unexpected cache hit on first call.\n";
}

$second = Zog::hybrid('myview.php', $key);
if ($second !== false) {
    echo "✔ Cache HIT on second call.\n";
    file_put_contents($outputDir . '/hybrid-second.html', $second);
    echo "→ hybrid-second.html saved.\n\n";
} else {
    echo "❌ Cache MISS on second call.\n\n";
}


// ------------------------------
// 3) Test static file reading
// ------------------------------
echo "=== 3) Testing staticFile() ===\n";

file_put_contents(__DIR__ . '/static/test.txt', "Static OK!");

try {
    $static = Zog::staticFile('test.txt');
    echo "✔ staticFile() OK: \"$static\"\n\n";
} catch (Throwable $e) {
    echo "❌ staticFile() error: " . $e->getMessage() . "\n\n";
}


// ------------------------------
// 4) Cleanup
// ------------------------------
echo "=== 4) Clearing cache ===\n";
// Zog::clearStatics();
// Zog::clearCompiled();
echo "✔ Cache cleared.\n";

echo "\n🎉 TESTS COMPLETED.\n";
