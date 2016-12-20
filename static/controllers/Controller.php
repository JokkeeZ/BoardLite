<?php

/**
 * Simple abstract class for easy database access for controllers and other often used functionality.
 *
 * @author JokkeeZ
 * @version 1.0
 * @copyright Copyright © 2016 JokkeeZ
 */
abstract class Controller {

    /**
     * Get's $_CONFIG array without defining global every single time.
     * @var array
     */
    protected $config = [];

    /**
     * Initializes an new instance of Controller class with default values.
     */
    public function __construct() {
        global $_CONFIG;
        $this->config = $_CONFIG;
    }

    /**
     * Get's an new instance of database class.
     *
     * @return Database
     */
    protected function getDatabase():Database {
        return new Database();
    }
}