<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\ReportController;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GenerateLoadTestExports extends Command
{
    protected $signature = 'loadtest:export {--count=2000}';
    protected $description = 'Seed a large dataset and generate CSV and PDF exports for the load test user';

    public function handle(): int
    {
        $this->info('Running LargeTaskDatasetSeeder...');
        Artisan::call('db:seed', [
            '--class' => 'Database\\Seeders\\LargeTaskDatasetSeeder',
            '--force' => true,
        ]);

        $user = User::where('email', 'loadtest@example.com')->first();
        if (! $user) {
            $this->error('Load test user not found after seeding.');
            return 1;
        }

        // Temporarily authenticate as the user so ReportController uses Auth::user()
        Auth::login($user);

        $controller = new ReportController();

        // Common request data (no assignee filter so exports will include user's tasks)
        $request = Request::create('/reports/export', 'GET', [
            'format' => 'csv'
        ]);

    // Generate CSV by calling controller method and capturing the streamed response
    $response = $controller->exportTaskReport($request);

        $now = Carbon::now()->format('Y-m-d-H-i-s');
        $dir = 'exports';
        Storage::makeDirectory($dir);

        // If response is a StreamedResponse, capture output to storage file
        $csvPath = $dir . '/task-report-' . $now . '.csv';
        ob_start();
        $response->send();
        $content = ob_get_clean();
        Storage::put($csvPath, $content);

        $this->info("CSV export saved to storage/app/{$csvPath}");

        // Now request PDF but limit rows to avoid excessive memory usage
        // Increase PHP memory limit temporarily
        ini_set('memory_limit', '1024M');

        $requestPdf = Request::create('/reports/export', 'GET', [
            'format' => 'pdf',
            'limit' => 200,
        ]);

        $responsePdf = $controller->exportTaskReport($requestPdf);

        // If it's a binary download response, capture content
        ob_start();
        $responsePdf->send();
        $pdfContent = ob_get_clean();
        $pdfPath = $dir . '/task-report-' . $now . '.pdf';
        Storage::put($pdfPath, $pdfContent);

        $this->info("PDF export saved to storage/app/{$pdfPath}");

        // Logout
        Auth::logout();

        return 0;
    }
}
