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

use Geeshoe\Diesel\Database\MariaDB;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

/**
 * Class MariaDBTest
 *
 * @package Geeshoe\Diesel
 * @author  Jesse Rushlow <jr@geeshoe.com>
 * @link    https://geeshoe.com
 */
class MariaDBTest extends TestCase
{
    /**
     * @var vfsStreamDirectory
     */
    public $vfs;

    /**
     * @var string
     */
    public $path = 'vfs://root';

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        $this->vfs = vfsStream::setup();
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    public function createFile(): string
    {
        $fileName = random_int(1, 1000) . 'someFile.sql';

        vfsStream::newFile($fileName)->at($this->vfs);

        return $fileName;
    }

    /**
     * @return MariaDB|object
     */
    public function getExtendedClass()
    {
        return new class extends MariaDB
        {
            public function fileContents(string $file): string
            {
                return $this->getFileContents($file);
            }

            public function unreadableFiles(string $dir, array $files = []): array
            {
                return $this->dropUnreadableFiles($dir, $files);
            }

            public function fileList(string $dir): array
            {
                return $this->getFileList($dir);
            }
        };
    }

    /**
     * @throws \Exception
     */
    public function testGetFileContentsReturnsContentsOfFile(): void
    {
        $class = $this->getExtendedClass();

        $file = $this->createFile();

        $content = 'Lorem Ipsum';

        file_put_contents("$this->path/$file", $content);

        $result = $class->fileContents("$this->path/$file");

        $this->assertSame($content, $result);
    }

    /**
     * @throws \Exception
     */
    public function testDropUnreadableFilesReturnsOnlyFilesThatAreReadable(): void
    {
        $class = $this->getExtendedClass();

        $unreadable = $this->createFile();
        chmod("vfs://root/$unreadable", 0000);

        $fileList = [
            $this->createFile(),
            $unreadable
        ];

        $results = $class->unreadableFiles($this->path, $fileList);

        $this->assertSame([0 => $fileList[0]], $results);
    }

    /**
     * @throws \Exception
     */
    public function testGetFileListOnlyReturnsDotSQLFiles(): void
    {
        $expected = [
            $this->createFile(),
            $this->createFile()
        ];

        vfsStream::newFile('some.txt')->at($this->vfs);

        $class = $this->getExtendedClass();

        $results = $class->fileList($this->path);

        $this->assertCount(2, $results);

        foreach ($expected as $file) {
            $this->assertNotFalse(array_search($file, $results, true));
        }
    }

    /**
     * @throws \Exception
     */
    public function testGetSQLFilesFromDirReturnsSQLCollection(): void
    {
        $this->createFile();
        $this->createFile();

        $mdb = new MariaDB();
        $collection = $mdb->getSQLFilesFromDir($this->path);

        $this->assertCount(2, $collection);
    }
}
