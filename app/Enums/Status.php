<?php

namespace App\Enums;

enum Status: string
{
    case TODO = 'To Do';
    case PROGRESS = 'In Progress';
    case COMPLETED = 'Completed';
}
