<?php

namespace App;

enum UserType: int
{
    case admin = 1;
    case doctor = 2;
    case patient = 3;
}
