<?php

// PECL stats stubs for PhpStorm
// https://pecl.php.net/package/stats
// https://www.php.net/manual/en/book.stats.php

// by @yoosefi

/**
 * Returns the absolute deviation of the values in a, or FALSE if a is empty or is not an array.
 *
 * @link https://www.php.net/manual/en/function.stats-absolute-deviation.php
 * @param array $a
 * @return float|false
 */
function stats_absolute_deviation(array $a) {}

/**
 * Returns CDF, x, alpha, or beta, determined by which.
 *
 * @link https://www.php.net/manual/en/function.stats-cdf-beta.php
 * @param float $par1
 * @param float $par2
 * @param float $par3
 * @param int $which
 * @return float
 */
function stats_cdf_beta(float $par1, float $par2, float $par3, int $which): float {}

/**
 * Returns CDF, x, n, or p, determined by which.
 *
 * @link https://www.php.net/manual/en/function.stats-cdf-binomial.php
 * @param float $par1
 * @param float $par2
 * @param float $par3
 * @param int $which
 * @return float
 */
function stats_cdf_binomial(float $par1, float $par2, float $par3, int $which): float {}

/**
 * Returns CDF, x, x0, or gamma, determined by which.
 *
 * @link https://www.php.net/manual/en/function.stats-cdf-cauchy.php
 * @param float $par1
 * @param float $par2
 * @param float $par3
 * @param int $which
 * @return float
 */
function stats_cdf_cauchy(float $par1, float $par2, float $par3, int $which): float {}

/**
 * Returns CDF, x, or k, determined by which.
 *
 * @link https://www.php.net/manual/en/function.stats-cdf-chisquare.php
 * @param float $par1
 * @param float $par2
 * @param int $which
 * @return float
 */
function stats_cdf_chisquare(float $par1, float $par2, int $which): float {}

/**
 * Returns CDF, x, or lambda, determined by which.
 *
 * @link https://www.php.net/manual/en/function.stats-cdf-exponential.php
 * @param float $par1
 * @param float $par2
 * @param int $which
 * @return float
 */
function stats_cdf_exponential(float $par1, float $par2, int $which): float {}

/**
 * Returns CDF, x, d1, or d2, determined by which.
 *
 * @link https://www.php.net/manual/en/function.stats-cdf-f.php
 * @param float $par1
 * @param float $par2
 * @param float $par3
 * @param int $which
 * @return float
 */
function stats_cdf_f(float $par1, float $par2, float $par3, int $which): float {}

/**
 * Returns CDF, x, k, or theta, determined by which.
 *
 * @link https://www.php.net/manual/en/function.stats-cdf-gamma.php
 * @param float $par1
 * @param float $par2
 * @param float $par3
 * @param int $which
 * @return float
 */
function stats_cdf_gamma(float $par1, float $par2, float $par3, int $which): float {}

/**
 * Returns CDF, x, mu, or b, determined by which.
 *
 * @link https://www.php.net/manual/en/function.stats-cdf-laplace.php
 * @param float $par1
 * @param float $par2
 * @param float $par3
 * @param int $which
 * @return float
 */
function stats_cdf_laplace(float $par1, float $par2, float $par3, int $which): float {}

/**
 * Returns CDF, x, mu, or s, determined by which.
 *
 * @link https://www.php.net/manual/en/function.stats-cdf-logistic.php
 * @param float $par1
 * @param float $par2
 * @param float $par3
 * @param int $which
 * @return float
 */
function stats_cdf_logistic(float $par1, float $par2, float $par3, int $which): float {}

/**
 * Returns CDF, x, r, or p, determined by which.
 *
 * @link https://www.php.net/manual/en/function.stats-cdf-negative-binomial.php
 * @param float $par1
 * @param float $par2
 * @param float $par3
 * @param int $which
 * @return float
 */
function stats_cdf_negative_binomial(float $par1, float $par2, float $par3, int $which): float {}

