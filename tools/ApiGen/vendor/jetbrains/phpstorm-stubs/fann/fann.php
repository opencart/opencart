<?php

// Start of Fann v.1

/**
 * Class FANNConnection
 */
class FANNConnection
{
    /**
     * @var float
     */
    public $weight;

    /**
     * @var int
     */
    public $to_neuron;

    /**
     * @var int
     */
    public $from_neuron;

    /**
     * The connection constructor
     *
     * @param int $from_neuron
     * @param int $to_neuron
     * @param float $weight
     */
    public function __construct($from_neuron, $to_neuron, $weight) {}

    /**
     * Returns the postions of starting neuron.
     *
     * @return int The postions of starting neuron.
     */
    public function getFromNeuron() {}

    /**
     * Returns the postions of terminating neuron
     *
     * @return int The postions of terminating neuron.
     */
    public function getToNeuron() {}

    /**
     * Returns the connection weight
     *
     * @return void The connection weight.
     */
    public function getWeight() {}

    /**
     * Sets the connections weight
     *
     * @param float $weight
     *
     * @return bool
     */
    public function setWeight($weight) {}
}

/**
 * Trains on an entire dataset, for a period of time using the Cascade2 training algorithm
 *
 *
 * @param resource $ann
 * @param resource $data
 * @param int $max_neurons
 * @param int $neurons_between_reports
 * @param float $desired_error
 *
 * @return bool
 */
function fann_cascadetrain_on_data($ann, $data, $max_neurons, $neurons_between_reports, $desired_error) {}

/**
 * Trains on an entire dataset read from file, for a period of time using the Cascade2 training algorithm.
 *
 *
 * @param resource $ann
 * @param string $filename
 * @param int $max_neurons
 * @param int $neurons_between_reports
 * @param float $desired_error
 *
 * @return bool
 */
function fann_cascadetrain_on_file($ann, $filename, $max_neurons, $neurons_between_reports, $desired_error) {}

/**
 * Clears scaling parameters
 *
 *
 * @param resource $ann
 *
 * @return bool
 */
function fann_clear_scaling_params($ann) {}

/**
 * Creates a copy of a fann structure
 *
 *
 * @param resource $ann
 *
 * @return resource|false Returns a copy of neural network resource on success, or false on error
 */
function fann_copy($ann) {}

/**
 * Constructs a backpropagation neural network from a configuration file
 *
 *
 * @param string $configuration_file
 *
 * @return resource|false
 */
function fann_create_from_file($configuration_file) {}

/**
 * Creates a standard backpropagation neural network which is not fully connectected and has shortcut connections
 *
 *
 * @param int $num_layers
 * @param array $layers
 *
 * @return resource|false Returns a neural network resource on success, or false on error.
 */
function fann_create_shortcut_array($num_layers, $layers) {}

/**
 * Creates a standard backpropagation neural network which is not fully connectected and has shortcut connections
 *
 * @param int $num_layers
 * @param int $num_neurons1
 * @param int $num_neurons2
 * @param int ...$_
 *
 * @return resource|false Returns a neural network resource on success, or false on error.
 */
function fann_create_shortcut($num_layers, $num_neurons1, $num_neurons2, ...$_) {}

/**
 * Creates a standard backpropagation neural network, which is not fully connected using an array of layer sizes
 *
 *
 * @param float $connection_rate
 * @param int $num_layers
 * @param array $layers
 *
 * @return resource|false Returns a neural network resource on success, or false on error.
 */
function fann_create_sparse_array($connection_rate, $num_layers, $layers) {}

/**
 * Creates a standard backpropagation neural network, which is not fully connected
 *
 *
 * @param float $connection_rate
 * @param int $num_layers
 * @param int $num_neurons1
 * @param int $num_neurons2
 * @param int ...$_
 *
 * @return resource|false Returns a neural network resource on success, or false on error.
 */
function fann_create_sparse($connection_rate, $num_layers, $num_neurons1, $num_neurons2, ...$_) {}

/**
 * Creates a standard fully connected backpropagation neural network using an array of layer sizes
 *
 *
 * @param int $num_layers
 * @param array $layers
 *
 * @return resource|false Returns a neural network resource on success, or false on error.
 */
function fann_create_standard_array($num_layers, $layers) {}

/**
 * Creates a standard fully connected backpropagation neural network
 *
 *
 * @param int $num_layers
 * @param int $num_neurons1
 * @param int $num_neurons2
 * @param int ...$_
 *
 * @return resource|false Returns a neural network resource on success, or false on error.
 */
function fann_create_standard($num_layers, $num_neurons1, $num_neurons2, ...$_) {}

