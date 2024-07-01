<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

    class StudentDocument extends Pivot
    {
        use HasFactory;

        protected $table = 'student_document';

        protected $guarded = [];
    }

