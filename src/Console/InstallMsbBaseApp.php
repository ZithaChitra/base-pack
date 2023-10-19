<?php

namespace BasePack\Console;

use Illuminate\Console\Command;

class InstallMsbBaseApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'msbbase:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migrations, publish assets and config, for the MSB Base App';

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
        $this->info('MSB Base App Installing.');
        
        $completedDbSeed = false;

        try {
            $this->call('migrate',[
                '--seed'   => true,
                '--seeder' => 'Database\Seeders\BasePackTableSeeders'

            ]);
            $completedDbSeed = true;

        } catch (\Throwable $th) {
            $this->error('MSB-BASE-APP: An error occurred. Did not finish running database migration and seeder steps');
            $this->error('MSB-BASE-APP: It could be that the database connection is not correctly configured in the .env file');
        }


        try{
            if($completedDbSeed) $this->call('contexts:update');
            
        }catch(\Throwable $th){
            $this->error('MSB-BASE-APP: An error occurred. Could not update contexts');
            $this->error('MSB-BASE-APP: Please double check the following');
            $this->error('\t 1. That the API server is reachable.');
            $this->error('\t 2. That you\'re using the correct URL for the context.json e.g apiUrl/.well-known/context.json');
            // $this->error('\t 1. ');
        }

        try {
            $this->call('vendor:publish', [
                '--provider' => 'BasePack\BasePackServiceProvider',
                '--tag' => 'assets'
            ]);

        } catch (\Throwable $th) {
            $this->error('MSB-BASE-APP: An error occurred while publishing assets.');
        }


        try {
            $this->call('vendor:publish', [
                '--provider' => 'BasePack\BasePackServiceProvider',
                '--tag'      => 'config'
            ]);

        } catch (\Throwable $th) {
            $this->error('MSB-BASE-APP: An error occurred while publishing config file.');
        }


        $this->info('MSB-BASE-APP: Done!');
    }
}
