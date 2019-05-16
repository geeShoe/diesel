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

use Geeshoe\Diesel\Database\SQLAccount;
use League\CLImate\CLImate;
use League\CLImate\TerminalObject\Dynamic\Input;
use PHPUnit\Framework\TestCase;

/**
 * Class SQLAccountTest
 *
 * @package Geeshoe\Diesel\UnitTests\Database
 * @author  Jesse Rushlow <jr@geeshoe.com>
 * @link    https://geeshoe.com
 */
class SQLAccountTest extends TestCase
{
    /**
     * @return array
     */
    public function promptDataProvider(): array
    {
        return [
            [true, 'y'],
            [false, 'n']
        ];
    }

    /**
     * @dataProvider promptDataProvider
     *
     * @param bool   $expected
     * @param string $response
     * @throws \ReflectionException
     */
    public function testAskCreateAccountPromptsUserAndReturnsBool(bool $expected, string $response): void
    {
        $inputMock = $this->createMock(Input::class);
        $inputMock->expects($this->once())
            ->method('prompt')
            ->willReturn($response);

        $cliMock = $this->createMock(CLImate::class);
        $cliMock->expects($this->once())
            ->method('__call')
            ->willReturn($inputMock)
            ->with('confirm', ['Create MariaDb/MySQL user account? (y/n)']);

        $sqlAccount = new SQLAccount($cliMock);
        $result = $sqlAccount->askCreateAccount();

        $this->assertSame($expected, $result);
    }

    /**
     * @throws \ReflectionException
     */
    public function testAskForCredentialsPromptsCLIAndReturnsInput(): void
    {
        $inputMock = $this->createMock(Input::class);
        $inputMock->expects($this->exactly(4))
            ->method('prompt')
            ->willReturn('');

        $cliMock = $this->createMock(CLImate::class);
        $cliMock->expects($this->exactly(4))
            ->method('__call')
            ->willReturn($inputMock)
            ->withConsecutive(
                ['input', ['User: ']],
                ['password', ['Password: ']],
                ['password', ['Confirm password: ']],
                ['input', ['Host: ']]
            );

        $sqlAccount = new SQLAccount($cliMock);
        $sqlAccount->getCredentials();
    }

    /**
     * @throws \ReflectionException
     */
    public function testComparePasswordsLoopsGetCredentialsOnPasswordMismatch(): void
    {
        $inputMock = $this->createMock(Input::class);
        $inputMock->expects($this->exactly(8))
            ->method('prompt')
            ->willReturnOnConsecutiveCalls(
                '',
                'pass',
                'wrong',
                '',
                '',
                '',
                '',
                ''
            );

        $cliMock = $this->createMock(CLImate::class);
        $cliMock->expects($this->exactly(9))
            ->method('__call')
            ->willReturn($inputMock)
            ->withConsecutive(
                ['input', ['User: ']],
                ['password', ['Password: ']],
                ['password', ['Confirm password: ']],
                ['input', ['Host: ']],
                ['out', ['Password mismatch. Please try again.']]
            );

        $sqlAccount = new SQLAccount($cliMock);
        $sqlAccount->getCredentials();
    }

    /**
     * @throws \ReflectionException
     */
    public function testGetCredentialsAddsInputToMySQLAccountObject(): void
    {
        $inputMock = $this->createMock(Input::class);
        $inputMock->method('prompt')
            ->willReturnOnConsecutiveCalls(
                'user',
                'pass',
                'pass',
                'host'
            );

        $cliMock = $this->createMock(CLImate::class);
        $cliMock->method('__call')
            ->willReturn($inputMock);

        $sqlAccount = new SQLAccount($cliMock);
        $account = $sqlAccount->getCredentials();

        $this->assertSame('user', $account->user);
        $this->assertSame('pass', $account->password);
        $this->assertSame('pass', $account->passwordVerify);
        $this->assertSame('host', $account->host);
    }
}
