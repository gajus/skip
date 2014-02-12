<?php
namespace Gajus\Skip;

/**
 * @link https://github.com/gajus/skip for the canonical source repository
 * @license https://github.com/gajus/skip/blob/master/LICENSE BSD 3-Clause
 */
class Vessel {
    private
        $map = [];

    /**
     * @param string $url Default route URL.
     */
    public function __construct ($url) {
        $this->setRoute('default', $url);
    }

    /**
     * @param string $name Route name.
     * @param string $url URL.
     */
    public function setRoute ($name, $url) {
        if (isset($this->map[$name])) {
            throw new Exception\InvalidArgumentException('Cannot overwrite existing route.');
        } else if (!filter_var($url, \FILTER_VALIDATE_URL)) {
            throw new Exception\InvalidArgumentException('Invalid URL.');
        } else if (mb_strpos(strrev($url), '/') !== 0) {
            throw new Exception\InvalidArgumentException('URL does not refer to a directory.');
        }

        $this->map[$name] = $url;
    }

    /**
     * @param string $name Route name.
     * @return string Route base URL.
     */
    public function getRoute ($name) {
        if (!isset($this->map[$name])) {
            throw new Exception\InvalidArgumentException('Route does not exist.');
        }

        return $this->map[$name];
    }

    public function url ($path = '', $route = 'default') {
        if (strpos($path, '/') === 0) {
            throw new Exception\InvalidArgumentException('Path is not relative to the route URL.');
        }

        $route = $this->getRoute($route);

        return $route . $path;
    }

    /**
     * Redirect 
     */
    public function go ($path, $route = 'default') {
        header('Location: ' . $this->url($path, $route));

        exit;
    }
}