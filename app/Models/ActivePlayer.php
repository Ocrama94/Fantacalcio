<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivePlayer  extends Model
{
    use HasFactory;
    public $info = [];
    public function __construct(
        public $id,
        public $name,
        public $role,
        public $vote,
    ){
        $this->info = [
            $this->$id,
            $this->$name,
            $this->$role,
            $this->$vote,
        ];
    }
}
