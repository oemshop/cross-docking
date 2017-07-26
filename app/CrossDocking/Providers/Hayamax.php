<?php

namespace App\CrossDocking\Providers;

use App\CrossDocking\Src\DockingProvider;

class Hayamax extends DockingProvider
{
    protected $parameters = ['user', 'password'];

    protected $fields = [
        'sku' => 'code',
        'titulo' => 'title',
    ];

    public function download()
    {
        $path = storage_path('crossdocking/'.$this->provider->id);

        if (! file_exists($path)) {
            \File::makeDirectory($path, 0777, true, true);
        }

        $ctx = stream_context_create(array(), array('notification' => function ($notification_code, $severity, $message, $message_code, $bytes_transferred, $bytes_max) {
            switch ($notification_code) {
                case STREAM_NOTIFY_FILE_SIZE_IS:
                    echo 'Downloading '.$bytes_max;
                    break;
                case STREAM_NOTIFY_PROGRESS:
                    // echo 'Downloading '.$bytes_transferred.'/'.$bytes_max;
                    break;
            }
        }));

        $file = file_get_contents('http://webmax.hayamax.com.br/crossdock/servlet/CrossDockingServlet.class.php?action=crossDockingPrice&customerId=334993&compress=0&canal=CD', false, $ctx);
        file_put_contents($path.'/'.$this->provider->id.'__'.date('Y-m-d-H-i-s').'.xml', $file);
    }

    protected function read()
    {
        $path = storage_path('crossdocking/'.$this->provider->id);
    }

    protected function format(array $data)
    {
        return $data;
    }
}
