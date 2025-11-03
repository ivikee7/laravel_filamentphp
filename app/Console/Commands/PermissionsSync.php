<?php

namespace App\Console\Commands;

use App\Models\Permission;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;
use App\Providers\AuthServiceProvider;
use Symfony\Component\Finder\Finder;

class PermissionsSync extends Command
{
    protected $signature = 'app:permissions-sync {--clean : Delete old permissions without asking for confirmation}';
    protected $description = 'Generate and synchronize permissions based on application policies.';

    public function handle()
    {
        $this->info('Starting permission synchronization...');

        $permissionsToSync = [];
        $policies = $this->discoverPolicies();

        foreach ($policies as $policyClass) {
            $reflection = new ReflectionClass($policyClass);
            $policyMethods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
            $modelClass = $this->getModelClassFromPolicy($policyClass);

            if (!$modelClass) {
                $this->warn("Skipping policy '{$policyClass}' as no matching model was found.");
                continue;
            }
            $modelName = class_basename($modelClass);

            foreach ($policyMethods as $method) {
                if (in_array($method->getName(), ['__construct', 'before', 'after'])) {
                    continue;
                }

                if ($method->getNumberOfParameters() < 1) {
                    continue;
                }

                $permissionsToSync[] = Str::kebab($method->getName()) . ' ' . $modelName;
            }
        }

        $existingPermissions = Permission::pluck('name')->toArray();
        $newPermissions = array_diff($permissionsToSync, $existingPermissions);
        $oldPermissions = array_diff($existingPermissions, $permissionsToSync);

        // Create new permissions
        if (!empty($newPermissions)) {
            foreach ($newPermissions as $permissionName) {
                Permission::findOrCreate($permissionName);
            }
            $this->info("Created new permissions: " . implode(', ', $newPermissions));
        } else {
            $this->info("No new permissions to create.");
        }

        // Delete old permissions
        if (!empty($oldPermissions)) {
            $this->warn("The following permissions are no longer in your policies and will be deleted:");
            $this->warn(implode(', ', $oldPermissions));

            if ($this->option('clean') || $this->confirm('Do you want to continue with the deletion?')) {
                Permission::whereIn('name', $oldPermissions)->get()->each->delete();
                $this->warn('Old permissions deleted.');
            } else {
                $this->info('No old permissions were deleted.');
            }
        } else {
            $this->info("No old permissions to delete.");
        }

        $this->info('Permissions synchronized successfully!');
    }

    protected function discoverPolicies(): array
    {
        $policiesPath = app_path('Policies');
        $policies = [];

        if (!is_dir($policiesPath)) {
            return $policies;
        }

        $finder = new Finder();
        foreach ($finder->files()->in($policiesPath) as $file) {
            $class = 'App\\Policies\\' . Str::before($file->getFilename(), '.php');
            if (class_exists($class)) {
                $policies[] = $class;
            }
        }
        return $policies;
    }

    protected function getModelClassFromPolicy(string $policyClass): ?string
    {
        $policyName = class_basename($policyClass);
        $modelName = Str::before($policyName, 'Policy');
        $modelClass = "App\\Models\\{$modelName}";

        if (class_exists($modelClass)) {
            return $modelClass;
        }

        // Handle namespaced models
        $reflection = new ReflectionClass($policyClass);
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if ($method->getNumberOfParameters() > 1) {
                $parameter = $method->getParameters()[1];
                if ($parameter->hasType() && !$parameter->getType()->isBuiltin()) {
                    return $parameter->getType()->getName();
                }
            }
        }

        return null;
    }
}
