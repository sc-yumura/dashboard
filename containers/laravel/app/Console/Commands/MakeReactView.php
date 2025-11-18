<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeReactView extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:react-view {controller} {function*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new React view file. Script placed app/Console/Commands folder.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $controller = $this->argument('controller');
        $functions = $this->argument('function');
        // echo "Controller: $controller\n";
        // $functions_str = implode(', ', $functions);
        // echo "Functions: $functions_str\n";

        if(str_contains($controller, 'Controller')) {
            $controller = str_replace('Controller', '', $controller);
        }

        $controller = strtolower($controller);

        if(str_contains($controller, '/')) {
            $parts = explode('/', $controller);
            $controllerName = array_pop($parts);
            $path = 'resources/js/pages/' . implode('/', $parts) . '/';
        } else {
            $controllerName = $controller;
            $path = 'resources/js/pages/';
        }
        $folderPath = $path . $controllerName . '/';

        mkdir($folderPath, 0755, true);

        foreach ($functions as $function) {
            $filePath = $folderPath . $function . '.tsx';
            $componentName = ucfirst($function);

            # TODO: レイアウト選べたらいいよね
            $fileContent = <<<EOT
import { type BreadcrumbItem, type SharedData } from '@/types';
import { usePage } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Example',
        href: 'example.com',
    },
];

export default function $componentName({
}: {
}) {
    const { auth } = usePage<SharedData>().props;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
        </AppLayout>
    );
}

EOT;
            file_put_contents($filePath, $fileContent);
            $this->info("Created: " . $filePath);
        }

        $this->info('React view files created successfully.');
    }
}
