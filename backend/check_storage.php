<?php

// Check and create storage directories
$directories = [
    'app/public',
    'app/temp',
    'framework/cache',
    'framework/sessions',
    'framework/views',
    'logs',
    'fonts'
];

$basePath = __DIR__ . '/storage/';

foreach ($directories as $dir) {
    $path = $basePath . $dir;
    if (!is_dir($path)) {
        if (mkdir($path, 0755, true)) {
            echo "Created directory: $path\n";
        } else {
            echo "Failed to create directory: $path\n";
        }
    } else {
        echo "Directory exists: $path\n";
    }
    
    // Set permissions
    if (is_dir($path)) {
        if (chmod($path, 0755)) {
            echo "Set permissions for: $path\n";
        } else {
            echo "Failed to set permissions for: $path\n";
        }
    }
}

echo "\nStorage directory check complete.\n";
