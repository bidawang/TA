<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Rental_M;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected $rental;

    public function __construct(Rental_M $rental)
    {
        $this->rental = $rental;
    }
}
