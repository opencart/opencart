<?php

namespace PHPSTORM_META {

    registerArgumentsSet('hash_algo',
        'md2',
        'md4',
        'md5',
        'sha1',
        'sha224',
        'sha256',
        'sha384',
        'sha512/224',
        'sha512/256',
        'sha512',
        'sha3-224',
        'sha3-256',
        'sha3-384',
        'sha3-512',
        'ripemd128',
        'ripemd160',
        'ripemd256',
        'ripemd320',
        'whirlpool',
        'tiger128,3',
        'tiger160,3',
        'tiger192,3',
        'tiger128,4',
        'tiger160,4',
        'tiger192,4',
        'snefru',
        'snefru256',
        'gost',
        'gost-crypto',
        'haval128,3',
        'haval160,3',
        'haval192,3',
        'haval224,3',
        'haval256,3',
        'haval128,4',
        'haval160,4',
        'haval192,4',
        'haval224,4',
        'haval256,4',
        'haval128,5',
        'haval160,5',
        'haval192,5',
        'haval224,5',
        'haval256,5'
    );

    expectedArguments(\hash_hmac_file(), 0, argumentsSet('hash_algo'));
    expectedArguments(\hash_hmac(), 0, argumentsSet('hash_algo'));
    expectedArguments(\hash_pbkdf2(), 0, argumentsSet('hash_algo'));
    expectedArguments(\hash_init(), 0, argumentsSet('hash_algo'));
}
