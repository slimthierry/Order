<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Services\Contracts\ICustomersDataProvider;
use Validator;

class CustomerController extends Controller
{
     /**
    *
    * @var Validator
    */
    private $validator;

   /**
    * @var ICustomerDataProvider
    */
    protected $provider;

    public function __construct(ICustomersDataProvider $provider, Validator $validator)
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
    public function index(Request $request, $id = null)
    {

        return Customer::all();
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
    public function store(Request $request)
    {
        $validator = $this->validator::make($request->all(),[
            "address_id"=> 'required',
            "name"=>'required',
            "phonenumber"=>'required|max:20|min:8'
            ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $createCustomer = Customer::create($request->all());
        return  $createCustomer;

    }

    /**
     * Display the specified resource.
     *
     * Handle GET /customer
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Request $request, $id)
    {
        //
        $customer = $this->provider->getById($id);
        if (!$request->user()->can('view', $customer)) {
            return $this->unauthorized($request);
        }
        //
        return $this->respondOk(['data' => $customer]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
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
    public function update(Request $request,$id)
    {
        $validator = $this->validator::make($request->all(),[
            "address_id"=> 'required',
            "name"=>'required',
            "phonenumber"=>'required|max:20|min:8'
            ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $updateCustomerById = Customer::findOrFail($id);
        $updateCustomerById->update($request->all());
        return  $updateCustomerById;
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
        $deleteDeliveranceById = Customer::find($id)->delete();
        return response()->json([], 204);
    }
}
