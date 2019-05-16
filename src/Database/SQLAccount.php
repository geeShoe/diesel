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
use Geeshoe\Diesel\Model\MySQLAccount;

/**
 * Class SQLAccount
 *
 * @package Geeshoe\Diesel\Database
 * @author  Jesse Rushlow <jr@geeshoe.com>
 * @link    https://geeshoe.com
 */
class SQLAccount extends AbstractCLICalls
{
    /**
     * @return bool
     */
    public function askCreateAccount(): bool
    {
        $response = $this->promptConfirm('Create MariaDb/MySQL user account? (y/n)');

        return $response ===  'y';
    }

    /**
     * @return MySQLAccount
     */
    public function getCredentials(): MySQLAccount
    {
        $account = new MySQLAccount();

        $account->user = $this->promptUserInput('User: ');
        $account->password = $this->promptUserPassword('Password: ');
        $account->passwordVerify = $this->promptUserPassword('Confirm password: ');
        $account->host = $this->promptUserInput('Host: ');

        $this->comparePasswords($account->password, $account->passwordVerify);

        return $account;
    }

    /**
     * @param string $password
     * @param string $verify
     */
    protected function comparePasswords(string $password, string $verify): void
    {
        if ($password !== $verify) {
            $this->cli->out('Password mismatch. Please try again.');
            $this->getCredentials();
        }
    }
}
