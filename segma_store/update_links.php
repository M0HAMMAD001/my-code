<?php
// Function to update links in a file
function updateFileLinks($filename) {
    if (!file_exists($filename)) {
        echo "File not found: $filename\n";
        return;
    }

    $content = file_get_contents($filename);
    
    // Update products.html to products.php
    $content = str_replace('href="products.html"', 'href="products.php"', $content);
    
    // Update category links
    $content = str_replace('href="core-systems.html"', 'href="core-systems.php"', $content);
    $content = str_replace('href="visuals-hub.html"', 'href="visuals-hub.php"', $content);
    $content = str_replace('href="control-center.html"', 'href="control-center.php"', $content);
    $content = str_replace('href="sound-zone.html"', 'href="sound-zone.php"', $content);
    $content = str_replace('href="comfort-corner.html"', 'href="comfort-corner.php"', $content);
    $content = str_replace('href="gear-up.html"', 'href="gear-up.php"', $content);
    
    // Write the updated content back to the file
    file_put_contents($filename, $content);
    echo "Updated: $filename\n";
}

// List of files to update
$files = [
    'index.html',
    'gear-up.html',
    'terms.html',
    'admin.html',
    'sound-zone.html',
    'wishlist.html',
    'register.html',
    'comfort-corner.html',
    'checkout.html',
    'core-systems.html',
    'control-center.html',
    'visuals-hub.html',
    'cart.html',
    'admin.php'
];

// Update each file
foreach ($files as $file) {
    updateFileLinks($file);
}

echo "All files have been updated successfully!\n";
?> 