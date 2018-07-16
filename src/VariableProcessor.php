<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace KryuuCommon\ComponentRenderer;

/**
 * Description of VariableProcessor
 *
 * @author spawn
 */
class VariableProcessor
{
    //put your code here


    public function __invoke($variable)
    {

        $parts = $this->split($variable);
        $cmd = $this->determinVariableType(array_shift($parts));


        foreach ($parts as $key => $pipe) {
            list($f, $p) = explode(':', $pipe, 2);
            $parameters = $this->processParameters($p);
            $cmd = $this->processCommand($f, $cmd, $parameters);
        }


        return $cmd;
    }

    private function split($variable)
    {
         return preg_split("#(?<!\\\\)\|#", $variable);
    }

    private function processParameters($parameters)
    {
        $seperatedParams = preg_split("#(?<!\\\\),#", $parameters);

        $params = [];

        foreach ($seperatedParams as $param) {
            $params[] = $this->determinVariableType($param);
        }

        return $params;
    }

    private function determinVariableType($var)
    {
        $tVar = trim($var);
        if (preg_match("#^('|\"|`)(.|\n)*('|\"|`)$#", trim($tVar))) {
             return trim($tVar);
        } else {
            return '$'.trim($tVar);
        }
    }

    private function processCommand($function, $variable, $params)
    {

        array_unshift($params, $variable);

        return '$this->' . trim($function) . '(' . implode(', ', $params) . ')';
    }
}
