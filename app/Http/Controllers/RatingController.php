<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\rating;
use App\Http\Resources\RatingsResource;
use App\Models\Employe;
use App\Services\Contracts\IRatingsDataProvider;
use App\Services\Contracts\IEmployesDataProvider;
use App\Traits\Ratingable;
use Validator;


class RatingController extends Controller
{

    use Ratingable;

     /**
     *
     * @var Validator
     */
    private $validator;

    /**
     *
     * @var IAccountsDataProvider
     */
    protected $provider;

    public function __construct(IRatingsDataProvider $provider, Validator $validator)
    {
        // parent::__construct();
        $this->provider = $provider;
        $this->validator = $validator;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Employe $employe)
    {
        //

        // dd($request);

        $rating = Rating::firstOrCreate(
            [
              'user_member_id' => $request->user()->id,
              'employe_id' => $employe->id,

            ] ,

            ['rating' => $request->rating]
          );
          return new RatingsResource($rating);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