/**
 * Creates the training data struct from a user supplied function
 *
 *
 * @param int $num_data
 * @param int $num_input
 * @param int $num_output
 * @param callable $user_function
 *
 * @return resource|false
 */
function fann_create_train_from_callback($num_data, $num_input, $num_output, $user_function) {}

/**
 * Creates an empty training data struct
 *
 *
 * @param int $num_data
 * @param int $num_input
 * @param int $num_output
 *
 * @return resource|false
 */
function fann_create_train($num_data, $num_input, $num_output) {}

/**
 * Scale data in input vector after get it from ann based on previously calculated parameters
 *
 *
 * @param resource $ann
 * @param array $input_vector
 *
 * @return bool
 */
function fann_descale_input($ann, $input_vector) {}

/**
 * Scale data in output vector after get it from ann based on previously calculated parameters
 *
 *
 * @param resource $ann
 * @param array $output_vector
 *
 * @return bool
 */
function fann_descale_output($ann, $output_vector) {}

/**
 * Descale input and output data based on previously calculated parameters
 *
 *
 * @param resource $ann
 * @param resource $train_data
 *
 * @return bool
 */
function fann_descale_train($ann, $train_data) {}

/**
 * Destroys the entire network and properly freeing all the associated memory
 *
 *
 * @param resource $ann
 *
 * @return bool
 */
function fann_destroy($ann) {}

/**
 * Destructs the training data
 *
 *
 * @param resource $train_data
 *
 * @return bool
 */
function fann_destroy_train($train_data) {}

/**
 * Returns an exact copy of a fann train data
 *
 *
 * @param resource $data
 *
 * @return resource|false
 */
function fann_duplicate_train_data($data) {}

/**
 * Returns the activation function
 *
 *
 * @param resource $ann
 * @param int $layer
 * @param int $neuron
 *
 * @return int|false constant or -1 if the neuron is not defined in the neural network, or false on error.
 */
function fann_get_activation_function($ann, $layer, $neuron) {}

/**
 * Returns the activation steepness for supplied neuron and layer number
 *
 *
 * @param resource $ann
 * @param int $layer
 * @param int $neuron
 *
 * @return float|false The activation steepness for the neuron or -1 if the neuron is not defined in the neural network, or false on error.
 */
function fann_get_activation_steepness($ann, $layer, $neuron) {}

/**
 * Get the number of bias in each layer in the network
 *
 *
 * @param resource $ann
 *
 * @return array An array of numbers of bias in each layer
 */
function fann_get_bias_array($ann) {}

/**
 * Returns the bit fail limit used during training
 *
 *
 * @param resource $ann
 *
 * @return float|false The bit fail limit, or false on error.
 */
function fann_get_bit_fail_limit($ann) {}

/**
 * The number of fail bits
 *
 *
 * @param resource $ann
 *
 * @return int|false The number of bits fail, or false on error.
 */
function fann_get_bit_fail($ann) {}

/**
 * Returns the number of cascade activation functions
 *
 *
 * @param resource $ann
 *
 * @return int|false The number of cascade activation functions, or false on error.
 */
function fann_get_cascade_activation_functions_count($ann) {}

/**
 * Returns the cascade activation functions
 *
 *
 * @param resource $ann
 *
 * @return array|false The cascade activation functions, or false on error.
 */
function fann_get_cascade_activation_functions($ann) {}

/**
 * The number of activation steepnesses
 *
 *
 * @param resource $ann
 *
 * @return int|false The number of activation steepnesses, or false on error.
 */
function fann_get_cascade_activation_steepnesses_count($ann) {}

/**
 * Returns the cascade activation steepnesses
 *
 *
 * @param resource $ann
 *
 * @return array|false The cascade activation steepnesses, or false on error.
 */
function fann_get_cascade_activation_steepnesses($ann) {}

/**
 * Returns the cascade candidate change fraction
 *
 *
 * @param resource $ann
 *
 * @return float|false The cascade candidate change fraction, or false on error.
 */
function fann_get_cascade_candidate_change_fraction($ann) {}

/**
 * Return the candidate limit
 *
 *
 * @param resource $ann
 *
 * @return float|false The candidate limit, or false on error.
 */
function fann_get_cascade_candidate_limit($ann) {}

/**
 * Returns the number of cascade candidate stagnation epochs
 *
 *
 * @param resource $ann
 *
 * @return float|false The number of cascade candidate stagnation epochs, or false on error.
 */
function fann_get_cascade_candidate_stagnation_epochs($ann) {}

/**
 * Returns the maximum candidate epochs
 *
 *
 * @param resource $ann
 *
 * @return int|false The maximum candidate epochs, or false on error.
 */
function fann_get_cascade_max_cand_epochs($ann) {}

