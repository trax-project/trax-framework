<?php

namespace Trax\Account\Models;

use Illuminate\Foundation\Auth\User;

use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class UserWithFiles extends User implements HasMedia
{
    use HasMediaTrait;
}

