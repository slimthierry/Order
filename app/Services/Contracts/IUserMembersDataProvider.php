<?php

namespace App\Services\Contracts;

use App\Services\Contracts\Data\IDataProvider;


/**
 * Interface IUserMemberstDataProvider
 * @package App\Services\Contracts
 */
interface IUserMembersDataProvider extends IDataProvider
{

    public static function getProfilPage($username);

    public static function getProfilePicture();

    public static function updateDisplayName();

    public static function getLeaderboard();

    public static function updateProfilePicture();

}
