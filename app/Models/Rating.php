<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Rating extends Model
{

     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ratings';
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_member_id',
        'employe_id',
        'comment',
        'rating'
    ];

    /**
     * Relationship.
     *
     * @var string
     */

       /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employe() {
      return $this->belongsTo('App\Models\Employe');
    }


         /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userMember() {
        return $this->hasMany('App\Models\UserMember');
      }


    /**
     * @inheritDoc
     */
    public function rules()
    {
        // Returns a laravel validation rules definitions
        return array(
            "comment" => ["bail", "required", "max:145", "min:2", "unique:$this->table,comment"],

        );
    }

    /**
     * @inheritDoc
     */
    public function updateRules()
    {
        // Validation rules definition for example resources
        return array(
            "comment" => ["bail", "sometimes", "max:145", "min:2"],
            "id" => "required|bail|exists:$this->table,$this->primaryKey"
        );
    }

    /**
     * @inheritDoc
     */

    function getTransit()
    {
        $query = "SELECT * FROM Transits ORDER BY id DESC";

        $result = $this->ds->select($query);
        return $result;
    }

    /**
     * @inheritDoc
     */

    function getUserMemberRating($userMemberId, $transitId)
    {
        $average = 0;
        $avgQuery = "SELECT rating FROM ratings WHERE user_member_id = ? and employe_id = ?";
        $paramType = 'ii';
        $paramValue = array(
            $userMemberId,
            $transitId
        );
        $result = $this->ds->select($avgQuery, $paramType, $paramValue);
        if ($result > 0) {
            foreach ($result as $row) {
                $average = round($row["rating"]);
            } // endForeach
        } // endIf
        return $average;
    }

    function getTotalRating($employeId)
    {
        $totalVotesQuery = "SELECT * FROM rating WHERE employe_id = ?";
        $paramType = 'i';
        $paramValue = array(
            $employeId
        );
        $result = $this->ds->getRecordCount($totalVotesQuery, $paramType, $paramValue);
        return $result;
    }

    function isUserRatingExist($userMemberId, $employeId)
    {
        $checkIfExistQuery = "SELECT * FROM ratings WHERE user_member_id = ? AND employe_id = ?";
        $userMemberId;
        $employeId;
        $paramType = 'ii';
        $paramValue = array(
            $userMemberId,
            $employeId
        );
        $rowCount = $this->ds->getRecordCount($checkIfExistQuery, $paramType, $paramValue);
        return $rowCount;
    }

    function addRating($userMemberId, $employeId, $rating, $comment)
    {
        $insertQuery = "INSERT INTO ratings(user_member_id, employe_id, rating, comment) VALUES (?,?,?,?) ";

        $paramType = 'iiii';
        $paramValue = array(
            $userMemberId,
            $employeId,
            $rating,
            $comment
        );
        $insertId = $this->ds->insert($insertQuery, $paramType, $paramValue);
        return $insertId;
    }
}
