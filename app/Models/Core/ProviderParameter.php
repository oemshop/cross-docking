<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class ProviderParameter extends Model
{
    protected $fillable = ['param'];

    public static function associate(Provider $provider, $params)
    {
        if (!is_array($params) || count($params) == 0) {
            return false;
        }

        foreach ($params as $param) {
            if ($provider->parameters()->where('param', $param)->count() > 0) {
                continue;
            }

            $provider->parameters()->save(new ProviderParameter(['param' => $param]));
        }

        return $provider->parameters()->get();
    }
}
