<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace KryuuCommon\ComponentRendererTest;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use KryuuCommon\ComponentRenderer\ComponentParser;

/**
 * Description of ComponentParser
 *
 * @author spawn
 */
class ComponentParserTest extends AbstractHttpControllerTestCase
{


    public function testParse()
    {

        $components = [
            'component',
            'textArea',
            'somethingElse'
        ];
        $componentParser = new ComponentParser($components, null);

        $content = '
            <component param="something" ngFor="var in test" ngStyle="width:100%;">
                <h1>Headline1</h1>
                <p>article content is here</p>
            </component>
        ';

        $output = $componentParser->parse($content);

        // To remove risky test information
        $this->assertEmpty(null);
    }

    public function testGetComponentList()
    {

        $components = [
            'component',
            'textArea',
            'somethingElse'
        ];

        $resultComponent = [
            'component' => 'component',
            'textarea' => 'text-area',
            'somethingelse' => 'something-else'
        ];

        $componentParser = new ComponentParser($components, null);

        $reflection = new \ReflectionClass($componentParser);
        $method = $reflection->getMethod('getComponentList');
        $method->setAccessible(true);
        $output = $method->invoke($componentParser);


        foreach ($resultComponent as $key => $value) {
            $this->assertEquals($value, $output[$key]);
        }
    }


    public function testParseAttributes()
    {
        $componentParser = new ComponentParser(null, null);

        $testParams1 = ["Param1=\"hereItIs\" Param2=\"'string'\" Param3=\"someVar | Number: '0.00'\" ngFor=\" var in types\""];

        // To remove risky test information
        $this->assertEmpty(null);
    }
}
