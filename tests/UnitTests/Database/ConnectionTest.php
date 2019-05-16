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

namespace Geeshoe\Diesel\UnitTests\Database;

use Geeshoe\Diesel\Database\Connection;
use League\CLImate\CLImate;
use League\CLImate\TerminalObject\Dynamic\Input;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class ConnectionTest
 *
 * @package Geeshoe\Diesel\UnitTests\Database
 * @author  Jesse Rushlow <jr@geeshoe.com>
 * @link    https://geeshoe.com
 */
class ConnectionTest extends TestCase
{
    /**
     * @param int        $expectsCallsCount
     * @param MockObject $inputMock
     *
     * @return MockObject|CLImate
     *
     * @throws \ReflectionException
     */
    public function getCLIMateMock(int $expectsCallsCount, MockObject $inputMock): MockObject
    {
        $cli = $this->createMock(CLImate::class);
        $cli->expects($this->exactly($expectsCallsCount))
            ->method('__call')
            ->willReturn($inputMock)
            ->withConsecutive(
                ['input', ['Host: ']],
                ['input', ['Port: ']],
                ['input', ['User: ']],
                ['password', ['Password: ']],
                ['input', ['Name of new database: ']],
                ['confirm', ['Are the above credentials correct? (y/n)']]
            );
        return $cli;
    }

    /**
     * @throws \ReflectionException
     */
    public function testAskForCredentialsMakesCLICalls(): void
    {
        $input = $this->createMock(Input::class);
        $input->expects($this->exactly(6))
            ->method('prompt')
            ->willReturnOnConsecutiveCalls(
                'host',
                'port',
                'user',
                'password',
                'database',
                'y'
            );

        $cli = $this->getCLIMateMock(6, $input);

        $connection = new Connection($cli);
        $connection->askForCredentials();
    }

    /**
     * @throws \ReflectionException
     */
    public function testAskForCredentialsLoopsIfPromptConfirmDoesntReturnY(): void
    {
        $input = $this->createMock(Input::class);
        $input->expects($this->exactly(12))
            ->method('prompt')
            ->willReturnOnConsecutiveCalls(
                'host',
                'port',
                'user',
                'password',
                'database',
                'n',
                'host',
                'port',
                'user',
                'password',
                'database',
                'y'
            );


        $cli = $this->getCLIMateMock(12, $input);

        $connection = new Connection($cli);
        $connection->askForCredentials();
    }
}