/**
 * Returns CDF, x, k, or lambda, determined by which.
 *
 * @link https://www.php.net/manual/en/function.stats-cdf-noncentral-chisquare.php
 * @param float $par1
 * @param float $par2
 * @param float $par3
 * @param int $which
 * @return float
 */
function stats_cdf_noncentral_chisquare(float $par1, float $par2, float $par3, int $which): float {}

/**
 * Returns CDF, x, nu1, nu2, or lambda, determined by which.
 *
 * @link https://www.php.net/manual/en/function.stats-cdf-noncentral-f.php
 * @param float $par1
 * @param float $par2
 * @param float $par3
 * @param float $par4
 * @param int $which
 * @return float
 */
function stats_cdf_noncentral_f(float $par1, float $par2, float $par3, float $par4, int $which): float {}

/**
 * Returns CDF, x, nu, or mu, determined by which.
 *
 * @link https://www.php.net/manual/en/function.stats-cdf-noncentral-t.php
 * @param float $par1
 * @param float $par2
 * @param float $par3
 * @param int $which
 * @return float
 */
function stats_cdf_noncentral_t(float $par1, float $par2, float $par3, int $which): float {}

/**
 * Returns CDF, x, mu, or sigma, determined by which.
 *
 * @link https://www.php.net/manual/en/function.stats-cdf-normal.php
 * @param float $par1
 * @param float $par2
 * @param float $par3
 * @param int $which
 * @return float
 */
function stats_cdf_normal(float $par1, float $par2, float $par3, int $which): float {}

/**
 * Returns CDF, x, or lambda, determined by which.
 *
 * @link https://www.php.net/manual/en/function.stats-cdf-poisson.php
 * @param float $par1
 * @param float $par2
 * @param int $which
 * @return float
 */
function stats_cdf_poisson(float $par1, float $par2, int $which): float {}

/**
 * Returns CDF, x, or nu, determined by which.
 *
 * @link https://www.php.net/manual/en/function.stats-cdf-t.php
 * @param float $par1
 * @param float $par2
 * @param int $which
 * @return float
 */
function stats_cdf_t(float $par1, float $par2, int $which): float {}

/**
 * Returns CDF, x, a, or b, determined by which.
 *
 * @link https://www.php.net/manual/en/function.stats-cdf-uniform.php
 * @param float $par1
 * @param float $par2
 * @param float $par3
 * @param int $which
 * @return float
 */
function stats_cdf_uniform(float $par1, float $par2, float $par3, int $which): float {}

/**
 * Returns CDF, x, k, or lambda, determined by which.
 *
 * @link https://www.php.net/manual/en/function.stats-cdf-weibull.php
 * @param float $par1
 * @param float $par2
 * @param float $par3
 * @param int $which
 * @return float
 */
function stats_cdf_weibull(float $par1, float $par2, float $par3, int $which): float {}

/**
 * Returns the covariance of a and b, or FALSE on failure.
 *
 * @link https://www.php.net/manual/en/function.stats-covariance.php
 * @param array $a
 * @param array $b
 * @return float|false
 */
function stats_covariance(array $a, array $b) {}

/**
 * The probability density at x or FALSE for failure.
 *
 * @link https://www.php.net/manual/en/function.stats-dens-beta.php
 * @param float $x
 * @param float $a
 * @param float $b
 * @return float|false
 */
function stats_dens_beta(float $x, float $a, float $b) {}

/**
 * The probability density at x or FALSE for failure.
 *
 * @link https://www.php.net/manual/en/function.stats-dens-cauchy.php
 * @param float $x
 * @param float $ave
 * @param float $stdev
 * @return float|false
 */
function stats_dens_cauchy(float $x, float $ave, float $stdev) {}

/**
 * The probability density at x or FALSE for failure.
 *
 * @link https://www.php.net/manual/en/function.stats-dens-chisquare.php
 * @param float $x
 * @param float $dfr
 * @return float|false
 */
function stats_dens_chisquare(float $x, float $dfr) {}

/**
 * The probability density at x or FALSE for failure.
 *
 * @link https://www.php.net/manual/en/function.stats-dens-exponential.php
 * @param float $x
 * @param float $scale
 * @return float|false
 */
