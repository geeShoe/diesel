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

use Geeshoe\Diesel\Database\MariaDB;
use Geeshoe\Diesel\Model\SQL;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class MariaDBTest
 *
 * @package Geeshoe\Diesel\UnitTests\Database
 * @author  Jesse Rushlow <jr@geeshoe.com>
 * @link    https://geeshoe.com
 */
class MariaDBTest extends TestCase
{

    /**
     * @param string $expected
     *
     * @return MockObject
     *
     * @throws \ReflectionException
     */
    public function pdoExec(string $expected): MockObject
    {
        $pdo = $this->createMock(\PDO::class);
        $pdo->expects($this->once())
            ->method('exec')
            ->with($expected);

        return $pdo;
    }

    /**
     * @throws \ReflectionException
     */
    public function testExecuteCallsPDOExec(): void
    {
        $expected = 'Test sql;';

        $pdo = $this->pdoExec($expected);

        $maria = new MariaDB($pdo);
        $maria->execute($expected);
    }

    /**
     * @throws \ReflectionException
     */
    public function testRunSQLFromSQLObjectsCallsPDOExecWithSQL(): void
    {
        $expected = 'Test sql;';

        $pdo = $this->pdoExec($expected);

        $sql = new SQL();
        $sql->content = $expected;

        $maria = new MariaDB($pdo);
        $maria->runSQLFromSQLObject($sql);
    }

    /**
     * @throws \ReflectionException
     */
    public function testCreateSchemaCallsPDOExecWithSchemaStatement(): void
    {
        $expected = "CREATE SCHEMA test\nCHARACTER SET = 'utf8mb4'\nCOLLATE = 'utf8mb4_general_ci';";

        $pdo = $this->pdoExec($expected);

        $maria = new MariaDB($pdo);
        $maria->createSchema('test');
    }
}
