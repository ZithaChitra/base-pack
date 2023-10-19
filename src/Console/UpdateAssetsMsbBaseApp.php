<?php

namespace BasePack\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
use Illuminate\Filesystem\Filesystem;

class UpdateAssetsMsbBaseApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'msbbase:update-assets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Re-publish assets. (deletes previously published assets)';

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
     * @return int
     */
    public function handle()
    {
        $this->info('MSB-Base-App:  Updating Assets');

        $viewsF = resource_path('views/vendor/adminlte');
        try {
            $exists = is_dir($viewsF);

            if($exists){
                $filesystem = new Filesystem;
                $filesystem->deleteDirectory($viewsF);
                $this->info('MSB-BASE-APP: removed old assets folder');
            }
        } catch (\Throwable $th) {
            $this->error('MSB-BASE-APP: An error occurred while deleting old assets folder');
            echo($th);
        }


        try {
            $this->call('vendor:publish', [
                '--provider' => 'BasePack\BasePackServiceProvider',
                '--tag'      => 'assets'
            ]);

        } catch (\Throwable $th) {
            $this->error('MSB-BASE-APP: An error occurred while publishing assets.');
        }



        $this->info('Done');

    }

}