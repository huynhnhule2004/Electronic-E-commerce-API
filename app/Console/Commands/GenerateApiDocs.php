<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Yaml\Yaml;

class GenerateApiDocs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:generate-docs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate API documentation from YAML files';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $yamlFile = base_path('docs/api.yaml');
        $jsonFile = storage_path('api-docs/api-docs.json');
        $yamlCopyFile = storage_path('api-docs/api-docs.yaml');

        if (!file_exists($yamlFile)) {
            $this->error("Error: {$yamlFile} not found");
            return self::FAILURE;
        }

        try {
            $this->info('Generating API documentation...');

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

            $this->info('✓ Successfully generated API documentation:');
            $this->line("  - {$jsonFile}");
            $this->line("  - {$yamlCopyFile}");
            $this->newLine();
            $this->info('View at: http://localhost/api/documentation');

            return self::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
