<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CrossDockingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crossdocking:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute crossdockings integrations.';

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
        $progress = $this->getHelperSet()->get('progress');
        $ctx = stream_context_create(array(), array('notification' => function ($notification_code, $severity, $message, $message_code, $bytes_transferred, $bytes_max) use ($output, $progress) {
            switch ($notification_code) {
                case STREAM_NOTIFY_FILE_SIZE_IS:
                    $progress->start($output, $bytes_max);
                    break;
                case STREAM_NOTIFY_PROGRESS:
                    $progress->setCurrent($bytes_transferred);
                    break;
            }
        }));

        $file = file_get_contents('http://webmax.hayamax.com.br/crossdock/servlet/CrossDockingServlet.class.php?action=crossDockingPrice&customerId=334993&compress=0&canal=CD', false, $ctx);
        $progress->finish();
    }
}
