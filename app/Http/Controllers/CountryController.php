<?php

namespace App\Http\Controllers;

use App\Http\Resources\CountryResource;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
  public function __construct()
  {
//    $this->middleware(['auth:api']);
  }

  public function index()
  {
    return CountryResource::collection(Country::get());
  }

}
