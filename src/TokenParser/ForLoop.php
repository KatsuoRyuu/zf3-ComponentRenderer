<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace KryuuCommon\ComponentRenderer\TokenParser;

class ForLoop
{

    public function __invoke($content, $text)
    {

        $matches = [];
        if (! preg_match("#(?P<value>.*?)in(?P<array>.*?)#", $text, $matches)) {
            throw \Exception("Parsing for loop not possible");
        } elseif (! array_key_exists('value', $matches)) {
            throw \Exception("malformed for look");
        } elseif (! array_key_exists('array', $matches)) {
            throw \Exception("malformed for look");
        }

        $value = trim($matches['value']);
        $array = trim($matches['array']);

        return '<?php foreach(' . $array . ' as ' . $value . '): ?>'
                . $text
                . '<?php endforeach; ?>';
    }
}
