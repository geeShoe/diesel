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

namespace Geeshoe\Diesel;

use Geeshoe\Diesel\Model\SQL;
use Geeshoe\Diesel\Model\SQLCollection;
use Geeshoe\Helpers\Files\FileHelpers;

/**
 * Class SQLFiles
 *
 * @package Geeshoe\Diesel
 * @author  Jesse Rushlow <jr@geeshoe.com>
 * @link    https://geeshoe.com
 */
class SQLFiles
{
    /**
     * @param string $dir
     *
     * @return SQLCollection
     */
    public function getSQLFilesFromDir(string $dir): SQLCollection
    {
        $files = $this->getFileList($dir);
        $files = $this->dropUnreadableFiles($dir, $files);

        $collection = new SQLCollection();

        foreach ($files as $file) {
            $sql = new SQL();
            $sql->name = $file;
            $sql->path = $dir;
            $sql->contents = $this->getFileContents("$dir/$file");
            $collection->add($sql);
        }

        return $collection;
    }

    /**
     * @param string $dir
     *
     * @return array
     */
    protected function getFileList(string $dir): array
    {
        $contents = scandir($dir);

        $files = [];

        foreach ($contents as $file) {
            if (preg_match('/\.sql$/i', $file)) {
                $files[] = $file;
            }
        }

        return $files;
    }

    /**
     * @param string $dir
     * @param array  $files
     *
     * @return array
     */
    protected function dropUnreadableFiles(string $dir, array $files = []): array
    {
        foreach ($files as $key => $file) {
            if (!FileHelpers::checkFileIsR("$dir/$file")) {
                unset($files[$key]);
            }
        }

        return $files;
    }

    /**
     * @param string $file
     *
     * @return string
     */
    protected function getFileContents(string $file): string
    {
        return file_get_contents($file);
    }
}
