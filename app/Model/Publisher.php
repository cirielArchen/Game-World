<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    public function games()
    {
        return $this->belongsToMany('App\Model\Game', 'gamePublishers');
    }
}
