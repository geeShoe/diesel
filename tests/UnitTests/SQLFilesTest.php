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

use Geeshoe\Diesel\SQLFiles;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

/**
 * Class SQLFilesTest
 *
 * @package Geeshoe\Diesel\UnitTests
 * @author  Jesse Rushlow <jr@geeshoe.com>
 * @link    https://geeshoe.com
 */
class SQLFilesTest extends TestCase
{
    public function testGetSQLCollectionReturnsACollection(): void
    {
        $vfs = vfsStream::setup();

        vfsStream::newFile('foo.sql')->at($vfs);
        vfsStream::newFile('bar');

        $path = 'vfs://root';

        file_put_contents("$path/foo.sql", 'SQL Content');

        $collection = SQLFiles::getSQLCollection($path);

        $this->assertCount(1, $collection);
    }
}
