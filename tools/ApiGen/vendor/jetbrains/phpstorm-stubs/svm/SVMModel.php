<?php

/**
 * The SVMModel is the end result of the training process. It can be used to classify previously unseen data.
 * @since 7.0
 * @link https://www.php.net/manual/en/class.svmmodel.php
 */
class SVMModel
{
    /* Methods */
    /**
     * Returns true if the model has probability information
     *
     * @return bool Return a boolean value
     * @link https://www.php.net/manual/en/svmmodel.checkprobabilitymodel.php
     */
    public function checkProbabilityModel(): bool {}

    /**
     * Construct a new SVMModel
     *
     * Build a new SVMModel. Models will usually be created from the SVM::train function, but then saved models may be restored directly.
     * @param string $filename The filename for the saved model file this model should load.
     * @throws Throws SVMException on error
     * @link https://www.php.net/manual/en/svmmodel.construct.php
     */
    public function __construct(string $filename = '') {}

    /**
     * Get the labels the model was trained on
     *
     * Return an array of labels that the model was trained on. For regression and one class models an empty array is returned.
     * @return array Return an array of labels
     * @link https://www.php.net/manual/en/svmmodel.getlabels.php
     */
    public function getLabels(): array {}

    /**
     * Returns the number of classes the model was trained with
     *
     * Returns the number of classes the model was trained with, will return 2 for one class and regression models.
     * @return int Return an integer number of classes
     * @link https://www.php.net/manual/en/svmmodel.getnrclass.php
     */
    public function getNrClass(): int {}

    /**
     * Get the SVM type the model was trained with
     *
     * Returns an integer value representing the type of the SVM model used, e.g SVM::C_SVC.
     * @return int Return an integer SVM type
     * @link https://www.php.net/manual/en/svmmodel.getsvmtype.php
     */
    public function getSvmType(): int {}

    /**
     * Get the sigma value for regression types
     *
     * For regression models, returns a sigma value. If there is no probability information or the model is not SVR, 0 is returned.
     * @return float Returns a sigma value
     * @link https://www.php.net/manual/en/svmmodel.getsvrprobability.php
     */
    public function getSvrProbability(): float {}

    /**
     * Load a saved SVM Model
     * @param string $filename The filename of the model.
     * @return bool Returns true on success.
     * @throws SVMException
     * @link https://www.php.net/manual/en/svmmodel.load.php
     */
    public function load(string $filename): bool {}

    /**
     * Return class probabilities for previous unseen data
     *
     * This function accepts an array of data and attempts to predict the class, as with the predict function. Additionally, however, this function returns an array of probabilities, one per class in the model, which represent the estimated chance of the data supplied being a member of that class. Requires that the model to be used has been trained with the probability parameter set to true.
     * @param array $data The array to be classified. This should be a series of key => value pairs in increasing key order, but not necessarily continuous.
     * @return float the predicted value. This will be a class label in the case of classification, a real value in the case of regression. Throws SVMException on error
     * @throws SVMException Throws SVMException on error
     * @link https://www.php.net/manual/en/svmmodel.predict-probability.php
     */
    public function predict_probability(array $data): float {}

    /**
     * Predict a value for previously unseen data
     *
     * This function accepts an array of data and attempts to predict the class or regression value based on the model extracted from previously trained data.
     * @param array $data The array to be classified. This should be a series of key => value pairs in increasing key order, but not necessarily continuous.
     * @return float the predicted value. This will be a class label in the case of classification, a real value in the case of regression. Throws SVMException on error
     * @throws SVMException Throws SVMException on error
     * @link https://www.php.net/manual/en/svmmodel.predict.php
     */
    public function predict(array $data): float {}

    /**
     * Save a model to a file, for later use
     * @param string $filename The file to save the model to.
     * @return bool Throws SVMException on error. Returns true on success.
     * @throws SVMException Throws SVMException on error
     * @link https://www.php.net/manual/en/svmmodel.save.php
     */
    public function save(string $filename): bool {}
}
