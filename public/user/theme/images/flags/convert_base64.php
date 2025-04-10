<?php
// Convert base64 files to PNG images

// English flag
$enBase64 = trim(file_get_contents(__DIR__ . '/en.png.base64'));
file_put_contents(__DIR__ . '/en.png', base64_decode($enBase64));
echo "Created en.png\n";

// Vietnamese flag
$viBase64 = trim(file_get_contents(__DIR__ . '/vi.png.base64'));
file_put_contents(__DIR__ . '/vi.png', base64_decode($viBase64));
echo "Created vi.png\n";

echo "Conversion complete!\n";
?> 