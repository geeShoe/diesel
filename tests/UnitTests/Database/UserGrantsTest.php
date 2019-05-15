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

use Geeshoe\Diesel\Database\UserGrants;
use PHPUnit\Framework\TestCase;

/**
 * Class UserGrantsTest
 *
 * @package Geeshoe\Diesel\UnitTests\Database
 * @author  Jesse Rushlow <jr@geeshoe.com>
 * @link    https://geeshoe.com
 */
class UserGrantsTest extends TestCase
{
    public function testParseGrantsSQLReturnsStringWithDefinedUserAndHost(): void
    {
        $user = 'Test';
        $host = 'UnitTest';

        $sql = 'GRANT SOMETHING ON user_procedure TO \'user\'@\'host\';';

        $result = UserGrants::parseGrantsSQL($user, $host, $sql);

        $expected = 'GRANT SOMETHING ON user_procedure TO \'Test\'@\'UnitTest\';';

        $this->assertSame($expected, $result);
    }

    public function testParseGrantsSQLReturnsStringWithProvidedUserAndHostUsingDefinedPlaceholders(): void
    {
        $user = 'Test';
        $host = 'UnitTest';

        $sql = 'GRANT SOMETHING ON user_procedure TO \'foo\'@\'bar\';';

        $result = UserGrants::parseGrantsSQL($user, $host, $sql, "'foo'", "'bar'");

        $expected = 'GRANT SOMETHING ON user_procedure TO \'Test\'@\'UnitTest\';';

        $this->assertSame($expected, $result);
    }
}
