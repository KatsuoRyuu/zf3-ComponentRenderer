<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace KryuuCommon\ComponentRenderer;

class ComponentParser
{

    const COMPONENT = '#(?P<tag>(<\s*?(?P<component>%s)(?P<attr>\b(\\\\>|[^>])*)>(?P<text>.*?)</%s\b[^>]*>))#s';

    private $illegalTags = [
        'a',
        'abbr',
        'acronym',
        'address',
        'applet',
        'area',
        'article',
        'aside',
        'audio',
        'b',
        'base',
        'basefont',
        'bdi',
        'bdo',
        'big',
        'blockquote',
        'body',
        'br',
        'button',
        'canvas',
        'caption',
        'center',
        'cite',
        'code',
        'col',
        'colgroup',
        'datalist',
        'dd',
        'del',
        'details',
        'dfn',
        'dir',
        'div',
        'dl',
        'dt',
        'em',
        'embed',
        'fieldset',
        'figcaption',
        'figure',
        'font',
        'footer',
        'form',
        'frame',
        'frameset',
        'h1',
        'h2',
        'h3',
        'h4',
        'h5',
        'h6',
        'head',
        'header',
        'hgroup',
        'hr',
        'html',
        'i',
        'iframe',
        'img',
        'input',
        'ins',
        'kbd',
        'keygen',
        'label',
        'legend',
        'li',
        'link',
        'map',
        'mark',
        'menu',
        'meta',
        'meter',
        'nav',
        'noframes',
        'noscript',
        'object',
        'ol',
        'optgroup',
        'option',
        'output',
        'p',
        'param',
        'pre',
        'progress',
        'q',
        'rp',
        'rt',
        'ruby',
        's',
        'samp',
        'script',
        'section',
        'select',
        'small',
        'source',
        'span',
        'strike',
        'strong',
        'style',
        'sub',
        'summary',
        'sup',
        'table',
        'tbody',
        'td',
        'textarea',
        'tfoot',
        'th',
        'thead',
        'time',
        'title',
        'tr',
        'track',
        'tt',
        'u',
        'ul',
        'var',
        'video',
        'wbr'
    ];


    private $components = [];
    private $componentsRaw = [];
    private $renderer = null;
    private $serviceLocator = null;

    public function __construct($components, $serviceLocator)
    {
        $this->componentsRaw = $components;
        $this->serviceLocator = $serviceLocator;
    }

    public function parse($content)
    {

        $attributeProcessor = new AttributeProcessor();

        foreach ($this->getComponentList() as $key => $comp) {
            if (! strpos($content, $comp)) {
                continue;
            }
            $reg = sprintf(self::COMPONENT, $comp, $comp);

            $matches = [];
            preg_match_all($reg, $content, $matches);
//            print_r($matches['attr']);
            $attributeProcessor->setParamLine($matches['attr']);

            //$this->processNgX($attr);

//            print_r($attributeProcessor->getNgStd());
//            print_r($attributeProcessor->getNgX());
//            print_r($attributeProcessor->getParams());

            $content = $this->parseComponentMatches($matches, $key, $content);
        }

        return $content;
    }

    public function parseComponentMatches($matches, $key, $content)
    {
        if (! array_key_exists('tag', $matches)) {
            return $content;
        }
        foreach ($matches['tag'] as $i => $match) {
            $tag = trim($matches['tag'][$i]);
            $component = trim($matches['component'][$i]);
            $text = trim($matches['text'][$i]);

            $content = str_ireplace(
                $tag,
                $this->genTag($component, $key, $text),
                $content
            );
        }

        return $content;
    }

    private function genTag($component, $method, $text)
    {

        if (preg_match('#^({)#', $text)) {
            $text = '$' . trim(preg_replace('#(^({)|(})$)#m', '', $text));
        } else {
            $text = '\'' . addslashes(
                preg_replace('#(^(\'|"|`)|(\'|"|`)$)#m', '', $text)
            ) . '\'';
        }

        return '<' . $component . '>'
            . '<?= $this->' . $method . '(' . $text . '); ?>'
            . '</' . $component . '>';
    }


    private function getRenderer()
    {
        if (! $this->renderer) {
            $componentStrategy = $this->serviceLocator->get('ComponentStrategy');
            $this->renderer = $componentStrategy->getRenderer();
        }

        return $this->renderer;
    }


    private function setComponentList($list)
    {
        $this->componentsRaw = $list;
        $this->components = [];
        return $this;
    }

    private function getComponentList()
    {
        if (count($this->components)) {
            return $this->components;
        }

        $filter = new \Zend\Filter\Word\CamelCaseToDash();

        foreach ($this->componentsRaw as $helper) {
            $identifier = strtolower(str_replace(["/","_"], "", $helper));
            $filteredHelper = strtolower($filter->filter($helper));
            if (in_array($filteredHelper, $this->illegalTags)) {
                continue;
            }
            if (! array_key_exists($identifier, $this->components)
              || preg_match("/^[A-Z]/", $helper)
            ) {
                $this->components[$identifier] = $filteredHelper;
            }
        }

        return $this->components;
    }
}
