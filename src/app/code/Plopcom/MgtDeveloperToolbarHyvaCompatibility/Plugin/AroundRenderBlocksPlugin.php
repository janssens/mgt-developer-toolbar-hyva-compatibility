<?php

namespace Plopcom\MgtDeveloperToolbarHyvaCompatibility\Plugin;

use Mgt\DeveloperToolbar\Block\Toolbar\Container\Sidebar\Block\Blocks;

class AroundRenderBlocksPlugin extends Blocks
{
    public function aroundRenderBlocks(Blocks $subject, callable $proceed,array $blocks)
    {
        $out = '';
        if (count($blocks)) {
            $out .= '<ul>';
            foreach ($blocks as $block) {
                $hasChildren = isset($block['children']) && count($block['children']);
                $out .= '<li '.($hasChildren ? 'x-data="{ open: false }" class="mgt-developer-toolbar-sidebar-block-parent"' : '').'>';
                $out .= sprintf('<button @click="open = !open">%s</button>', $block['name']);
                $out .= $this->renderBlockProperties($block);
                if (true === $hasChildren) {
                    $out .= $this->renderBlocks($block['children']);
                }
                $out .= '</li>';
            }
            $out .= '</ul>';
        }
        return $out;

    }

    protected function renderBlockProperties(array $block)
    {
        $properties = '<ul class="mgt-developer-toolbar-sidebar-block-properties" x-show="open">';
        foreach(array('template', 'class', 'fileName') as $key) {
            if (isset($block[$key]) && $block[$key]) {
                $properties .= sprintf('<li><strong>%s:</strong> %s</li>', ucfirst($key), $block[$key]);
            }
        }
        $properties .= '</ul>';
        return $properties;
    }
}
