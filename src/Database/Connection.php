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

use Geeshoe\Diesel\Model\AbstractCLICalls;
use Geeshoe\Diesel\Model\DatabaseCredentials;

/**
 * Class Connection
 *
 * @package Geeshoe\Diesel\Database
 * @author  Jesse Rushlow <jr@geeshoe.com>
 * @link    https://geeshoe.com
 */
class Connection extends AbstractCLICalls
{
    /**
     * @return DatabaseCredentials
     */
    public function askForCredentials(): DatabaseCredentials
    {
        $database = new DatabaseCredentials();
        $database->host = $this->promptUserInput('Host: ');
        $database->port = $this->promptUserInput('Port: ');
        $database->user = $this->promptUserInput('User: ');
        $database->password = $this->promptUserPassword('Password: ');
        $database->database = $this->promptUserInput('Name of new database: ');
        $response = $this->promptConfirm('Are the above credentials correct? (y/n)');

        $this->confirm($response);

        return $database;
    }

    /**
     * @param string $response
     */
    protected function confirm(string $response): void
    {
        if ($response !== 'y') {
            $this->askForCredentials();
        }
    }
}