/**
 * Returns the maximum out epochs
 *
 *
 * @param resource $ann
 *
 * @return int|false The maximum out epochs, or false on error.
 */
function fann_get_cascade_max_out_epochs($ann) {}

/**
 * Returns the minimum candidate epochs
 *
 *
 * @param resource $ann
 *
 * @return int|false The minimum candidate epochs, or false on error.
 */
function fann_get_cascade_min_cand_epochs($ann) {}

/**
 * Returns the minimum out epochs
 *
 *
 * @param resource $ann
 *
 * @return int|false The minimum out epochs, or false on error.
 */
function fann_get_cascade_min_out_epochs($ann) {}

/**
 * Returns the number of candidate groups
 *
 *
 * @param resource $ann
 *
 * @return int|false The number of candidate groups, or false on error.
 */
function fann_get_cascade_num_candidate_groups($ann) {}

/**
 * Returns the number of candidates used during training
 *
 *
 * @param resource $ann
 *
 * @return int|false The number of candidates used during training, or false on error.
 */
function fann_get_cascade_num_candidates($ann) {}

/**
 * Returns the cascade output change fraction
 *
 *
 * @param resource $ann
 *
 * @return float|false The cascade output change fraction, or false on error.
 */
function fann_get_cascade_output_change_fraction($ann) {}

/**
 * Returns the number of cascade output stagnation epochs
 *
 *
 * @param resource $ann
 *
 * @return int|false The number of cascade output stagnation epochs, or false on error.
 */
function fann_get_cascade_output_stagnation_epochs($ann) {}

/**
 * Returns the weight multiplier
 *
 *
 * @param resource $ann
 *
 * @return float|false The weight multiplier, or false on error.
 */
function fann_get_cascade_weight_multiplier($ann) {}

/**
 * Get connections in the network
 *
 *
 * @param resource $ann
 *
 * @return array An array of connections in the network
 */
function fann_get_connection_array($ann) {}

/**
 * Get the connection rate used when the network was created
 *
 *
 * @param resource $ann
 *
 * @return float|false The connection rate used when the network was created, or false on error.
 */
function fann_get_connection_rate($ann) {}

/**
 * Returns the last error number
 *
 *
 * @param resource $errdat
 *
 * @return int|false The error number, or false on error.
 */
function fann_get_errno($errdat) {}

/**
 * Returns the last errstr
 *
 *
 * @param resource $errdat
 *
 * @return string|false The last error string, or false on error.
 */
function fann_get_errstr($errdat) {}

/**
 * Get the number of neurons in each layer in the network
 *
 *
 * @param resource $ann
 *
 * @return array An array of numbers of neurons in each leayer
 */
function fann_get_layer_array($ann) {}

/**
 * Returns the learning momentum
 *
 *
 * @param resource $ann
 *
 * @return float|false The learning momentum, or false on error.
 */
function fann_get_learning_momentum($ann) {}

/**
 * Returns the learning rate
 *
 *
 * @param resource $ann
 *
 * @return float|false The learning rate, or false on error.
 */
function fann_get_learning_rate($ann) {}

/**
 * Reads the mean square error from the network
 *
 *
 * @param resource $ann
 *
 * @return float|false The mean square error, or false on error.
 */
function fann_get_MSE($ann) {}

/**
 * Get the type of neural network it was created as
 *
 *
 * @param resource $ann
 *
 * @return int|false constant, or false on error.
 */
function fann_get_network_type($ann) {}

/**
 * Get the number of input neurons
 *
 *
 * @param resource $ann
 *
 * @return int|false Number of input neurons, or false on error
 */
function fann_get_num_input($ann) {}

/**
 * Get the number of layers in the neural network
 *
 *
 * @param resource $ann
 *
 * @return int|false The number of leayers in the neural network, or false on error.
 */
function fann_get_num_layers($ann) {}

/**
 * Get the number of output neurons
 *
 *
 * @param resource $ann
 *
 * @return int|false Number of output neurons, or false on error
 */
function fann_get_num_output($ann) {}

/**
 * Returns the decay which is a factor that weights should decrease in each iteration during quickprop training
 *
 *
 * @param resource $ann
 *
 * @return float|false The decay, or false on error.
 */
function fann_get_quickprop_decay($ann) {}

/**
 * Returns the mu factor
 *
 *
 * @param resource $ann
 *
 * @return float|false The mu factor, or false on error.
 */
function fann_get_quickprop_mu($ann) {}

/**
 * Returns the increase factor used during RPROP training
 *
 *
 * @param resource $ann
 *
 * @return float|false The decrease factor, or false on error.
 */
function fann_get_rprop_decrease_factor($ann) {}

