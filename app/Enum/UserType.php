<?php

namespace App\Enum;

enum UserType: int
{
    case Admin = 1;
    case Doctor = 2;
    case Patient = 3;
}