function stats_dens_exponential(float $x, float $scale) {}

/**
 * The probability density at x or FALSE for failure.
 *
 * @link https://www.php.net/manual/en/function.stats-dens-f.php
 * @param float $x
 * @param float $dfr1
 * @param float $dfr2
 * @return float|false
 */
function stats_dens_f(float $x, float $dfr1, float $dfr2) {}

/**
 * The probability density at x or FALSE for failure.
 *
 * @link https://www.php.net/manual/en/function.stats-dens-gamma.php
 * @param float $x
 * @param float $shape
 * @param float $scale
 * @return float|false
 */
function stats_dens_gamma(float $x, float $shape, float $scale) {}

/**
 * The probability density at x or FALSE for failure.
 *
 * @link https://www.php.net/manual/en/function.stats-dens-laplace.php
 * @param float $x
 * @param float $ave
 * @param float $stdev
 * @return float|false
 */
function stats_dens_laplace(float $x, float $ave, float $stdev) {}

/**
 * The probability density at x or FALSE for failure.
 *
 * @link https://www.php.net/manual/en/function.stats-dens-logistic.php
 * @param float $x
 * @param float $ave
 * @param float $stdev
 * @return float|false
 */
function stats_dens_logistic(float $x, float $ave, float $stdev) {}

/**
 * The probability density at x or FALSE for failure.
 *
 * @link https://www.php.net/manual/en/function.stats-dens-normal.php
 * @param float $x
 * @param float $ave
 * @param float $stdev
 * @return float|false
 */
function stats_dens_normal(float $x, float $ave, float $stdev) {}

/**
 * The probability mass at x or FALSE for failure.
 *
 * @link https://www.php.net/manual/en/function.stats-dens-pmf-binomial.php
 * @param float $x
 * @param float $n
 * @param float $pi
 * @return float|false
 */
function stats_dens_pmf_binomial(float $x, float $n, float $pi) {}

/**
 * The probability mass at n1 or FALSE for failure.
 *
 * @link https://www.php.net/manual/en/function.stats-dens-pmf-hypergeometric.php
 * @param float $n1
 * @param float $n2
 * @param float $N1
 * @param float $N2
 * @return float|false
 */
function stats_dens_pmf_hypergeometric(float $n1, float $n2, float $N1, float $N2) {}

/**
 * The probability mass at x or FALSE for failure.
 *
 * @link https://www.php.net/manual/en/function.stats-dens-pmf-negative-binomial.php
 * @param float $x
 * @param float $n
 * @param float $pi
 * @return float|false
 */
function stats_dens_pmf_negative_binomial(float $x, float $n, float $pi) {}

/**
 * The probability mass at x or FALSE for failure.
 *
 * @link https://www.php.net/manual/en/function.stats-dens-pmf-poisson.php
 * @param float $x
 * @param float $lb
 * @return float|false
 */
function stats_dens_pmf_poisson(float $x, float $lb) {}

/**
 * The probability density at x or FALSE for failure.
 *
 * @link https://www.php.net/manual/en/function.stats-dens-t.php
 * @param float $x
 * @param float $dfr
 * @return float|false
 */
function stats_dens_t(float $x, float $dfr) {}

/**
 * The probability density at x or FALSE for failure.
 *
 * @link https://www.php.net/manual/en/function.stats-dens-uniform.php
 * @param float $x
 * @param float $a
 * @param float $b
 * @return float|false
 */
function stats_dens_uniform(float $x, float $a, float $b) {}

/**
 * The probability density at x or FALSE for failure.
 *
 * @link https://www.php.net/manual/en/function.stats-dens-weibull.php
 * @param float $x
 * @param float $a
 * @param float $b
 * @return float|false
 */
function stats_dens_weibull(float $x, float $a, float $b) {}

/**
 * Returns the harmonic mean of the values in a, or FALSE if a is empty or is not an array.
 *
 * @link https://www.php.net/manual/en/function.stats-harmonic-mean.php
 * @param array $a
 * @return number|false
 */
