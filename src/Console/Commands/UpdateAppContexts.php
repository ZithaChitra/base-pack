<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Storage;
use App\Models\Context;

class UpdateAppContexts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contexts:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get app contexts from the sim-api and update the contexts table in local database and update context.json file';

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
        try{
            $response = (new Client)->request('get', env('CONTEXT_FILE_ENDPOINT'));
            
            $json = $response->getBody()->getContents();
            $body = json_decode($json);

            $contexts = [];
            $roles = [];

            if(property_exists($body, 'contexts')){
                $contexts = $body->contexts;
            }

            echo("Contexts count: " . count($contexts) . PHP_EOL);



            // updates the context.json file
            // TODO: import context.json file from SIM-API 
            if(Storage::disk('public_uploads')->exists('/.well-known/context.json')){ // file does not exist
                echo("File exists" . PHP_EOL . "reading and updating file" . PHP_EOL);
                $prev = Storage::disk('public_uploads')->get('/.well-known/context.json');
                $prev = json_decode($prev);
                if(property_exists($prev, 'roles')){
                    $roles = $prev->roles;
                }else{
                    echo("Roles not defined in existing context.json file" . PHP_EOL);
                }

                $fileData = json_encode(['roles' => $roles, 'contexts' => $contexts,]);
                $saved = Storage::disk('public_uploads')->put('/.well-known/context.json', $fileData);
                if($saved){
                    echo("File saved" . PHP_EOL);
                }else{
                    echo("FIle could not be saved" . PHP_EOL);
                }

            }else{
                echo("File 'context.json' does not exist. Attempting to create file." . PHP_EOL);
                $fileData = json_encode(['roles' => [], 'contexts' => $contexts,]);
                $saved = Storage::disk('public_uploads')->put('/.well-known/context.json', $fileData);
                if($saved){
                    echo("File saved" . PHP_EOL);
                }else{
                    echo("FIle could not be saved" . PHP_EOL);
                }

            }

            
            // update the database
            $prevSavedContexts = (Context::query()->select('name')->get()->pluck('name'))->toArray();
            $inactiveContexts = array_diff($prevSavedContexts, $contexts);

            foreach($inactiveContexts as $inactiveContext){
                $oldContext = Context::where('name', $inactiveContext)->first();
                $oldContext->active = false;
                $oldContext->save();
            }

            echo('updating contexts...' . PHP_EOL);
            foreach ($contexts as $context) {
                try{
                    $oldContext = Context::where('name', $context)->first();
                    if($oldContext){
                        $oldContext->active = true;
                        $oldContext->save();
                    }else{                                
                        Context::create([
                            'name' => $context,
                            'active' => true,
                            'created_by' => 'CRON JOB',
                            'modified_by' => 'CRON JOB',
                        ]);
                    }
                }catch(\Exception $e){
                    echo("Could not save or update contexts in database." . PHP_EOL);
                    echo($e);
                }
            }


            
        }catch(ClientException $e){
            echo("An error occurred while making a request to the API" . PHP_EOL);
        }

        


        echo(PHP_EOL);
        echo('Cron job completed' . PHP_EOL);
        
        return 0;
    }
}
