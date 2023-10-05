<?php

declare(strict_types = 1);

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */
namespace Wdes\phpI18nL10n\plugins;

use Exception;
use stdClass;

/**
 * Plugin for reading .mo files
 * @see https://www.gnu.org/software/gettext/manual/html_node/MO-Files.html
 * @author William Desportes <williamdes@wdes.fr>
 * @license MPL-2.0
 */
class MoReader extends BasePlugin
{

    public const ENDIAN_BIG    = 1;
    public const ENDIAN_LITTLE = 2;

    /**
     * Translations
     *
     * @var array<string,string>
     */
    protected $translations = [];

    /**
     * Build a MoReader instance
     *
     * @param array $options The options
     */
    public function __construct(array $options = [])
    {
        $options = null;// Unused param detection
    }

    /**
     * Set the translations
     * @param array<string,string> $translations
     */
    public function setTranslations(array $translations): void
    {
        $this->translations = $translations;
    }

    /**
     * Get the translations
     * @return array<string,string> $translations
     */
    public function getTranslations(): array
    {
        return $this->translations;
    }

    /**
     * Read the .mo file
     *
     * @param string $file The complete path to the file
     * @return stdClass
     */
    public function readFile(string $file): stdClass
    {
        $data = new stdClass();
        if (! is_file($file)) {
            throw new Exception($file . ' does not exist.');
        }
        $res = fopen($file, 'rb');
        if (is_resource($res)) {
            $fileRes = $res;
        } else {
            throw new Exception($file . ' could not be open.');
        }
        $data->endian = self::determineByteOrder($fileRes);
        if ($data->endian === -1) {
            fclose($fileRes);
            throw new Exception($file . ' is not a valid gettext file.');
        }

        fseek($fileRes, 4);
        $data->fileFormatRevision = self::readInteger($data->endian, $fileRes);
        if ($data->fileFormatRevision !== 0 && $data->fileFormatRevision !== 1) {
            fclose($fileRes);
            throw new Exception($file . ' has an unknown major revision.');
        }
        $data->nbrOfStrings           = self::readInteger($data->endian, $fileRes);
        $data->tableOffsetOriginal    = self::readInteger($data->endian, $fileRes);//offset of table with original strings
        $data->tableOffsetTranslation = self::readInteger($data->endian, $fileRes);//offset of table with translation strings

        // see: https://gist.github.com/timwhitlock/8255619
        // Hash table needs to be implemented if not useless
        //$data->tblHashSize            = self::readInteger($data->endian, $fileRes);//size of hashing table
        //$data->hashTableOffsetStart   = self::readInteger($data->endian, $fileRes);//offset of hashing table
        //$data->hashTableOffsetEnd     = $data->hashTableOffsetStart + $data->tblHashSize * 4;//offset of hashing table

        fseek($fileRes, $data->tableOffsetOriginal);
        $data->msgIdTable = self::readIntegerList($data->endian, $fileRes, 2 * $data->nbrOfStrings);
        fseek($fileRes, $data->tableOffsetTranslation);
        $data->msgStrTable = self::readIntegerList($data->endian, $fileRes, 2 * $data->nbrOfStrings);

        $this->translations = $this->readTranslations($fileRes, $data);
        $data->translations = $this->translations;

        // Clean memory
        unset($data->tableOffsetOriginal);
        unset($data->tableOffsetTranslation);
        unset($data->fileFormatRevision);
        unset($data->endian);
        unset($data->msgIdTable);
        unset($data->msgStrTable);

        // Close resource
        fclose($fileRes);
        return $data;
    }

    /**
     * Read translations
     *
     * @copyright 2015 Max Grigorian
     * @license MIT
     *
     * @param resource $fileRes The mo file
     * @param stdClass $data The data
     * @return array<string,string>
     */
    public function readTranslations($fileRes, stdClass $data): array
    {
        $dataOut = [];
        for ($counter = 0; $counter < $data->nbrOfStrings; $counter++) {
            $msgId  = null;
            $msgStr = null;
            try {
                $msgId = $this->readStringFromTable($fileRes, $counter, $data->msgIdTable);
            } catch (Exception $e) {
                $msgId = [''];
            }
            try {
                $msgStr = $this->readStringFromTable($fileRes, $counter, $data->msgStrTable);
            } catch (Exception $e) {
                $msgStr = [];
            }
            $this->processRecord($dataOut, $msgId, $msgStr);
        }
        return $dataOut;
    }

    /**
     * Process a record
     *
     * @copyright 2015 Max Grigorian
     * @license MIT
     * @param array $data    The record
     * @param array $msgIds  Message ids
     * @param array $msgStrs Message strings
     * @return void
     */
    public function processRecord(array &$data, array $msgIds, array $msgStrs): void
    {
        // note: Contexts are stored by storing the concatenation of the context, a EOT byte, and the original string, instead of the original string.
        foreach ($msgIds as $key => $msgId) {
            $data[$msgId] = $msgStrs[$key];
        }
    }

    /**
     * Read a string from table
     *
     * @copyright 2015 Max Grigorian
     * @license MIT
     *
     * @param resource $fileRes The mo file
     * @param int   $index Position
     * @param array $table Table
     *
     * @return array
     */
    public function readStringFromTable($fileRes, int $index, array $table): array
    {
        $sizeKey = ($index * 2 ) + 1;
        $size    = $table[$sizeKey];
        if ($size > 0) {
            $offset = $table[$sizeKey + 1];
            fseek($fileRes, $offset);
            $read = fread($fileRes, $size);
            if (is_string($read)) {
                return explode("\0", $read);
            } else {
                throw new Exception('read error !');
            }
        }
        throw new Exception('size error !');
    }

