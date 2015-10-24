<?php
/**
 * nelisys/ping
 *
 * @author    nelisys <nelisys@users.noreply.github.com>
 * @copyright 2015 nelisys
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/nelisys/ping
 */

namespace Nelisys;

/**
 * PHP Class for fping command
 */
class Ping {

    /**
     * Path to fping command.
     */
    protected $fping = '/usr/sbin/fping';

    /**
     * Array of hosts.
     */
    protected $hosts = array();

    /**
     * Initial Variables.
     *
     * @param string or array of $hosts to ping
     */
    public function __construct($hosts) {
        if (! isset($hosts)) {
            throw new \Exception('no host to ping');
        }

        $this->hosts = array_unique((array) $hosts);
    }

    /**
     * Execute fping command.
     *
     * @return associative array of hosts to ping response time, false = cannot ping
     */
    public function ping() {
        $hosts_to_ping = '';

        foreach ($this->hosts as $host) {
            $hosts_to_ping .= ' ' . escapeshellarg($host);
        }

        exec("$this->fping -e $hosts_to_ping 2>&1", $exec_output, $exec_return);

        return $this->output($exec_output, $exec_return);
    }

    /**
     * Error handling and format the return
     */
    protected function output($exec_output, $exec_return) {

        if ($exec_return == 127) {
            // 127 = command not found
            throw new \Exception($exec_output[0]);
        }

        $_ret = array();

        foreach ($this->hosts as $host) {
            $_ret[$host] = false;
        }

        for ($i=0; $i<count($exec_output); $i++) {
            if (strpos($exec_output[$i], ' is alive')) {
                $host = strtok($exec_output[$i], ' ');

                // 127.0.0.1 is alive (0.12 ms)
                $elapsed_time = trim(str_replace("$host is alive (", '', $exec_output[$i]), ' ms)');
                $_ret[$host]  = $elapsed_time;
            }
        }

        return $_ret;
    }
}
