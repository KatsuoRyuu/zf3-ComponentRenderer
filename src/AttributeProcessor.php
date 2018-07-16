<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace KryuuCommon\ComponentRenderer;

/**
 * Description of AttributeProcessor
 *
 * @author spawn
 */
class AttributeProcessor
{

    const PARAMETERS = '#(?P<param>[\w-_]*)="(?P<value>(\\"|[^"])*?)"#s';

    private $standard = [
        'accesskey',
        'class',
        'contenteditable',
        'contextmenu',
        'dir',
        'draggable',
        'dropzone',
        'hidden',
        'id',
        'lang',
        'spellcheck',
        'style',
        'tabindex',
        'title',
        'translate',
        'onafterprint',
        'onbeforeprint',
        'onbeforeunload',
        'onerror',
        'onhashchange',
        'onload',
        'onmessage',
        'onoffline',
        'ononline',
        'onpagehide',
        'onpageshow',
        'onpopstate',
        'onresize',
        'onstorage',
        'onunload',
        'align',
    ];

    private $parameterLine = null;
    private $parameters = [
        'ngX'   => [],
        'ngStd' => [],
        'param' => [],
    ];

    private $variableProcessor;

    public function setParamLine($line)
    {
        $this->parameterLine = $line;
        $this->parameters = [
            'ngX'   => [],
            'ngStd' => [],
            'param' => [],
        ];

        return $this;
    }


    public function getNgX()
    {
        $this->processParamLine();
        return $this->parameters['ngX'];
    }

    public function getNgStd()
    {
        $this->processParamLine();
        return $this->parameters['ngStd'];
    }

    public function getParams()
    {
        $this->processParamLine();
        return $this->parameters['param'];
    }

    public function processParamLine()
    {
        if (count($this->parameters['ngX']) != 0
          && count($this->parameters['ngStd']) != 0
          && count($this->parameters['param']) != 0
        ) {
            return;
        }

        $mergedAttr = [];

        foreach ($this->parameterLine as $key => $attr) {
            $matches = [];
            preg_match_all(self::PARAMETERS, $attr, $matches);
            $methodParams = '';

            if (! array_key_exists('param', $matches)
              || ! array_key_exists('value', $matches)
            ) {
                continue;
            }
            $this->processParameterMatches($matches);

            $mergedAttr[$key] = $methodParams;
        }

        return $mergedAttr;
    }

    public function processParameterMatches($matches)
    {
        $variableProcessor = $this->getVariableProcessor();
        foreach ($matches['param'] as $key => $parameter) {
            $paramKey = strtolower(substr($parameter, 2, strlen($parameter)));

            if (substr($parameter, 0, 2) == "ng"
              && in_array($paramKey, $this->standard)
            ) {
                $this->parameters['ngStd'][$paramKey]
                        = trim($matches['value'][$key]);
            } elseif (substr($parameter, 0, 2) == "ng") {
                $this->parameters['ngX'][$paramKey]
                        = trim($matches['value'][$key]);
            } else {
                $this->parameters['param'][$parameter]
                        = $variableProcessor($matches['value'][$key]);
            }
        }
    }

    private function getVariableProcessor()
    {
        if (! $this->variableProcessor) {
            $this->variableProcessor = new VariableProcessor();
        }
        return $this->variableProcessor;
    }
}