/**
 * Returns the maximum step-size
 *
 *
 * @param resource $ann
 *
 * @return float|false The maximum step-size, or false on error.
 */
function fann_get_rprop_delta_max($ann) {}

/**
 * Returns the minimum step-size
 *
 *
 * @param resource $ann
 *
 * @return float|false The minimum step-size, or false on error.
 */
function fann_get_rprop_delta_min($ann) {}

/**
 * Returns the initial step-size
 *
 *
 * @param resource $ann
 *
 * @return int|false The initial step-size, or false on error.
 */
function fann_get_rprop_delta_zero($ann) {}

/**
 * Returns the increase factor used during RPROP training
 *
 *
 * @param resource $ann
 *
 * @return float|false The increase factor, or false on error.
 */
function fann_get_rprop_increase_factor($ann) {}

/**
 * Returns the sarprop step error shift
 *
 *
 * @param resource $ann
 *
 * @return float|false The sarprop step error shift , or false on error.
 */
function fann_get_sarprop_step_error_shift($ann) {}

/**
 * Returns the sarprop step error threshold factor
 *
 *
 * @param resource $ann
 *
 * @return float|false The sarprop step error threshold factor, or false on error.
 */
function fann_get_sarprop_step_error_threshold_factor($ann) {}

/**
 * Returns the sarprop temperature
 *
 *
 * @param resource $ann
 *
 * @return float|false The sarprop temperature, or false on error.
 */
function fann_get_sarprop_temperature($ann) {}

/**
 * Returns the sarprop weight decay shift
 *
 *
 * @param resource $ann
 *
 * @return float|false The sarprop weight decay shift, or false on error.
 */
function fann_get_sarprop_weight_decay_shift($ann) {}

/**
 * Get the total number of connections in the entire network
 *
 *
 * @param resource $ann
 *
 * @return int|false Total number of connections in the entire network, or false on error
 */
function fann_get_total_connections($ann) {}

/**
 * Get the total number of neurons in the entire network
 *
 *
 * @param resource $ann
 *
 * @return int|false Total number of neurons in the entire network, or false on error.
 */
function fann_get_total_neurons($ann) {}

/**
 * Returns the error function used during training
 *
 *
 * @param resource $ann
 *
 * @return int|false The constant, or false on error.
 */
function fann_get_train_error_function($ann) {}

/**
 * Returns the training algorithm
 *
 *
 * @param resource $ann
 *
 * @return int|false constant, or false on error.
 */
function fann_get_training_algorithm($ann) {}

/**
 * Returns the stop function used during training
 *
 *
 * @param resource $ann
 *
 * @return int|false The constant, or false on error.
 */
function fann_get_train_stop_function($ann) {}

/**
 * Initialize the weights using Widrow + Nguyen’s algorithm
 *
 *
 * @param resource $ann
 * @param resource $train_data
 *
 * @return bool
 */
function fann_init_weights($ann, $train_data) {}

/**
 * Returns the number of training patterns in the train data
 *
 *
 * @param resource $data
 *
 * @return int|false Number of elements in the train data ``resource``, or false on error.
 */
function fann_length_train_data($data) {}

/**
 * Merges the train data
 *
 *
 * @param resource $data1
 * @param resource $data2
 *
 * @return resource|false New merged train data ``resource``, or false on error.
 */
function fann_merge_train_data($data1, $data2) {}

/**
 * Returns the number of inputs in each of the training patterns in the train data
 *
 *
 * @param resource $data
 *
 * @return int|false The number of inputs, or false on error.
 */
function fann_num_input_train_data($data) {}

/**
 * Returns the number of outputs in each of the training patterns in the train data
 *
 *
 * @param resource $data
 *
 * @return int|false The number of outputs, or false on error.
 */
function fann_num_output_train_data($data) {}

/**
 * Prints the error string
 * @link https://www.php.net/manual/en/function.fann-print-error.php
 *
 * @param resource $errdat
 *
 * @return void
 */
function fann_print_error($errdat) {}

/**
 * Give each connection a random weight between min_weight and max_weight
 *
 *
 * @param resource $ann
 * @param float $min_weight
 * @param float $max_weight
 *
 * @return bool
 */
function fann_randomize_weights($ann, $min_weight, $max_weight) {}

/**
 * Reads a file that stores training data
 *
 *
 * @param string $filename
 *
 * @return resource|false
 */
function fann_read_train_from_file($filename) {}

/**
 * Resets the last error number
 *
 *
 * @param resource $errdat
 *
 * @return void
 */
function fann_reset_errno($errdat) {}

/**
 * Resets the last error string
 *
 *
 * @param resource $errdat
 *
 * @return void
 */
function fann_reset_errstr($errdat) {}

