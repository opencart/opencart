<?php

namespace {
    use JetBrains\PhpStorm\Internal\PhpStormStubsElementAvailable;
    use JetBrains\PhpStorm\Pure;

    /**
     * Combined linear congruential generator
     * @link https://php.net/manual/en/function.lcg-value.php
     * @return float A pseudo random float value in the range of (0, 1)
     */
    function lcg_value(): float {}

    /**
     * Seeds the Mersenne Twister Random Number Generator
     * @link https://php.net/manual/en/function.mt-srand.php
     * @param int $seed <p>
     * An optional seed value
     * </p>
     * @param int $mode [optional] <p>
     * Use one of the following constants to specify the implementation of the algorithm to use.
     * </p>
     * @return void
     */
    function mt_srand(
        int $seed = 0,
        #[PhpStormStubsElementAvailable(from: '7.1')] int $mode = MT_RAND_MT19937
    ): void {}

    /**
     * Seed the random number generator
     * <p><strong>Note</strong>: As of PHP 7.1.0, {@see srand()} has been made
     * an alias of {@see mt_srand()}.
     * </p>
     * @link https://php.net/manual/en/function.srand.php
     * @param int $seed <p>
     * Optional seed value
     * </p>
     * @param int $mode [optional] <p>
     * Use one of the following constants to specify the implementation of the algorithm to use.
     * </p>
     * @return void
     */
    function srand(
        int $seed = 0,
        #[PhpStormStubsElementAvailable(from: '7.1')] int $mode = MT_RAND_MT19937
    ): void {}

    /**
     * Generate a random integer
     * @link https://php.net/manual/en/function.rand.php
     * @param int $min
     * @param int $max [optional]
     * @return int A pseudo random value between min
     * (or 0) and max (or getrandmax, inclusive).
     */
    function rand(int $min = null, int $max): int {}

    /**
     * Generate a random value via the Mersenne Twister Random Number Generator
     * @link https://php.net/manual/en/function.mt-rand.php
     * @param int $min <p>
     * Optional lowest value to be returned (default: 0)
     * </p>
     * @param int $max [optional] <p>
     * Optional highest value to be returned (default: mt_getrandmax())
     * </p>
     * @return int A random integer value between min (or 0)
     * and max (or mt_getrandmax, inclusive)
     */
    function mt_rand(int $min = null, int $max): int {}

    /**
     * Show largest possible random value
     * @link https://php.net/manual/en/function.mt-getrandmax.php
     * @return int the maximum random value returned by mt_rand
     */
    #[Pure]
    function mt_getrandmax(): int {}

    /**
     * Show largest possible random value
     * @link https://php.net/manual/en/function.getrandmax.php
     * @return int The largest possible random value returned by rand
     */
    #[Pure]
    function getrandmax(): int {}

    /**
     * Generates cryptographically secure pseudo-random bytes
     * @link https://php.net/manual/en/function.random-bytes.php
     * @param int $length The length of the random string that should be returned in bytes.
     * @return string Returns a string containing the requested number of cryptographically secure random bytes.
     * @since 7.0
     * @throws Exception if an appropriate source of randomness cannot be found.
     */
    function random_bytes(int $length): string {}

    /**
     * Generates cryptographically secure pseudo-random integers
     * @link https://php.net/manual/en/function.random-int.php
     * @param int $min The lowest value to be returned, which must be PHP_INT_MIN or higher.
     * @param int $max The highest value to be returned, which must be less than or equal to PHP_INT_MAX.
     * @return int Returns a cryptographically secure random integer in the range min to max, inclusive.
     * @since 7.0
     * @throws Exception if an appropriate source of randomness cannot be found.
     */
    function random_int(int $min, int $max): int {}
}

namespace Random\Engine
{
    /**
     * @since 8.2
     */
    final class Mt19937 implements \Random\Engine
    {
        public function __construct(int|null $seed = null, int $mode = MT_RAND_MT19937) {}

        public function generate(): string {}

        public function __serialize(): array {}

        public function __unserialize(array $data): void {}

        public function __debugInfo(): array {}
    }

    /**
     * @since 8.2
     */
    final class PcgOneseq128XslRr64 implements \Random\Engine
    {
        public function __construct(string|int|null $seed = null) {}

        public function generate(): string {}

        public function jump(int $advance): void {}

        public function __serialize(): array {}

        public function __unserialize(array $data): void {}

        public function __debugInfo(): array {}
    }

    /**
     * @since 8.2
     */
    final class Xoshiro256StarStar implements \Random\Engine
    {
        public function __construct(string|int|null $seed = null) {}

        public function generate(): string {}

        public function jump(): void {}

        public function jumpLong(): void {}

        public function __serialize(): array {}

        public function __unserialize(array $data): void {}

        public function __debugInfo(): array {}
    }

    /**
     * @since 8.2
     */
    final class Secure implements \Random\CryptoSafeEngine
    {
        public function generate(): string {}
    }
}

namespace Random
{
    use Error;
    use Exception;

    /**
     * @since 8.2
     */
    interface Engine
    {
        public function generate(): string;
    }
    /**
     * @since 8.2
     */
    interface CryptoSafeEngine extends Engine {}

    /**
     * @since 8.2
     */
    final class Randomizer
    {
        public readonly Engine $engine;

        public function __construct(?Engine $engine = null) {}

        public function nextInt(): int {}

        public function getInt(int $min, int $max): int {}

        public function getBytes(int $length): string {}

        public function shuffleArray(array $array): array {}

        public function shuffleBytes(string $bytes): string {}

        public function pickArrayKeys(array $array, int $num): array {}

        public function __serialize(): array {}

        public function __unserialize(array $data): void {}
    }

    /**
     * @since 8.2
     */
    class RandomError extends Error {}

    /**
     * @since 8.2
     */
    class BrokenRandomEngineError extends RandomError {}

    /**
     * @since 8.2
     */
    class RandomException extends Exception {}
}
