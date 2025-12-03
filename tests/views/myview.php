<!-- tests/views/myview.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@{{ page_title }}</title>
</head>
<body>

<h1>@{{ heading }}</h1>

<p>Escaped output: @{{ unsafe_html }}</p>
<p>Raw HTML: @raw(unsafe_html)</p>

<p>JavaScript data:</p>
<script>
    const products = @json(products);
    console.log(products);
</script>

<!-- Loop with zp-for -->
<div zp-for="$product, $index of $products">
    <div zp-if="$product['is_free'] === true">
        🎁 @{{ $product['name'] }} (Free!)
    </div>
    <div zp-else-if="$product['is_free'] === 'today'">
        ⏳ @{{ $product['name'] }} (Free today)
    </div>
    <div zp-else>
        💰 @{{ $product['name'] }} — Price: @{{ $product['price'] }}
    </div>
</div>

<!-- Raw PHP (if allowed) -->
@php( echo "<p>Rendered at: " . date('Y-m-d H:i:s') . "</p>"; )

<!-- @tojs alias for @json -->
<script>
    const tags = @tojs(tags);
    document.write('<p>Tags: ' + tags.join(', ') + '</p>');
</script>

</body>
</html>