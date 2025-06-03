<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PhpParser\Node\Expr\Cast\Array_;

class Task extends Model
{
   protected $table = 'tasks_table';

   protected $touches = ['user'];

   /**
    * Get the user that owns the Task
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function user(): BelongsTo
   {
       return $this->belongsTo(User::class);
   }
   protected function casts(): array
   {
    return  [
        'end_date' => 'datetime',
        'start_date' => 'datetime',
    ];
   }

}