/**
 * Resets the mean square error from the network
 *
 *
 * @param string $ann
 *
 * @return bool
 */
function fann_reset_MSE($ann) {}

/**
 * Will run input through the neural network
 *
 *
 * @param resource $ann
 * @param array $input
 *
 * @return array|false Array of output values, or false on error
 */
function fann_run($ann, $input) {}

/**
 * Saves the entire network to a configuration file
 *
 *
 * @param resource $ann
 * @param string $configuration_file
 *
 * @return bool
 */
function fann_save($ann, $configuration_file) {}

/**
 * Save the training structure to a file
 *
 *
 * @param resource $data
 * @param string $file_name
 *
 * @return bool
 */
function fann_save_train($data, $file_name) {}

/**
 * Scale data in input vector before feed it to ann based on previously calculated parameters
 *
 *
 * @param resource $ann
 * @param array $input_vector
 *
 * @return bool
 */
function fann_scale_input($ann, $input_vector) {}

/**
 * Scales the inputs in the training data to the specified range
 *
 *
 * @param resource $train_data
 * @param float $new_min
 * @param float $new_max
 *
 * @return bool
 */
function fann_scale_input_train_data($train_data, $new_min, $new_max) {}

/**
 * Scale data in output vector before feed it to ann based on previously calculated parameters
 *
 *
 * @param resource $ann
 * @param array $output_vector
 *
 * @return bool
 */
function fann_scale_output($ann, $output_vector) {}

/**
 * Scales the outputs in the training data to the specified range
 *
 *
 * @param resource $train_data
 * @param float $new_min
 * @param float $new_max
 *
 * @return bool
 */
function fann_scale_output_train_data($train_data, $new_min, $new_max) {}

/**
 * Scales the inputs and outputs in the training data to the specified range
 *
 *
 * @param resource $train_data
 * @param float $new_min
 * @param float $new_max
 *
 * @return bool
 */
function fann_scale_train_data($train_data, $new_min, $new_max) {}

/**
 * Scale input and output data based on previously calculated parameters
 *
 *
 * @param resource $ann
 * @param resource $train_data
 *
 * @return bool
 */
function fann_scale_train($ann, $train_data) {}

/**
 * Sets the activation function for all of the hidden layers
 *
 *
 * @param resource $ann
 * @param int $activation_function
 *
 * @return bool
 */
function fann_set_activation_function_hidden($ann, $activation_function) {}

/**
 * Sets the activation function for all the neurons in the supplied layer.
 *
 *
 * @param resource $ann
 * @param int $activation_function
 * @param int $layer
 *
 * @return bool
 */
function fann_set_activation_function_layer($ann, $activation_function, $layer) {}

/**
 * Sets the activation function for the output layer
 *
 *
 * @param resource $ann
 * @param int $activation_function
 *
 * @return bool
 */
function fann_set_activation_function_output($ann, $activation_function) {}

/**
 * Sets the activation function for supplied neuron and layer
 *
 *
 * @param resource $ann
 * @param int $activation_function
 * @param int $layer
 * @param int $neuron
 *
 * @return bool
 */
function fann_set_activation_function($ann, $activation_function, $layer, $neuron) {}

/**
 * Sets the steepness of the activation steepness for all neurons in the all hidden layers
 *
 *
 * @param resource $ann
 * @param float $activation_steepness
 *
 * @return bool
 */
function fann_set_activation_steepness_hidden($ann, $activation_steepness) {}

/**
 * Sets the activation steepness for all of the neurons in the supplied layer number
 *
 *
 * @param resource $ann
 * @param float $activation_steepness
 * @param int $layer
 *
 * @return bool
 */
function fann_set_activation_steepness_layer($ann, $activation_steepness, $layer) {}

/**
 * Sets the steepness of the activation steepness in the output layer
 *
 *
 * @param resource $ann
 * @param float $activation_steepness
 *
 * @return bool
 */
function fann_set_activation_steepness_output($ann, $activation_steepness) {}

/**
 * Sets the activation steepness for supplied neuron and layer number
 *
 *
 * @param resource $ann
 * @param float $activation_steepness
 * @param int $layer
 * @param int $neuron
 *
 * @return bool
 */
function fann_set_activation_steepness($ann, $activation_steepness, $layer, $neuron) {}

/**
 * Set the bit fail limit used during training
 *
 *
 * @param resource $ann
 * @param float $bit_fail_limit
 *
 * @return bool
 */
function fann_set_bit_fail_limit($ann, $bit_fail_limit) {}

/**
 * Sets the callback function for use during training
 *
 *
 * @param resource $ann
 * @param callable $callback
 *
 * @return bool
 */
function fann_set_callback($ann, $callback) {}

