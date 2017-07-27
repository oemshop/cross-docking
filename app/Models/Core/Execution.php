<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class Execution extends Model
{
    public function start($providerId, $dataRows)
    {
        $this->provider_id = $providerId;
        $this->data_rows = count($dataRows);
        $this->started_at = date('Y-m-d H:i:s');
        $this->save();

        return $this;
    }

    public function finish()
    {
        $this->finished = true;
        $this->finished_at = date('Y-m-d H:i:s');
        $this->save();
    }

    public function feeds()
    {
        return $this->hasMany(ExecutionFeed::class);
    }
}
