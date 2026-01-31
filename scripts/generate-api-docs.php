#!/usr/bin/env php
<?php

/**
 * Convert YAML API documentation to JSON format for Swagger UI
 * 
 * This script reads the main api.yaml file and converts it to JSON
 * for use with Swagger UI.
 */

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

$yamlFile = __DIR__ . '/docs/api.yaml';
$jsonFile = __DIR__ . '/storage/api-docs/api-docs.json';
$yamlCopyFile = __DIR__ . '/storage/api-docs/api-docs.yaml';

if (!file_exists($yamlFile)) {
    echo "Error: {$yamlFile} not found\n";
    exit(1);
}

try {
    // Read YAML file
    $yamlContent = file_get_contents($yamlFile);
    
    // Parse YAML to array
    $data = Yaml::parse($yamlContent);
    
    // Convert to JSON
    $jsonContent = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    
    // Ensure output directory exists
    $outputDir = dirname($jsonFile);
    if (!is_dir($outputDir)) {
        mkdir($outputDir, 0755, true);
    }
    
    // Write JSON file
    file_put_contents($jsonFile, $jsonContent);
    
    // Copy YAML to output directory
    copy($yamlFile, $yamlCopyFile);
    
    echo "✓ Successfully generated API documentation:\n";
    echo "  - {$jsonFile}\n";
    echo "  - {$yamlCopyFile}\n";
    echo "\nView at: http://localhost:8080/api/documentation\n";
    
    exit(0);
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
