<?php
class Waterless_Walker_Nav_Menu extends Walker_Nav_Menu
{
    // Start sub-menu (dropdown container)
    function start_lvl(&$output, $depth = 0, $args = null)
    {
        if ($depth === 0) {
            $output .= '<section class="dropdown-menu"><article class="dropdown-content grid cols-auto">';
        }
    }

    function end_lvl(&$output, $depth = 0, $args = null)
    {
        if ($depth === 0) {
            $output .= '</article></section>';
        }
    }

    // Start element
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        $title = apply_filters('the_title', $item->title, $item->ID);
        $url   = !empty($item->url) ? esc_url($item->url) : '#';
        $has_children = in_array('menu-item-has-children', (array) $item->classes);

        $icon_id = get_post_meta($item->ID, '_menu_item_icon_id', true);

        if ($depth === 0 && $has_children) {
            // Parent with dropdown
            $output .= '<section class="dropdown">';
            $output .= '<button class="dropdown-toggle nav-elements">' . esc_html($title) . '</button>';
        } elseif ($depth === 0) {
            // Normal top-level item
            $output .= '<a href="' . $url . '" class="nav-elements">' . esc_html($title) . '</a>';
        } else {
            // Child item inside dropdown
            $output .= '<section class="dropdown-content-item">';
            // Check if menu point has icon
            $icon_url = wp_get_attachment_url($icon_id);
            if($icon_url){
                $output .= '
                <a href="' . $url . '" class="dropdown-link">
                <img src="' . esc_url($icon_url) . '" alt="" class="menu-icon" />'
                . esc_html($title) . '</a>';
            }else {
                $output .= '<a href="' . $url . '" class="dropdown-link">' . esc_html($title) . '</a>';
            }
        }
    }

    function end_el(&$output, $item, $depth = 0, $args = null)
    {
        $has_children = in_array('menu-item-has-children', (array) $item->classes);

        if ($depth === 0 && $has_children) {
            $output .= '</section>'; // close .dropdown
        } elseif ($depth > 0) {
            $output .= '</section>'; // close .dropdown-content-item
        }
    }
}
