<?php 

namespace FFI;

final class CType
{
    public function getName() : string
    {
    }
    #[\Since('8.1')]
    public function getKind() : int
    {
    }
    #[\Since('8.1')]
    public function getSize() : int
    {
    }
    #[\Since('8.1')]
    public function getAlignment() : int
    {
    }
    #[\Since('8.1')]
    public function getAttributes() : int
    {
    }
    #[\Since('8.1')]
    public function getEnumKind() : int
    {
    }
    #[\Since('8.1')]
    public function getArrayElementType() : CType
    {
    }
    #[\Since('8.1')]
    public function getArrayLength() : int
    {
    }
    #[\Since('8.1')]
    public function getPointerType() : CType
    {
    }
    #[\Since('8.1')]
    public function getStructFieldNames() : array
    {
    }
    #[\Since('8.1')]
    public function getStructFieldOffset(string $name) : int
    {
    }
    #[\Since('8.1')]
    public function getStructFieldType(string $name) : CType
    {
    }
    #[\Since('8.1')]
    public function getFuncABI() : int
    {
    }
    #[\Since('8.1')]
    public function getFuncReturnType() : CType
    {
    }
    #[\Since('8.1')]
    public function getFuncParameterCount() : int
    {
    }
    #[\Since('8.1')]
    public function getFuncParameterType(int $index) : CType
    {
    }
}