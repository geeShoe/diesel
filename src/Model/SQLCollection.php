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

/**
 * Class SQLCollection
 *
 * @package Geeshoe\Diesel\Model
 * @author  Jesse Rushlow <jr@geeshoe.com>
 * @link    https://geeshoe.com
 */
class SQLCollection implements \Countable
{
    /**
     * @var array
     */
    public $collection = [];

    /**
     * @param SQL $object
     */
    public function add(SQL $object): void
    {
        $this->collection[] = $object;
    }

    /**
     * Replace a SQL object within the collection
     *
     * @param string $name
     * @param SQL    $replacement
     */
    public function replace(string $name, SQL $replacement): void
    {
        foreach ($this->collection as $key => $object) {
            if ($object->name === $name) {
                $this->collection[$key] = $replacement;
            }
        }
    }

    /**
     * Remove an SQL object from the collection
     *
     * @param string $name
     */
    public function remove(string $name): void
    {
        foreach ($this->collection as $key => $object) {
            if ($object->name === $name) {
                unset($this->collection[$key]);
            }
        }
    }

    /**
     * @return array
     */
    public function getCollection(): array
    {
        return $this->collection;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->collection);
    }

    /**
     * @param string $name
     *
     * @return SQL|null
     */
    public function getObjectByName(string $name): ?SQL
    {
        foreach ($this->collection as $key => $object) {
            if ($object->name === $name) {
                return $this->collection[$key];
            }
        }

        return null;
    }
}