function stats_harmonic_mean(array $a) {}

/**
 * Returns the kurtosis of the values in a, or FALSE if a is empty or is not an array.
 *
 * @link https://www.php.net/manual/en/function.stats-kurtosis.php
 * @param array $a
 * @return float|false
 */
function stats_kurtosis(array $a) {}

/**
 * A random deviate
 *
 * @link https://www.php.net/manual/en/function.stats-rand-gen-beta.php
 * @param float $a
 * @param float $b
 * @return float
 */
function stats_rand_gen_beta(float $a, float $b): float {}

/**
 * A random deviate
 *
 * @link https://www.php.net/manual/en/function.stats-rand-gen-chisquare.php
 * @param float $df
 * @return float
 */
function stats_rand_gen_chisquare(float $df): float {}

/**
 * A random deviate
 *
 * @link https://www.php.net/manual/en/function.stats-rand-gen-exponential.php
 * @param float $av
 * @return float
 */
function stats_rand_gen_exponential(float $av): float {}

/**
 * A random deviate
 *
 * @link https://www.php.net/manual/en/function.stats-rand-gen-f.php
 * @param float $dfn
 * @param float $dfd
 * @return float
 */
function stats_rand_gen_f(float $dfn, float $dfd): float {}

/**
 * A random deviate
 *
 * @link https://www.php.net/manual/en/function.stats-rand-gen-funiform.php
 * @param float $low
 * @param float $high
 * @return float
 */
function stats_rand_gen_funiform(float $low, float $high): float {}

/**
 * A random deviate
 *
 * @link https://www.php.net/manual/en/function.stats-rand-gen-gamma.php
 * @param float $a
 * @param float $r
 * @return float
 */
function stats_rand_gen_gamma(float $a, float $r): float {}

/**
 * A random deviate, which is the number of failure.
 *
 * @link https://www.php.net/manual/en/function.stats-rand-gen-ibinomial-negative.php
 * @param int $n
 * @param float $p
 * @return int
 */
function stats_rand_gen_ibinomial_negative(int $n, float $p): int {}

/**
 * A random deviate
 *
 * @link https://www.php.net/manual/en/function.stats-rand-gen-ibinomial.php
 * @param int $n
 * @param float $pp
 * @return int
 */
function stats_rand_gen_ibinomial(int $n, float $pp): int {}

/**
 * A random integer
 *
 * @link https://www.php.net/manual/en/function.stats-rand-gen-int.php
 * @return int
 */
function stats_rand_gen_int(): int {}

/**
 * A random deviate
 *
 * @link https://www.php.net/manual/en/function.stats-rand-gen-ipoisson.php
 * @param float $mu
 * @return int
 */
function stats_rand_gen_ipoisson(float $mu): int {}

/**
 * A random integer
 *
 * @link https://www.php.net/manual/en/function.stats-rand-gen-iuniform.php
 * @param int $low
 * @param int $high
 * @return int
 */
function stats_rand_gen_iuniform(int $low, int $high): int {}

/**
 * A random deviate
 *
 * @link https://www.php.net/manual/en/function.stats-rand-gen-noncentral-f.php
 * @param float $dfn
 * @param float $dfd
 * @param float $xnonc
 * @return float
 */
function stats_rand_gen_noncentral_f(float $dfn, float $dfd, float $xnonc): float {}

/**
 * A random deviate
 *
 * @link https://www.php.net/manual/en/function.stats-rand-gen-noncentral-t.php
 * @param float $df
 * @param float $xnonc
 * @return float
 */
function stats_rand_gen_noncentral_t(float $df, float $xnonc): float {}

/**
 * A random deviate
 *
 * @link https://www.php.net/manual/en/function.stats-rand-gen-normal.php
 * @param float $av
 * @param float $sd
 * @return float
 */
function stats_rand_gen_normal(float $av, float $sd): float {}

/**
 * A random deviate
 *
 * @link https://www.php.net/manual/en/function.stats-rand-gen-t.php
 * @param float $df
 * @return float
 */
