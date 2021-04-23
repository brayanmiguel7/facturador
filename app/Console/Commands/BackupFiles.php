<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Zip;

class BackupFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bk:files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup storage tenants';

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
        try {
            $today = now()->format('dmYHi');
            $path_sql = now()->format('dmY');
            if (!is_dir(storage_path('app/backups'))) mkdir(storage_path('app/backups'));
            if (!is_dir(storage_path('app/backups/zip'))) mkdir(storage_path('app/backups/zip'));
            $zip = Zip::create(storage_path('app/backups/zip/'.$today.'_storage.zip'));
            $zip->add(storage_path('app/tenancy/tenants/'), true);
            $zip->add(storage_path('app/backups/'.$path_sql.'/'), true);

            Log::info('Backup storage success');
        } catch (Throwable $e) {
            Log::error('Backup failed', $e);
        }
    }
}
