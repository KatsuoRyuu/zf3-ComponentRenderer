<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace KryuuCommon\ComponentRendererTest;

use KryuuCommon\ComponentRenderer\VariableProcessor;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Description of VariableProcessorTest
 *
 * @author spawn
 */
class VariableProcessorTest extends AbstractHttpControllerTestCase
{
    //put your code here

    public function testInvoke()
    {

        $pipeContent = "variable | number: '0.00', somevar | currency: 'dk'";
        $variableProcessor = new VariableProcessor();

        $output = $variableProcessor($pipeContent);

        $this->assertEquals("\$this->currency(\$this->number(\$variable, '0.00', \$somevar), 'dk')", $output);
    }
}