function stats_rand_gen_t(float $df): float {}

/**
 * Returns an array of two integers.
 *
 * @link https://www.php.net/manual/en/function.stats-rand-get-seeds.php
 * @return int[]
 */
function stats_rand_get_seeds() {}

/**
 * Returns an array of two integers.
 *
 * @link https://www.php.net/manual/en/function.stats-rand-phrase-to-seeds.php
 * @param string $phrase
 * @return int[]
 */
function stats_rand_phrase_to_seeds(string $phrase) {}

/**
 * A random floating point number
 *
 * @link https://www.php.net/manual/en/function.stats-rand-ranf.php
 * @return float
 */
function stats_rand_ranf(): float {}

/**
 * No values are returned.
 *
 * @link https://www.php.net/manual/en/function.stats-rand-setall.php
 * @param int $iseed1
 * @param int $iseed2
 */
function stats_rand_setall(int $iseed1, int $iseed2): void {}

/**
 * Returns the skewness of the values in a, or FALSE if a is empty or is not an array.
 *
 * @link https://www.php.net/manual/en/function.stats-skew.php
 * @param array $a
 * @return float|false
 */
function stats_skew(array $a) {}

/**
 * Returns the standard deviation on success; FALSE on failure.
 * Raises an E_WARNING when there are fewer than 2 values in a.
 *
 * @link https://www.php.net/manual/en/function.stats-standard-deviation.php
 * @param array $a
 * @param bool $sample
 * @return float|false
 */
function stats_standard_deviation(array $a, bool $sample = false) {}

/**
 * Returns the binomial coefficient
 *
 * @link https://www.php.net/manual/en/function.stats-stat-binomial-coef.php
 * @param int $x
 * @param int $n
 * @return float
 */
function stats_stat_binomial_coef(int $x, int $n): float {}

/**
 * Returns the Pearson correlation coefficient between arr1 and arr2, or FALSE on failure.
 *
 * @link https://www.php.net/manual/en/function.stats-stat-correlation.php
 * @param array $arr1
 * @param array $arr2
 * @return float|false
 */
function stats_stat_correlation(array $arr1, array $arr2) {}

/**
 * The factorial of n.
 *
 * @link https://www.php.net/manual/en/function.stats-stat-factorial.php
 * @param int $n
 * @return float
 */
function stats_stat_factorial(int $n): float {}

/**
 * Returns the t-value, or FALSE if failure.
 *
 * @link https://www.php.net/manual/en/function.stats-stat-independent-t.php
 * @param array $arr1
 * @param array $arr2
 * @return float|false
 */
function stats_stat_independent_t(array $arr1, array $arr2) {}

/**
 * Returns the inner product of arr1 and arr2, or FALSE on failure.
 *
 * @link https://www.php.net/manual/en/function.stats-stat-innerproduct.php
 * @param array $arr1
 * @param array $arr2
 * @return float|false
 */
function stats_stat_innerproduct(array $arr1, array $arr2) {}

/**
 * Returns the t-value, or FALSE if failure.
 *
 * @link https://www.php.net/manual/en/function.stats-stat-paired-t.php
 * @param array $arr1
 * @param array $arr2
 * @return float|false
 */
function stats_stat_paired_t(array $arr1, array $arr2) {}

/**
 * Returns the percentile values of the input array.
 *
 * @link https://www.php.net/manual/en/function.stats-stat-percentile.php
 * @param array $array
 * @param float $perc
 * @return float
 */
function stats_stat_percentile(array $array, float $perc): float {}

/**
 * Returns the power sum of the input array.
 *
 * @link https://www.php.net/manual/en/function.stats-stat-powersum.php
 * @param array $array
 * @param float $power
 * @return float
 */
function stats_stat_powersum(array $array, float $power): float {}

/**
 * Returns the variance on success; FALSE on failure.
 *
 * @link https://www.php.net/manual/en/function.stats-variance.php
 * @param array $a
 * @param bool $sample
 * @return float|false
 */
function stats_variance(array $a, bool $sample = false) {}
