<?php

namespace SocialbugCRM\Classes;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Data {
    public function toArray() {
        $return_data = $this->_data;

        foreach($return_data as $k => $v) {
            if(is_object($v) && method_exists($v, 'toArray')) {
                $return_data[$k] = $v->toArray();
            }

            if(is_array($v)) {
                foreach($v as $kk => $vv) {
                    if(is_object($vv) && method_exists($vv, 'toArray')) {
                        $v[$kk] = $vv->toArray();
                    }
                }
                $return_data[$k] = $v;
            }
        }

        return $return_data;
    }

    public function camelize($s) {
        $parts = explode('_', $s);
        $r = '';

        foreach($parts as $p) {
            $r .= ucfirst($p);
        }

        return $r;
    }

    public function setData($k, $v = null) {
        if(is_array($k)) {
            foreach($k as $kk => $v) {
                $method = 'set'.$this->camelize($kk);

                if(method_exists($this, $method)) {
                    $this->$method($v);
                }
            }
        } else {
            $method = 'set'.$this->camelize($k);

            if(method_exists($this, $method)) {
                $this->$method($v);
            }
        }

        return $this;
    }

    public function getData($k = null) {
        if($k === null) {
            return $this->_data;
        }

        if (array_key_exists($k, $this->_data)) {
            return $this->_data[$k];
        }

        return null;
    }
}