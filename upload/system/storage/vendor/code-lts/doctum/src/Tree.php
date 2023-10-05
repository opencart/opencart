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

use Wdes\phpI18nL10n\Launcher;

class Tree
{

    public static function getGlobalNamespacePageName(): string
    {
        return Launcher::gettext('[Global_Namespace]');
    }

    public static function getGlobalNamespaceName(): string
    {
        return Launcher::gettext('[Global Namespace]');
    }

    public function getTree(Project $project): TreeNode
    {
        $namespaces = [];
        $ns         = $project->getNamespaces();
        foreach ($ns as $namespace) {
            if (false !== $pos = strpos($namespace, '\\')) {
                $namespaces[substr($namespace, 0, $pos)][] = $namespace;
            } else {
                $namespaces[$namespace][] = $namespace;
            }
        }

        return new TreeNode(0, '', '', $this->generateClassTreeLevel($project, 1, $namespaces, []));
    }

    /**
     * @param array<string,string[]> $namespaces
     * @param \Doctum\Reflection\ClassReflection[] $classes
     * @return TreeNode[]
     */
    protected function generateClassTreeLevel(Project $project, int $level, array $namespaces, array $classes): array
    {
        ++$level;

        $treeNodes         = [];
        $currentHumanLevel = $level - 1;
        foreach ($namespaces as $namespace => $subnamespaces) {
            // classes
            $cl = $project->getNamespaceAllClasses($namespace);

            // subnamespaces
            $ns = [];
            foreach ($subnamespaces as $subnamespace) {
                $parts = explode('\\', $subnamespace);
                if (!isset($parts[$currentHumanLevel])) {
                    continue;
                }

                $ns[implode('\\', array_slice($parts, 0, $level))][] = $subnamespace;
            }

            $parts = explode('\\', $namespace);
            $url   = Tree::getGlobalNamespacePageName();

            $namespaceParentPart = $parts[count($parts) - 1] ?? null;

            if ($namespaceParentPart && $project->hasNamespace($namespace) && (count($subnamespaces) || count($cl))) {
                $url = $namespace;
            }

            $short = $namespaceParentPart ? $namespaceParentPart : self::getGlobalNamespaceName();

            $treeNodes[] = new TreeNode($currentHumanLevel, $short, $url, $this->generateClassTreeLevel($project, $level, $ns, $cl));
        }

        foreach ($classes as $class) {
            $treeNodes[] = new TreeNode($currentHumanLevel, $class->getShortName(), $class->getName(), null);
        }

        return $treeNodes;
    }

}
