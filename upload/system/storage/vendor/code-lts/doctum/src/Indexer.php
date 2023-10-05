<?php

declare(strict_types = 1);

/*
 * This file is part of the Doctum utility.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Doctum;

class Indexer
{
    private const TYPE_CLASS     = 1;
    private const TYPE_METHOD    = 2;
    private const TYPE_NAMESPACE = 3;
    private const TYPE_FUNCTION  = 4;

    public function getIndex(Project $project)
    {
        $index = [
            'searchIndex' => [],
            'info' => [],
        ];

        foreach ($project->getNamespaces() as $namespace) {
            $index['searchIndex'][] = $this->getSearchString($namespace);
            $index['info'][]        = [self::TYPE_NAMESPACE, $namespace];
        }

        foreach ($project->getProjectClasses() as $class) {
            $index['searchIndex'][] = $this->getSearchString((string) $class);
            $index['info'][]        = [self::TYPE_CLASS, $class];
        }

        foreach ($project->getProjectClasses() as $class) {
            foreach ($class->getMethods() as $method) {
                $index['searchIndex'][] = $this->getSearchString((string) $method);
                $index['info'][]        = [self::TYPE_METHOD, $method];
            }
        }

        foreach ($project->getProjectFunctions() as $function) {
            $index['searchIndex'][] = $this->getSearchString((string) $function);
            $index['info'][]        = [self::TYPE_FUNCTION, $function];
        }

        return $index;
    }

    protected function getSearchString($string)
    {
        return strtolower(preg_replace('/\s+/', '', $string));
    }

}