/**
 * Sets the array of cascade candidate activation functions
 *
 *
 * @param resource $ann
 * @param array $cascade_activation_functions
 *
 * @return bool
 */
function fann_set_cascade_activation_functions($ann, $cascade_activation_functions) {}

/**
 * Sets the array of cascade candidate activation steepnesses
 *
 *
 * @param resource $ann
 * @param array $cascade_activation_steepnesses_count
 *
 * @return bool
 */
function fann_set_cascade_activation_steepnesses($ann, $cascade_activation_steepnesses_count) {}

/**
 * Sets the cascade candidate change fraction
 *
 *
 * @param resource $ann
 * @param float $cascade_candidate_change_fraction
 *
 * @return bool
 */
function fann_set_cascade_candidate_change_fraction($ann, $cascade_candidate_change_fraction) {}

/**
 * Sets the candidate limit
 *
 *
 * @param resource $ann
 * @param float $cascade_candidate_limit
 *
 * @return bool
 */
function fann_set_cascade_candidate_limit($ann, $cascade_candidate_limit) {}

/**
 * Sets the number of cascade candidate stagnation epochs
 *
 *
 * @param resource $ann
 * @param int $cascade_candidate_stagnation_epochs
 *
 * @return bool
 */
function fann_set_cascade_candidate_stagnation_epochs($ann, $cascade_candidate_stagnation_epochs) {}

/**
 * Sets the max candidate epochs
 *
 *
 * @param resource $ann
 * @param int $cascade_max_cand_epochs
 *
 * @return bool
 */
function fann_set_cascade_max_cand_epochs($ann, $cascade_max_cand_epochs) {}

/**
 * Sets the maximum out epochs
 *
 *
 * @param resource $ann
 * @param int $cascade_max_out_epochs
 *
 * @return bool
 */
function fann_set_cascade_max_out_epochs($ann, $cascade_max_out_epochs) {}

/**
 * Sets the min candidate epochs
 *
 *
 * @param resource $ann
 * @param int $cascade_min_cand_epochs
 *
 * @return bool
 */
function fann_set_cascade_min_cand_epochs($ann, $cascade_min_cand_epochs) {}

/**
 * Sets the minimum out epochs
 *
 *
 * @param resource $ann
 * @param int $cascade_min_out_epochs
 *
 * @return bool
 */
function fann_set_cascade_min_out_epochs($ann, $cascade_min_out_epochs) {}

/**
 * Sets the number of candidate groups
 *
 *
 * @param resource $ann
 * @param int $cascade_num_candidate_groups
 *
 * @return bool
 */
function fann_set_cascade_num_candidate_groups($ann, $cascade_num_candidate_groups) {}

/**
 * Sets the cascade output change fraction
 *
 *
 * @param resource $ann
 * @param float $cascade_output_change_fraction
 *
 * @return bool
 */
function fann_set_cascade_output_change_fraction($ann, $cascade_output_change_fraction) {}

/**
 * Sets the number of cascade output stagnation epochs
 *
 *
 * @param resource $ann
 * @param int $cascade_output_stagnation_epochs
 *
 * @return bool
 */
function fann_set_cascade_output_stagnation_epochs($ann, $cascade_output_stagnation_epochs) {}

/**
 * Sets the weight multiplier
 *
 *
 * @param resource $ann
 * @param float $cascade_weight_multiplier
 *
 * @return bool
 */
function fann_set_cascade_weight_multiplier($ann, $cascade_weight_multiplier) {}

/**
 * Sets where the errors are logged to
 *
 *
 * @param resource $errdat
 * @param string $log_file
 *
 * @return void
 */
function fann_set_error_log($errdat, $log_file) {}

/**
 * Calculate input scaling parameters for future use based on training data
 *
 *
 * @param resource $ann
 * @param resource $train_data
 * @param float $new_input_min
 * @param float $new_input_max
 *
 * @return bool
 */
function fann_set_input_scaling_params($ann, $train_data, $new_input_min, $new_input_max) {}

/**
 * Sets the learning momentum
 *
 *
 * @param resource $ann
 * @param float $learning_momentum
 *
 * @return bool
 */
function fann_set_learning_momentum($ann, $learning_momentum) {}

/**
 * Sets the learning rate
 *
 *
 * @param resource $ann
 * @param float $learning_rate
 *
 * @return bool
 */
function fann_set_learning_rate($ann, $learning_rate) {}

/**
 * Calculate output scaling parameters for future use based on training data
 *
 *
 * @param resource $ann
 * @param resource $train_data
 * @param float $new_output_min
 * @param float $new_output_max
 *
 * @return bool
 */
function fann_set_output_scaling_params($ann, $train_data, $new_output_min, $new_output_max) {}

