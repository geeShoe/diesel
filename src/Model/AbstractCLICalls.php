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

use Geeshoe\Diesel\Contract\CLICallsInterface;
use League\CLImate\CLImate;

/**
 * Class AbstractCLICalls
 *
 * @package Geeshoe\Diesel\Model
 * @author  Jesse Rushlow <jr@geeshoe.com>
 * @link    https://geeshoe.com
 */
abstract class AbstractCLICalls implements CLICallsInterface
{
    /**
     * @var CLImate
     */
    public $cli;

    /**
     * AbstractCLICalls constructor.
     *
     * @param CLImate $cli
     */
    public function __construct(CLImate $cli)
    {
        $this->cli = $cli;
    }

    /**
     * @inheritDoc
     */
    public function promptUserInput(string $question): string
    {
        $input = $this->cli->input($question);
        return $input->prompt();
    }

    /**
     * @inheritDoc
     */
    public function promptUserPassword(string $question): string
    {
        $input = $this->cli->password($question);
        return $input->prompt();
    }

    /**
     * @inheritDoc
     */
    public function promptConfirm(string $question): string
    {
        $input = $this->cli->confirm($question);
        return $input->prompt();
    }
}
