<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employe;
use Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\EmployesResource;


class EmployeController extends Controller
{

     /**
    *
    * @var Validator
    */
    private $validator;


    public function __construct(validator $validator){

        $this->validator = $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allNotRelated()
    {
        $listEmploye = DB::select("SELECT  employes.id,employes.firstname,employes.lastname,employes.identity  FROM    employes LEFT JOIN user_members t1 ON      t1.employe_id = employes.id  WHERE   t1.employe_id IS NULL");
        return $listEmploye;
    }
    public function index()
    {
        $listEmploye = Employe::all();
        return $listEmploye;
    }

    /**
     * Jean-Claude
     */

    public function address_id(Request $r)
    {
        $l = DB::select("SELECT * from employes
        WHERE address_id = ?",
        [$r->id]);
        return response()->json($l, 200);
    }


    public function lastName(Request $r)
    {
        $l = DB::select("SELECT * from employes
        WHERE lastName LIKE '".$r->data."%'",
        []);

        return response()->json($l, 200);
    }

    public function firstName(Request $r)
    {
        $l = DB::select("SELECT * from employes
        WHERE firstName LIKE '".$r->data."%'",
        []);

        return response()->json($l, 200);
    }

    public function identity(Request $r)
    {
        $l = DB::select("SELECT * from employes
        WHERE identity LIKE '".$r->data."%'",
        []);
        return response()->json($l, 200);
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
        //
        $validator = $this->validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'phonenumber' => 'required|max:20|min:8',
            'identity' => 'required|min:12|max:16',
            'address_id'=>'required',
            ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $createEmploye = Employe::create($request->all());
        return  $createEmploye;
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
        return new EmployesResource(Employe::with('Address')->with('User')->findOrFail($id));
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
        $validator = $this->validator::make($request->all(), [
            'firstname' => 'required',
            'identity' => 'required',
            'address_id' => 'required',
            'lastname' => 'required'
            ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $updateEmployeById = Employe::findOrFail($id);
        $updateEmployeById->update($request->all());

        return $updateEmployeById;
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
        $deleteEmployeById = Employe::find($id)->delete();
        return response()->json([], 204);
    }

    // public function updateStatus(Request $request, $id)
    // {
    //     $idDeliveryemploye = Authorizer::getResourceOwnerId();
    //     return $this->orderService->updateStatus($id, $idDeliveryemploye, $request->get('status'));
    // }

}
