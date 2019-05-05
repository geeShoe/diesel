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

namespace Geeshoe\Diesel\UnitTests;

use Geeshoe\Diesel\Model\SQL;
use Geeshoe\Diesel\Model\SQLCollection;
use PHPUnit\Framework\TestCase;

/**
 * Class SQLCollectionTest
 *
 * @package Geeshoe\Diesel
 * @author  Jesse Rushlow <jr@geeshoe.com>
 * @link    https://geeshoe.com
 */
class SQLCollectionTest extends TestCase
{
    /**
     * @return SQL
     *
     * @throws \Exception
     */
    public function makeSQLObject(): SQL
    {
        $sql = new SQL();
        $sql->name = random_int(1, 1000) . 'file.sql';
        $sql->path = '/some/path';
        $sql->contents = 'Lorem Ipsum';

        return $sql;
    }

    /**
     * @throws \Exception
     */
    public function testAddMethodAddsSQLObjectToCollection(): void
    {
        $sql = $this->makeSQLObject();

        $collection = new SQLCollection();
        $collection->add($sql);

        $expected = [0 => $sql];

        $this->assertSame($expected, $collection->collection);
    }

    /**
     * @throws \Exception
     */
    public function testGetCollectionReturnsCollectionArray(): void
    {
        $sql = $this->makeSQLObject();

        $collection = new SQLCollection();
        $collection->collection = [0 => $sql];

        $results = $collection->getCollection();

        $this->assertSame([0 => $sql], $results);
    }

    /**
     * @throws \Exception
     */
    public function testCountReturnsNumberOfItemsInTheCollection(): void
    {
        $array = [
            $this->makeSQLObject(),
            $this->makeSQLObject(),
            $this->makeSQLObject()
        ];

        $collection = new SQLCollection();
        $collection->collection = $array;

        $result = $collection->count();

        $this->assertSame(3, $result);
    }

    /**
     * @throws \Exception
     */
    public function testGetObjectByNameReturnsSQLObject(): void
    {
        $expected = $this->makeSQLObject();

        $array = [
            $this->makeSQLObject(),
            $this->makeSQLObject(),
            $this->makeSQLObject(),
            $expected
        ];

        $collection = new SQLCollection();
        $collection->collection = $array;

        $result = $collection->getObjectByName($expected->name);

        $this->assertSame($expected, $result);
    }

    /**
     * @throws \Exception
     */
    public function testGetObjectByNameReturnsNullIfObjectDoesntExist(): void
    {
        $array = [
            $this->makeSQLObject(),
            $this->makeSQLObject(),
            $this->makeSQLObject()
        ];

        $collection = new SQLCollection();
        $collection->collection = $array;

        $result = $collection->getObjectByName('MyObject');

        $this->assertNull($result);
    }
}
