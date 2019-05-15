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
     */
    public function getSQLObject(): SQL
    {
        $sql = new SQL();
        $sql->name = 'someName';
        $sql->path = '/path';
        $sql->content = 'SQL Content';
        return $sql;
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testAddMethodAddsObjectToCollection(): void
    {
        $sql = $this->getSQLObject();

        $collection = new SQLCollection();
        $collection->add($sql);

        $this->assertCount(1, $collection);
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testRemoveMethodRemovesObjectFromTheCollection(): void
    {
        $sql = $this->getSQLObject();

        $collection = new SQLCollection();
        $collection->collection[] = $sql;

        $collection->remove('someName');

        $this->assertCount(0, $collection);
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testReplaceMethodReplacesAnSQLObject(): void
    {
        $sql = $this->getSQLObject();

        $collection = new SQLCollection();
        $collection->collection[] = $sql;

        $new = $this->getSQLObject();
        $new->path = '/new/Path';

        $collection = new SQLCollection();
        $collection->collection[] = $sql;

        $collection->replace($new);

        $this->assertCount(1, $collection);
        $this->assertSame($new->path, $collection->collection[0]->path);
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testReplaceMethodReplacesObjectWithNameProvided(): void
    {
        $sql = $this->getSQLObject();

        $collection = new SQLCollection();
        $collection->collection[] = $sql;

        $new = $this->getSQLObject();
        $new->name = 'newName';

        $collection->replace($new, 'someName');

        $this->assertCount(1, $collection);
        $this->assertSame($new, $collection->collection[0]);
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testGetSQLByNameReturnsSQLObject(): void
    {
        $sql = $this->getSQLObject();

        $collection = new SQLCollection();
        $collection->collection[] = $sql;

        $result = $collection->getSQLByName($sql->name);

        $this->assertSame($sql, $result);
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testGetSQLByNameReturnsNullIfObjectNotFound(): void
    {
        $collection = new SQLCollection();

        $result = $collection->getSQLByName('Test');

        $this->assertNull($result);
    }
}
