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

namespace Geeshoe\Diesel\FunctionalTests;

use Geeshoe\DbConnector\ConfigAdapter\EnvConfigAdapter;
use Geeshoe\DbConnector\DbConnector;
use Geeshoe\Diesel\Database\MariaDbSetup;
use Geeshoe\Diesel\Model\SQL;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Dotenv\Dotenv;

/**
 * Class MariaDbSetupTest
 *
 * @package Geeshoe\Diesel\FunctionalTests
 * @author  Jesse Rushlow <jr@geeshoe.com>
 * @link    https://geeshoe.com
 */
class MariaDbSetupTest extends TestCase
{
    /**
     * @var \PDO
     */
    public static $pdo;

    /**
     * {@inheritDoc}
     *
     * @throws \Geeshoe\DbConnector\Exception\DbConnectorException
     */
    public static function setUpBeforeClass(): void
    {
        $localFile = dirname(__DIR__, 2) .'/.env.test.local';

        $env = new Dotenv();
        $env->load($localFile);

        $config = new EnvConfigAdapter();
        $config->initialize();

        $dbc = new DbConnector($config->getParams());
        self::$pdo = $dbc->getConnection();
    }

    /**
     * @inheritDoc
     */
    public static function tearDownAfterClass(): void
    {
        self::$pdo = null;
    }

    public function testExecuteSQLFromFile(): void
    {
        $mdb = new MariaDbSetup(self::$pdo);

        $dbName = 'GeeshoeDieselTest';

        $sql = new SQL();
        $sql->contents = "CREATE SCHEMA `$dbName`;";

        $mdb->runSQLFromSQLObject($sql);

        $query = self::$pdo->query(
            "SELECT * FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = '$dbName';"
        );

        $result = $query->fetch();

        self::$pdo->exec("DROP SCHEMA $dbName");

        $this->assertSame($dbName, $result['SCHEMA_NAME']);
    }
}
