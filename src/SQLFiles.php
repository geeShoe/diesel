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
     * @var string Path to SQL Files
     */
    public static $dir;

    /**
     * @param string $dir
     *
     * @return SQLCollection
     */
    public static function getSQLCollection(string $dir): SQLCollection
    {
        self::$dir = $dir;
        $collection = new SQLCollection();

        $sqlFiles = self::sortFiles();

        foreach ($sqlFiles as $file) {
            $sql = new SQL();
            $sql->name = $file;
            $sql->path = self::$dir;
            $sql->content = file_get_contents(self::$dir."/$file");

            $collection->add($sql);
        }

        return $collection;
    }

    /**
     * @return array
     */
    protected static function sortFiles(): array
    {
        $files = self::gatherFiles();

        foreach ($files as $key => $file) {
            if (!preg_match('/\.sql$/i', $file)) {
                unset($files[$key]);
            }
        }

        return $files;
    }

    /**
     * @return array
     */
    protected static function gatherFiles(): array
    {
        $files = scandir(self::$dir);

        if ($files !== false) {
            return $files;
        }

        return [];
    }
}