/**
 * Sets the quickprop decay factor
 *
 *
 * @param resource $ann
 * @param float $quickprop_decay
 *
 * @return bool
 */
function fann_set_quickprop_decay($ann, $quickprop_decay) {}

/**
 * Sets the quickprop mu factor
 *
 *
 * @param resource $ann
 * @param float $quickprop_mu
 *
 * @return bool
 */
function fann_set_quickprop_mu($ann, $quickprop_mu) {}

/**
 * Sets the decrease factor used during RPROP training
 *
 *
 * @param resource $ann
 * @param float $rprop_decrease_factor
 *
 * @return bool
 */
function fann_set_rprop_decrease_factor($ann, $rprop_decrease_factor) {}

/**
 * Sets the maximum step-size
 *
 *
 * @param resource $ann
 * @param float $rprop_delta_max
 *
 * @return bool
 */
function fann_set_rprop_delta_max($ann, $rprop_delta_max) {}

/**
 * Sets the minimum step-size
 *
 *
 * @param resource $ann
 * @param float $rprop_delta_min
 *
 * @return bool
 */
function fann_set_rprop_delta_min($ann, $rprop_delta_min) {}

/**
 * Sets the initial step-size
 *
 *
 * @param resource $ann
 * @param float $rprop_delta_zero
 *
 * @return bool
 */
function fann_set_rprop_delta_zero($ann, $rprop_delta_zero) {}

/**
 * Sets the increase factor used during RPROP training
 *
 *
 * @param resource $ann
 * @param float $rprop_increase_factor
 *
 * @return bool
 */
function fann_set_rprop_increase_factor($ann, $rprop_increase_factor) {}

/**
 * Sets the sarprop step error shift
 *
 *
 * @param resource $ann
 * @param float $sarprop_step_error_shift
 *
 * @return bool
 */
function fann_set_sarprop_step_error_shift($ann, $sarprop_step_error_shift) {}

/**
 * Sets the sarprop step error threshold factor
 *
 *
 * @param resource $ann
 * @param float $sarprop_step_error_threshold_factor
 *
 * @return bool
 */
function fann_set_sarprop_step_error_threshold_factor($ann, $sarprop_step_error_threshold_factor) {}

/**
 * Sets the sarprop temperature
 *
 *
 * @param resource $ann
 * @param float $sarprop_temperature
 *
 * @return bool
 */
function fann_set_sarprop_temperature($ann, $sarprop_temperature) {}

/**
 * Sets the sarprop weight decay shift
 *
 *
 * @param resource $ann
 * @param float $sarprop_weight_decay_shift
 *
 * @return bool
 */
function fann_set_sarprop_weight_decay_shift($ann, $sarprop_weight_decay_shift) {}

/**
 * Calculate input and output scaling parameters for future use based on training data
 *
 *
 * @param resource $ann
 * @param resource $train_data
 * @param float $new_input_min
 * @param float $new_input_max
 * @param float $new_output_min
 * @param float $new_output_max
 *
 * @return bool
 */
function fann_set_scaling_params($ann, $train_data, $new_input_min, $new_input_max, $new_output_min, $new_output_max) {}

/**
 * Sets the error function used during training
 *
 *
 * @param resource $ann
 * @param int $error_function
 *
 * @return bool
 */
function fann_set_train_error_function($ann, $error_function) {}

/**
 * Sets the training algorithm
 *
 *
 * @param resource $ann
 * @param int $training_algorithm
 *
 * @return bool
 */
function fann_set_training_algorithm($ann, $training_algorithm) {}

/**
 * Sets the stop function used during training
 *
 *
 * @param resource $ann
 * @param int $stop_function
 *
 * @return bool
 */
function fann_set_train_stop_function($ann, $stop_function) {}

/**
 * Set connections in the network
 *
 *
 * @param resource $ann
 * @param array $connections
 *
 * @return bool
 */
function fann_set_weight_array($ann, $connections) {}

/**
 * Set a connection in the network
 *
 *
 * @param resource $ann
 * @param int $from_neuron
 * @param int $to_neuron
 * @param float $weight
 *
 * @return bool
 */
function fann_set_weight($ann, $from_neuron, $to_neuron, $weight) {}

/**
 * Shuffles training data, randomizing the order
 *
 *
 * @param resource $train_data
 *
 * @return bool
 */
function fann_shuffle_train_data($train_data) {}

/**
 * Returns an copy of a subset of the train data
 *
 *
 * @param resource $data
 * @param int $pos
 * @param int $length
 *
 * @return resource|false
 */
function fann_subset_train_data($data, $pos, $length) {}

/**
 * Test a set of training data and calculates the MSE for the training data
 *
 *
 * @param resource $ann
 * @param resource $data
 *
 * @return float|false The updated MSE, or false on error.
 */
