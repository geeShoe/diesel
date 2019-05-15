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
class SQLCollection extends AbstractCollection
{
    /**
     * @param string $name
     *
     * @return SQL|null
     */
    public function getSQLByName(string $name): ?SQL
    {
        foreach ($this as $object) {
            if ($object->name === $name) {
                return $object;
            }
        }

        return null;
    }

    /**
     * @param SQL $sqlObject
     */
    public function add(SQL $sqlObject): void
    {
        $this->collection[] = $sqlObject;
    }

    /**
     * @param SQL         $sqlObject
     * @param string|null $oldName
     */
    public function replace(SQL $sqlObject, string $oldName = null): void
    {
        $name = $sqlObject->name;

        if ($oldName !== null) {
            $name = $oldName;
        }

        $keys = array_keys($this->collection);

        foreach ($keys as $key) {
            $this->compareReplaceName($name, $key, $sqlObject);
        }
    }

    /**
     * @param string $oldName
     * @param int    $key
     * @param SQL    $object
     */
    protected function compareReplaceName(string $oldName, int $key, SQL $object): void
    {
        if ($this->collection[$key]->name === $oldName) {
            $this->replaceObject($key, $object);
        }
    }

    /**
     * @param int $key
     * @param SQL $object
     */
    protected function replaceObject(int $key, SQL $object): void
    {
        $this->collection[$key] = $object;
    }

    /**
     * @param string $objectName
     */
    public function remove(string $objectName): void
    {
        $keys = array_keys($this->collection);

        foreach ($keys as $key) {
            $this->compareRemoveName($key, $objectName);
        }
    }

    /**
     * @param int    $key
     * @param string $name
     */
    protected function compareRemoveName(int $key, string $name): void
    {
        if ($this->collection[$key]->name === $name) {
            $this->removeObject($key);
        }
    }

    /**
     * @param int $key
     */
    protected function removeObject(int $key): void
    {
        unset($this->collection[$key]);
    }
}
