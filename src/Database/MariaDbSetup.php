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

use Geeshoe\Diesel\Model\SQL;

/**
 * Class MariaDbSetup
 *
 * @package Geeshoe\Diesel\Database
 * @author  Jesse Rushlow <jr@geeshoe.com>
 * @link    https://geeshoe.com
 */
class MariaDbSetup
{
    /**
     * @var \PDO
     */
    public $pdo;

    /**
     * MariaDbSetup constructor.
     *
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param SQL $sqlObject
     */
    public function runSQLFromSQLObject(SQL $sqlObject): void
    {
        $this->pdo->exec($sqlObject->contents);
    }
}