function fann_test_data($ann, $data) {}

/**
 * Test with a set of inputs, and a set of desired outputs
 *
 *
 * @param resource $ann
 * @param array $input
 * @param array $desired_output
 *
 * @return bool
 */
function fann_test($ann, $input, $desired_output) {}

/**
 * Train one epoch with a set of training data
 *
 *
 * @param resource $ann
 * @param resource $data
 *
 * @return float|false The MSE, or false on error.
 */
function fann_train_epoch($ann, $data) {}

/**
 * Trains on an entire dataset for a period of time
 *
 *
 * @param resource $ann
 * @param resource $data
 * @param int $max_epochs
 * @param int $epochs_between_reports
 * @param float $desired_error
 *
 * @return bool
 */
function fann_train_on_data($ann, $data, $max_epochs, $epochs_between_reports, $desired_error) {}

/**
 * Trains on an entire dataset, which is read from file, for a period of time
 *
 *
 * @param resource $ann
 * @param string $filename
 * @param int $max_epochs
 * @param int $epochs_between_reports
 * @param float $desired_error
 *
 * @return bool
 */
function fann_train_on_file($ann, $filename, $max_epochs, $epochs_between_reports, $desired_error) {}

/**
 * Train one iteration with a set of inputs, and a set of desired outputs
 *
 *
 * @param resource $ann
 * @param array $input
 * @param array $desired_output
 *
 * @return bool
 */
function fann_train($ann, $input, $desired_output) {}

define('FANN_TRAIN_INCREMENTAL', 0);
define('FANN_TRAIN_BATCH', 1);
define('FANN_TRAIN_RPROP', 2);
define('FANN_TRAIN_QUICKPROP', 3);
define('FANN_TRAIN_SARPROP', 4);
define('FANN_LINEAR', 0);
define('FANN_THRESHOLD', 1);
define('FANN_THRESHOLD_SYMMETRIC', 2);
define('FANN_SIGMOID', 3);
define('FANN_SIGMOID_STEPWISE', 4);
define('FANN_SIGMOID_SYMMETRIC', 5);
define('FANN_SIGMOID_SYMMETRIC_STEPWISE', 6);
define('FANN_GAUSSIAN', 7);
define('FANN_GAUSSIAN_SYMMETRIC', 8);
define('FANN_GAUSSIAN_STEPWISE', 9);
define('FANN_ELLIOT', 10);
define('FANN_ELLIOT_SYMMETRIC', 11);
define('FANN_LINEAR_PIECE', 12);
define('FANN_LINEAR_PIECE_SYMMETRIC', 13);
define('FANN_SIN_SYMMETRIC', 14);
define('FANN_COS_SYMMETRIC', 15);
define('FANN_SIN', 16);
define('FANN_COS', 17);
define('FANN_ERRORFUNC_LINEAR', 0);
define('FANN_ERRORFUNC_TANH', 1);
define('FANN_STOPFUNC_MSE', 0);
define('FANN_STOPFUNC_BIT', 1);
define('FANN_NETTYPE_LAYER', 0);
define('FANN_NETTYPE_SHORTCUT', 1);
define('FANN_E_NO_ERROR', 0);
define('FANN_E_CANT_OPEN_CONFIG_R', 1);
define('FANN_E_CANT_OPEN_CONFIG_W', 2);
define('FANN_E_WRONG_CONFIG_VERSION', 3);
define('FANN_E_CANT_READ_CONFIG', 4);
define('FANN_E_CANT_READ_NEURON', 5);
define('FANN_E_CANT_READ_CONNECTIONS', 6);
define('FANN_E_WRONG_NUM_CONNECTIONS', 7);
define('FANN_E_CANT_OPEN_TD_W', 8);
define('FANN_E_CANT_OPEN_TD_R', 9);
define('FANN_E_CANT_READ_TD', 10);
define('FANN_E_CANT_ALLOCATE_MEM', 11);
define('FANN_E_CANT_TRAIN_ACTIVATION', 12);
define('FANN_E_CANT_USE_ACTIVATION', 13);
define('FANN_E_TRAIN_DATA_MISMATCH', 14);
define('FANN_E_CANT_USE_TRAIN_ALG', 15);
define('FANN_E_TRAIN_DATA_SUBSET', 16);
define('FANN_E_INDEX_OUT_OF_BOUND', 17);
define('FANN_E_SCALE_NOT_PRESENT', 18);
define('FANN_E_INPUT_NO_MATCH', 19);
define('FANN_E_OUTPUT_NO_MATCH', 20);

define('FANN_VERSION', '2.2');
// End of Fann v.1.0
