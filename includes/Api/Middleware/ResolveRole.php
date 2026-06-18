<?php

namespace GesimaticStaticForms\Api;

class ResolveRole {

    public static function get_role($data) {

        $role = false;

        $blocks = parse_blocks(get_post($data['post_id'])->post_content);

        $atts = find_block_attrs($blocks,'gesimatic-static-forms/user-register');

        foreach ($atts as $block_atts) {
            if (isset($block_atts['formId']) && $block_atts['formId'] === $data['form_id']) {
                $role = $block_atts['userRole'];
                break;
            }
        }

        return $role;
    }

    public static function find_blocks_atts($blocks, $block_name) {

        $blocks_atts = [];
        foreach ($blocks as $block) {
            if ($block['blockName'] === $block_name) {
                $blocks_atts[] = $block['attrs'];
            }
            if (!empty($block['innerBlocks'])) {
                $result = self::find_blocks_atts($block['innerBlocks'], $block_name);
                if ($result !== null) {
                    return $result;
                }
            }
        }
        return $blocks_atts;
    }

}