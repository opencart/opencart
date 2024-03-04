<?php
declare(strict_types=1);

use StubTests\Model\StubsContainer;

require_once __DIR__ . '/../../vendor/autoload.php';

/** @var StubsContainer $coreStubs */
$coreStubs = unserialize(file_get_contents(__DIR__ . '/../../ReflectionData.json'), ['allowed_classes' => true]);
/** @var StubsContainer $peclAndCoreStubs */
$peclAndCoreStubs = unserialize(file_get_contents(__DIR__ . '/../../ReflectionDataPecl.json'), ['allowed_classes' => true]);
$onlyPeclStubs = new StubsContainer();
foreach ($peclAndCoreStubs->getConstants() as $peclConstant) {
    if (empty(array_filter($coreStubs->getConstants(), fn ($constant) => $constant->name === $peclConstant->name))) {
        $onlyPeclStubs->addConstant($peclConstant);
    }
}
foreach ($peclAndCoreStubs->getClasses() as $peclClass) {
    if (empty(array_filter($coreStubs->getClasses(), fn ($class) => $class->name === $peclClass->name))) {
        $onlyPeclStubs->addClass($peclClass);
    }
}
foreach ($peclAndCoreStubs->getFunctions() as $peclFunction) {
    if (empty(array_filter($coreStubs->getFunctions(), fn ($function) => $function->name === $peclFunction->name))) {
        $onlyPeclStubs->addFunction($peclFunction);
    }
}
foreach ($peclAndCoreStubs->getInterfaces() as $peclInterface) {
    if (empty(array_filter($coreStubs->getInterfaces(), fn ($interface) => $interface->name === $peclInterface->name))) {
        $onlyPeclStubs->addInterface($peclInterface);
    }
}
file_put_contents(__DIR__ . '/../../ReflectionData.json', serialize($onlyPeclStubs));
