<?php

declare(strict_types=1);

namespace Athwari\LaravelUserTimezone\Tests;

use Athwari\LaravelUserTimezone\Traits\HasTimezone;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasTimezone;

    protected $guarded = [];

    public $timestamps = false;

    protected $connection = 'testing';
}
