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

namespace Geeshoe\Diesel\Model;

/**
 * Class DatabaseCredentials
 *
 * @package Geeshoe\Diesel\Model
 * @author  Jesse Rushlow <jr@geeshoe.com>
 * @link    https://geeshoe.com
 */
class DatabaseCredentials
{
    /**
     * @var string Hostname
     */
    public $host;

    /**
     * @var int Host Port
     */
    public $port;

    /**
     * @var string Database username
     */
    public $user;

    /**
     * @var string Database password
     */
    public $password;

    /**
     * @var string Name of new database
     */
    public $database;

    /**
     * @var bool Persistent connection
     */
    public $persistent;

    /**
     * @var bool Enable SSL connection
     */
    public $ssl;
}
