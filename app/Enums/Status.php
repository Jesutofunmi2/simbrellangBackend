<?php

namespace App\Enums;

enum Status: string
{
    case TODO = 'TODO';
    case PROGRESS = 'PROGRESS';
    case COMPLETED = 'COMPLETED';
}
