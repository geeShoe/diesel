<?php
/**
 * Copyright 2019 Jesse Rushlow - Geeshoe Development
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

declare(strict_types=1);

namespace Geeshoe\Diesel\Database;

/**
 * Class UserGrants
 *
 * @package Geeshoe\Diesel\Database
 * @author  Jesse Rushlow <jr@geeshoe.com>
 * @link    https://geeshoe.com
 */
class UserGrants
{
    /**
     * Change user and host placeholder in an sql string.
     *
     * GRANT ... 'userPlaceHolder'@'hostPlaceHolder';
     * will change to
     * GRANT ... '$user'@'$host';
     *
     * @param string $user              Desired user
     * @param string $host              Desired host
     * @param string $sql               SQL String
     * @param string $userPlaceHolder   Needle to replace with $user
     * @param string $hostPlaceHolder   Needle to replace with $host
     *
     * @return string
     */
    public static function parseGrantsSQL(
        string $user,
        string $host,
        string $sql,
        string $userPlaceHolder = "'user'",
        string $hostPlaceHolder = "'host'"
    ): string {
        $parsedUser = preg_replace(
            "/$userPlaceHolder/",
            "'$user'",
            $sql
        );

        $parsedHost = preg_replace(
            "/$hostPlaceHolder/",
            "'$host'",
            $parsedUser
        );

        return $parsedHost;
    }
}
