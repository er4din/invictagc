<?php

class profilegrid_profile_visitor_details_allowed_html_wp_kses
{

    public function pm_allowed_html_wp_kses()
    {
        return array(
            'a' => array(
                'href' => array(),
                'title' => array(),
                'target' => array(),
                'class' => array()
            ),
            'b' => array(),
            'i' => array(),
            'strong' => array(),
            'em' => array(),
            'p' => array(),
            'br' => array(),
            'ul' => array(
                'class' => array(),
            ),
            'ol' => array(),
            'li' => array(),
            'span' => array(
                'class' => array(),
                'id' => array(),
                'style' => array(),
            ),
            'div' => array(
                'class' => array(),
                'id' => array(),
                'style' => array(),
            ),
            'img' => array(
                'src' => array(),
                'alt' => array(),
                'class' => array(),
                'style' => array(),
            ),
        );
    }
}
