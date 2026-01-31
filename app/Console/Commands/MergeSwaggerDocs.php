<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Yaml\Yaml;

class MergeSwaggerDocs extends Command
{
    protected $signature = 'api:merge-docs';
    protected $description = 'Merge all module API YAML files into main docs/api.yaml';

    public function handle(): int
    {
        $this->info('Merging module API documentation...');

        // Main API spec template
        $mainSpec = [
            'openapi' => '3.0.0',
            'info' => [
                'title' => 'Electronic E-commerce API',
                'description' => implode("\n", [
                    'Complete API documentation for Yejara Electronic E-commerce platform.',
                    '',
                    '## Features',
                    '- User authentication with JWT tokens',
                    '- Product catalog with AI-generated descriptions',
                    '- Shopping cart and order management',
                    '- Store branch locations',
                    '',
                    '## Architecture',
                    'This API follows a modular monolith architecture where each module is self-contained.',
                    '',
                    '## Base URL',
                    '- Development: `http://localhost`',
                    '- Production: `https://api.yejara.com`',
                ]),
                'version' => '1.0.0',
                'contact' => [
                    'name' => 'Yejara API Support',
                    'email' => 'api@yejara.com',
                ],
                'license' => [
                    'name' => 'MIT',
                    'url' => 'https://opensource.org/licenses/MIT',
                ],
            ],
            'servers' => [
                [
                    'url' => 'http://localhost',
                    'description' => 'Docker development server (port 80)',
                ],
                [
                    'url' => 'http://localhost:8000',
                    'description' => 'Local development server (php artisan serve)',
                ],
                [
                    'url' => 'https://api.yejara.com',
                    'description' => 'Production server',
                ],
            ],
            'paths' => [],
            'components' => [
                'schemas' => [
                    'StandardResponse' => [
                        'type' => 'object',
                        'properties' => [
                            'success' => [
                                'type' => 'boolean',
                                'example' => true,
                                'description' => 'Indicates if the request was successful',
                            ],
                            'data' => [
                                'type' => 'object',
                                'nullable' => true,
                                'description' => 'Response data (varies by endpoint)',
                            ],
                            'message' => [
                                'type' => 'string',
                                'example' => 'OK',
                                'description' => 'Human-readable message',
                            ],
                            'code' => [
                                'type' => 'integer',
                                'example' => 200,
                                'description' => 'HTTP status code',
                            ],
                        ],
                    ],
                ],
                'responses' => [
                    'Unauthenticated' => [
                        'description' => 'Unauthenticated - User must be logged in',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/StandardResponse',
                                ],
                                'examples' => [
                                    'error' => [
                                        'value' => [
                                            'success' => false,
                                            'data' => null,
                                            'message' => 'Unauthenticated',
                                            'code' => 401,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'ValidationError' => [
                        'description' => 'Validation error - Request data is invalid',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'allOf' => [
                                        ['$ref' => '#/components/schemas/StandardResponse'],
                                        [
                                            'type' => 'object',
                                            'properties' => [
                                                'data' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'errors' => [
                                                            'type' => 'object',
                                                            'additionalProperties' => [
                                                                'type' => 'array',
                                                                'items' => ['type' => 'string'],
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                                'examples' => [
                                    'error' => [
                                        'value' => [
                                            'success' => false,
                                            'data' => [
                                                'errors' => [
                                                    'field_name' => ['The field name is required.'],
                                                ],
                                            ],
                                            'message' => 'Validation failed',
                                            'code' => 422,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'securitySchemes' => [
                    'bearerAuth' => [
                        'type' => 'http',
                        'scheme' => 'bearer',
                        'bearerFormat' => 'JWT',
                        'description' => 'JWT token from /api/auth/login or /api/auth/refresh',
                    ],
                ],
            ],
            'tags' => [],
            'externalDocs' => [
                'description' => 'Find more info in the project README',
                'url' => 'https://github.com/technexus/api/blob/main/README.md',
            ],
        ];

        // Find all module API YAML files
        $modulesPath = base_path('modules');
        $moduleFiles = [];

        if (is_dir($modulesPath)) {
            $modules = scandir($modulesPath);
            foreach ($modules as $module) {
                if ($module === '.' || $module === '..') {
                    continue;
                }

                $apiYamlPath = "{$modulesPath}/{$module}/docs/api.yaml";
                if (file_exists($apiYamlPath)) {
                    $moduleFiles[$module] = $apiYamlPath;
                    $this->line("  Found: {$module}/docs/api.yaml");
                }
            }
        }

        if (empty($moduleFiles)) {
            $this->warn('No module API YAML files found');
            return self::FAILURE;
        }

        // Merge module specs
        foreach ($moduleFiles as $moduleName => $filePath) {
            $this->info("  Merging {$moduleName} module...");
            
            try {
                $moduleSpec = Yaml::parseFile($filePath);

                // Merge paths
                if (isset($moduleSpec['paths'])) {
                    $mainSpec['paths'] = array_merge($mainSpec['paths'], $moduleSpec['paths']);
                }

                // Merge schemas
                if (isset($moduleSpec['components']['schemas'])) {
                    $mainSpec['components']['schemas'] = array_merge(
                        $mainSpec['components']['schemas'],
                        $moduleSpec['components']['schemas']
                    );
                }

                // Merge responses
                if (isset($moduleSpec['components']['responses'])) {
                    $mainSpec['components']['responses'] = array_merge(
                        $mainSpec['components']['responses'],
                        $moduleSpec['components']['responses']
                    );
                }

                // Merge security schemes
                if (isset($moduleSpec['components']['securitySchemes'])) {
                    $mainSpec['components']['securitySchemes'] = array_merge(
                        $mainSpec['components']['securitySchemes'],
                        $moduleSpec['components']['securitySchemes']
                    );
                }

                // Merge tags
                if (isset($moduleSpec['tags'])) {
                    $mainSpec['tags'] = array_merge($mainSpec['tags'], $moduleSpec['tags']);
                }

            } catch (\Exception $e) {
                $this->error("  Error merging {$moduleName}: {$e->getMessage()}");
                return self::FAILURE;
            }
        }

        // Write merged spec to main api.yaml
        $outputPath = base_path('docs/api.yaml');
        
        try {
            $yamlContent = Yaml::dump($mainSpec, 10, 2, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK);
            file_put_contents($outputPath, $yamlContent);

            $this->newLine();
            $this->info('✓ Successfully merged API documentation');
            $this->line("  Output: {$outputPath}");
            $this->newLine();
            $this->info('Next step: Run "php artisan api:generate-docs" to generate Swagger UI');

            return self::SUCCESS;

        } catch (\Exception $e) {
            $this->error("Error writing merged file: {$e->getMessage()}");
            return self::FAILURE;
        }
    }
}
