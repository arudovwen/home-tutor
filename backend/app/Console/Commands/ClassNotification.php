<?php

namespace App\Console\Commands;

use App\TimesTable;
use Illuminate\Console\Command;
use App\Http\Controllers\UserController;

class ClassNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:class';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Class Notification';

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
        $user = auth('api')->user();
      $notify = new UserController;
      $notify->newLecture();
 
    }
}
