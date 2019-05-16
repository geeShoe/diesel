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

namespace Geeshoe\Diesel\UnitTests\Model;

use Geeshoe\Diesel\Model\AbstractCLICalls;
use League\CLImate\CLImate;
use League\CLImate\TerminalObject\Dynamic\Input;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractCLICallsTest
 *
 * @package Geeshoe\Diesel\UnitTests\Model
 * @author  Jesse Rushlow <jr@geeshoe.com>
 * @link    https://geeshoe.com
 */
class AbstractCLICallsTest extends TestCase
{
    /**
     * @var MockObject|AbstractCLICalls
     */
    public $abstractCLICalls;

    /**
     * @param CLImate $cliMock
     *
     * @throws \ReflectionException
     */
    public function getAbstract(CLImate $cliMock): void
    {
        $this->abstractCLICalls = $this->getMockForAbstractClass(
            AbstractCLICalls::class,
            [$cliMock]
        );
    }

    /**
     * @return MockObject|Input
     *
     * @throws \ReflectionException
     */
    public function getInputMock(): MockObject
    {
        $inputMock = $this->createMock(Input::class);
        $inputMock->expects($this->once())
            ->method('prompt')
            ->willReturn('Response');

        return $inputMock;
    }

    /**
     * @param string $magicCallArg
     *
     * @return MockObject|CLImate
     *
     * @throws \ReflectionException
     */
    public function getCLIMock(string $magicCallArg): MockObject
    {
        $cliMock = $this->createMock(CLImate::class);
        $cliMock->expects($this->once())
            ->method('__call')
            ->with($magicCallArg, ['Question'])
            ->willReturn($this->getInputMock());
        return $cliMock;
    }

    /**
     * @throws \ReflectionException
     */
    public function testPromptUserInputPromptsUserAndReturnsString(): void
    {
        $cliMock = $this->getCLIMock('input');

        $this->getAbstract($cliMock);

        $result = $this->abstractCLICalls->promptUserInput('Question');
        $expected = 'Response';

        $this->assertSame($expected, $result);
    }

    /**
     * @throws \ReflectionException
     */
    public function testPromptUserPasswordPromptsUserAndReturnsString(): void
    {
        $cliMock = $this->getCLIMock('password');

        $this->getAbstract($cliMock);

        $result = $this->abstractCLICalls->promptUserPassword('Question');
        $expected = 'Response';

        $this->assertSame($expected, $result);
    }

    /**
     * @throws \ReflectionException
     */
    public function testPromptConfirmPromptsUserAndReturnsString(): void
    {
        $cliMock = $this->getCLIMock('confirm');

        $this->getAbstract($cliMock);

        $result = $this->abstractCLICalls->promptConfirm('Question');
        $expected = 'Response';

        $this->assertSame($expected, $result);
    }
}
