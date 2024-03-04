<?php
/**
 * Support Vector Machine Library

 * LibSVM is an efficient solver for SVM classification and regression problems. The svm extension wraps this in a PHP interface for easy use in PHP scripts.
 * @since 7.0
 * @link https://www.php.net/manual/en/class.svm.php
 */
class SVM
{
    /* Constants */
    /**
     * @const The basic C_SVC SVM type. The default, and a good starting point
     */
    public const C_SVC = 0;

    /**
     * @const NU_SVC type uses a different, more flexible, error weighting
     */
    public const NU_SVC = 1;

    /**
     * @const One class SVM type. Train just on a single class, using outliers as negative examples
     */
    public const ONE_CLASS = 2;

    /**
     * @const A SVM type for regression (predicting a value rather than just a class)
     */
    public const EPSILON_SVR = 3;

    /**
     * @const A NU style SVM regression type
     */
    public const NU_SVR = 4;

    /**
     * @const A very simple kernel, can work well on large document classification problems
     */
    public const KERNEL_LINEAR = 0;

    /**
     * @const A polynomial kernel
     */
    public const KERNEL_POLY = 1;

    /**
     * @const The common Gaussian RBD kernel. Handles non-linear problems well and is a good default for classification
     */
    public const KERNEL_RBF = 2;

    /**
     * @const A kernel based on the sigmoid function. Using this makes the SVM very similar to a two layer sigmoid based neural network
     */
    public const KERNEL_SIGMOID = 3;

    /**
     * @const A precomputed kernel - currently unsupported.
     */
    public const KERNEL_PRECOMPUTED = 4;

    /**
     * @const The options key for the SVM type
     */
    public const OPT_TYPE = 101;

    /**
     * @const The options key for the kernel type
     */
    public const OPT_KERNEL_TYPE = 102;

    /**
     * @const OPT_DEGREE
     */
    public const OPT_DEGREE = 103;

    /**
     * @const Training parameter, boolean, for whether to use the shrinking heuristics
     */
    public const OPT_SHRINKING = 104;

    /**
     * @const Training parameter, boolean, for whether to collect and use probability estimates
     */
    public const OPT_PROPABILITY = 105;

    /**
     * @const Algorithm parameter for Poly, RBF and Sigmoid kernel types.
     */
    public const OPT_GAMMA = 201;

    /**
     * @const The option key for the nu parameter, only used in the NU_ SVM types
     */
    public const OPT_NU = 202;

    /**
     * @const The option key for the Epsilon parameter, used in epsilon regression
     */
    public const OPT_EPS = 203;

    /**
     * @const Training parameter used by Episilon SVR regression
     */
    public const OPT_P = 204;

    /**
     * @const Algorithm parameter for poly and sigmoid kernels
     */
    public const OPT_COEF_ZERO = 205;

    /**
     * @const The option for the cost parameter that controls tradeoff between errors and generality - effectively the penalty for misclassifying training examples.
     */
    public const OPT_C = 206;

    /**
     * @const Memory cache size, in MB
     */
    public const OPT_CACHE_SIZE = 207;

    /* Methods */
    /**
     * Construct a new SVM object
     *
     * Constructs a new SVM object ready to accept training data.
     * @throws SVMException Throws SVMException if the libsvm library could not be loaded
     * @link https://www.php.net/manual/en/svm.construct.php
     */
    public function __construct() {}

    /**
     * Test training params on subsets of the training data
     *
     * Crossvalidate can be used to test the effectiveness of the current parameter set on a subset of the training data. Given a problem set and a n "folds", it separates the problem set into n subsets, and the repeatedly trains on one subset and tests on another. While the accuracy will generally be lower than a SVM trained on the enter data set, the accuracy score returned should be relatively useful, so it can be used to test different training parameters.
     * @param array $problem The problem data. This can either be in the form of an array, the URL of an SVMLight formatted file, or a stream to an opened SVMLight formatted datasource.
     * @param int $number_of_folds The number of sets the data should be divided into and cross tested. A higher number means smaller training sets and less reliability. 5 is a good number to start with.
     * @return float The correct percentage, expressed as a floating point number from 0-1. In the case of NU_SVC or EPSILON_SVR kernels the mean squared error will returned instead.
     * @link https://www.php.net/manual/en/svm.crossvalidate.php
     */
    public function crossvalidate(array $problem, int $number_of_folds): float {}

    /**
     * Return the current training parameters
     *
     * Retrieve an array containing the training parameters. The parameters will be keyed on the predefined SVM constants.
     * @return array Returns an array of configuration settings.
     * @link https://www.php.net/manual/en/svm.getoptions.php
     */
    public function getOptions(): array {}

    /**
     * Set training parameters
     *
     * Set one or more training parameters.
     * @param array $params An array of training parameters, keyed on the SVM constants.
     * @return bool Return true on success, throws SVMException on error.
     * @throws SVMException
     * @link https://www.php.net/manual/en/svm.setoptions.php
     */
    public function setOptions(array $params): bool {}

    /**
     * Create a SVMModel based on training data
     *
     * Train a support vector machine based on the supplied training data.
     * @param array $problem The problem can be provided in three different ways. An array, where the data should start with the class label (usually 1 or -1) then followed by a sparse data set of dimension => data pairs. A URL to a file containing a SVM Light formatted problem, with the each line being a new training example, the start of each line containing the class (1, -1) then a series of tab separated data values shows as key:value. A opened stream pointing to a data source formatted as in the file above.
     * @param array|null $weights Weights are an optional set of weighting parameters for the different classes, to help account for unbalanced training sets. For example, if the classes were 1 and -1, and -1 had significantly more example than one, the weight for -1 could be 0.5. Weights should be in the range 0-1.
     * @return SVMModel Returns an SVMModel that can be used to classify previously unseen data. Throws SVMException on error
     * @throws SMVException
     * @link https://www.php.net/manual/en/svm.train.php
     */
    public function train(array $problem, array $weights = null): SVMModel {}
}
