<?php

namespace App\Console\Commands\Hr;

use Illuminate\Support\Str;
use App\Models\HR\Application;
use Illuminate\Console\Command;

class MoveFilesToWordPress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hr:move-resume-to-website';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $applications = Application::where('resume', 'LIKE', '/storage/resume%')->limit(50)
        ->get();

        foreach ($applications as $application) {
            $sourcePath = public_path($application->resume);
            $websitePath = config('constants.website_upload_dir') . '/' . date('Y') . '/' . date('m') . '/';

            if (!is_dir($websitePath)) {
                mkdir($websitePath);
            }

            $d = exec("cp $sourcePath $websitePath 2>&1");

            if ($d) {
                continue;
            }

            $newFilePath = Str::of(config('constants.website_url'))
                                ->finish('/')
                                ->append('wp-content/uploads/')
                                ->append(str_replace('/storage/resume/', '', $application->resume));

            $application->update(['resume' => $newFilePath]);
        }
    }
}