    /**
     * Determines byte order
     *
     * @copyright 2015 Max Grigorian
     * @license MIT
     * @see github.com/MAXakaWIZARD/MoReader:src/Reader.php
     * @param resource $res The file resource
     * @return int ENDIAN_BIG/ENDIAN_LITTLE/-1
     */
    public static function determineByteOrder($res): int
    {
        $orderBytes = fread($res, 4);
        if ($orderBytes === "\x95\x04\x12\xde") {
            return self::ENDIAN_BIG;
        } elseif ($orderBytes === "\xde\x12\x04\x95") {
            return self::ENDIAN_LITTLE;
        } else {
            return -1;
        }
    }

    /**
     * Read an integer from the current file.
     *
     * @copyright 2015 Max Grigorian
     * @license MIT
     * @see github.com/MAXakaWIZARD/MoReader:src/Reader.php
     * @param  int      $endian ENDIAN_BIG/ENDIAN_LITTLE
     * @param  resource $res    The file resource
     * @param  int      $nbr    Number of strings
     * @return array
     */
    public static function readIntegerList(int $endian, $res, int $nbr): array
    {
        $read = fread($res, 4 * $nbr);
        if (is_string($read)) {
            if ($endian === self::ENDIAN_LITTLE) {
                $unPack = unpack('V' . $nbr, $read);
                if ($unPack === false) {
                    return [];
                }
                return $unPack;
            }
            $unPack = unpack('N' . $nbr, $read);
            if ($unPack === false) {
                return [];
            }
            return $unPack;
        } else {
            throw new Exception('read error !');
        }
    }

    /**
     * Read a single integer from the current file.
     *
     * @copyright 2015 Max Grigorian
     * @license MIT
     * @param int      $endian ENDIAN_BIG/ENDIAN_LITTLE
     * @param  resource $res    The file resource
     * @return int
     */
    public static function readInteger(int $endian, $res)
    {
        $read = fread($res, 4);
        if (is_string($read)) {
            if ($endian === self::ENDIAN_LITTLE) {
                $result = unpack('Vint', $read);
            } else {
                $result = unpack('Nint', $read);
            }
            return $result === false ? PHP_INT_MAX : $result['int'];
        } else {
            throw new Exception('read error !');
        }
    }

    /**
     * Return string id or translation
     *
     * @param string $msgId The message id
     * @return string
     */
    public function idOrFind(string $msgId): string
    {
        if (array_key_exists($msgId, $this->translations)) {
            return $this->translations[$msgId];
        } else {
            return $msgId;
        }
    }

    /**
     * Get translation for a message id
     *
     * @param string $msgId Message id
     * @return string
     */
    public function __(string $msgId): string
    {
        return self::gettext($msgId);
    }

    /**
     * Get translation for a message id
     *
     * @param string $msgId Message id
     * @return string
     */
    public function gettext(string $msgId): string
    {
        return self::idOrFind($msgId);
    }

    /**
     * Get translation
     *
     * @param string $domain      Domain
     * @param string $msgctxt     msgctxt
     * @param string $msgId       Message id
     * @param string $msgIdPlural Plural message id
     * @param int    $number      Number
     * @return string
     */
    public function dnpgettext(string $domain, string $msgctxt, string $msgId, string $msgIdPlural, int $number): string
    {
        return self::idOrFind($msgId);
    }

    /**
     * Get translation
     *
     * @param string $domain      Domain
     * @param string $msgId       Message id
     * @param string $msgIdPlural Plural message id
     * @param int    $number      Number
     * @return string
     */
    public function dngettext(string $domain, string $msgId, string $msgIdPlural, int $number): string
    {
        return self::idOrFind($msgId);
    }

    /**
     * Get translation
     *
     * @param string $msgctxt     msgctxt
     * @param string $msgId       Message id
     * @param string $msgIdPlural Plural message id
     * @param int    $number      Number
     * @return string
     */
    public function npgettext(string $msgctxt, string $msgId, string $msgIdPlural, int $number): string
    {
        if ($number > 1) {
            return self::idOrFind($msgIdPlural);
        } else {
            return self::idOrFind($msgId);
        }
    }

    /**
     * Plural version of gettext
     *
     * @param string $msgId       Message id
     * @param string $msgIdPlural Plural message id
     * @param int    $number      Number
     * @return string
     */
    public function ngettext(string $msgId, string $msgIdPlural, int $number): string
    {
        if ($number > 1) {
            return self::idOrFind($msgIdPlural);
        } else {
            return self::idOrFind($msgId);
        }
    }

    /**
     * Get translation
     *
     * @param string $domain  Domain
     * @param string $msgctxt msgctxt
     * @param string $msgId   Message id
     * @return string
     */
    public function dpgettext(string $domain, string $msgctxt, string $msgId): string
    {
        return self::idOrFind($msgId);
    }

    /**
     * Get translation
     *
     * @param string $domain Domain
     * @param string $msgId  Message id
     * @return string
     */
    public function dgettext(string $domain, string $msgId): string
    {
        return self::idOrFind($msgId);
    }

    /**
     * Get translation
     *
     * @param string $msgctxt msgctxt
     * @param string $msgId   Message id
     * @return string
     */
    public function pgettext(string $msgctxt, string $msgId): string
    {
        return self::idOrFind($msgId);
    }

}
