<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employe extends Model
{
      /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'employes';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'firstname',
      'lastname',
      'phonenumber',
      'identity',
      'address_id'
    ];

    /**
     * Relationship.
     *
     * @var string
     */

       /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user() {
        return $this->hasOne('App\Models\UserMember');
      }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function address() {
      // dd($this->hasOne('App\Adresse'));
      // dd($this->belongsTo('App\Adresse'));
      return $this->belongsTo('App\Models\Address');
    }
}
