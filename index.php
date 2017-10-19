<?php
/*
Plugin Name: ADVANCED Pagination BY SHIRSHAK.
Description: A GOOD WAY TO MAKE FACEBOOK LIKE BOX.
Author: Shirshak Bajgain
Version: 1.0
Text Domain: shirshak
License: 
All rights reserved.



Redistribution and use in source and binary forms, with or without

modification, are permitted provided that the following conditions are met:

    * Redistributions of source code must retain the above copyright

      notice, this list of conditions and the following disclaimer.

    * Redistributions in binary form must reproduce the above copyright

      notice, this list of conditions and the following disclaimer in the

      documentation and/or other materials provided with the distribution.

    * Neither the name of the Studio 42 Ltd. nor the

      names of its contributors may be used to endorse or promote products

      derived from this software without specific prior written permission.



THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND

ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED

WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE

DISCLAIMED. IN NO EVENT SHALL "STUDIO 42" BE LIABLE FOR ANY DIRECT, INDIRECT,

INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT

LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR

PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF

LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE

OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF

ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

function pbt_paginate($args = null) {
$defaults = array(
'page' => null, 'pages' => null, 
'range' => 3, 'gap' => 3, 'anchor' => 1,
'before' => '<div class="pbt-paginate">', 'after' => '<div style="clear:both"></div></div><div style="clear:both"></div>',
'title' => __(''),
'nextpage' => __('&#10140;'), 'previouspage' => __('&#8592;'),
'echo' => 1
);

$r = wp_parse_args($args, $defaults);
extract($r, EXTR_SKIP);

if (!$page && !$pages) {
global $wp_query;

$page = get_query_var('paged');
$page = !empty($page) ? intval($page) : 1;

$posts_per_page = intval(get_query_var('posts_per_page'));
$pages = intval(ceil($wp_query->found_posts / $posts_per_page));
}
	
$output = "";
if ($pages > 1) {	
$output .= "$before";
$ellipsis = "<span class='pbt-gap'>...</span>";

if ($page > 1 && !empty($previouspage)) {
$output .= "<a href='" . get_pagenum_link($page - 1) . "' class='pbt-prev pbt-page'>$previouspage</a>";
}
		
$min_links = $range * 2 + 1;
$block_min = min($page - $range, $pages - $min_links);
$block_high = max($page + $range, $min_links);
$left_gap = (($block_min - $anchor - $gap) > 0) ? true : false;
$right_gap = (($block_high + $anchor + $gap) < $pages) ? true : false;

if ($left_gap && !$right_gap) {
$output .= sprintf('%s%s%s', 
pbt_paginate_loop(1, $anchor), 
$ellipsis, 
pbt_paginate_loop($block_min, $pages, $page)
);
}
		
else if ($left_gap && $right_gap) {
$output .= sprintf('%s%s%s%s%s', 
pbt_paginate_loop(1, $anchor), 
$ellipsis, 
pbt_paginate_loop($block_min, $block_high, $page), 
$ellipsis, 
pbt_paginate_loop(($pages - $anchor + 1), $pages)
);
}
		
else if ($right_gap && !$left_gap) {
$output .= sprintf('%s%s%s', 
pbt_paginate_loop(1, $block_high, $page),
$ellipsis,
pbt_paginate_loop(($pages - $anchor + 1), $pages)
);
}
	
else {
$output .= pbt_paginate_loop(1, $pages, $page);
}

if ($page < $pages && !empty($nextpage)) {
$output .= "<a href='" . get_pagenum_link($page + 1) . "' class='pbt-next pbt-page'>$nextpage</a>";
}

$output .= $after;
}

if ($echo) {
echo $output;
}

return $output;
}

function pbt_paginate_loop($start, $max, $page = 0) {
$output = "";
for ($i = $start; $i <= $max; $i++) {
$output .= ($page === intval($i)) 
? "<span class='pbt-page pbt-current'>$i</span>" 
: "<a href='" . get_pagenum_link($i) . "' class='pbt-page'>$i</a>";
}
return $output;
}
 ! defined( 'ABSPATH' ) and exit;

add_action( 'numbered_in_page_links', 'numbered_in_page_links', 10, 1 );


 function numbered_in_page_links( $args = array () )
{
    $defaults = array(
        'before'      => '<hr/><p>Click on following buttons to continue reading</p><div class="pbt-paginate">' . __('')
    ,   'after'       => '</div><div style="clear:both"/>'
    ,   'link_before' => ''
    ,   'link_after'  => ''
    ,   'pagelink'    => '%'
    ,   'echo'        => 1
        // element for the current page
    ,   'highlight'   => 'span class="pbt-page pbt-current"'
    );

    $r = wp_parse_args( $args, $defaults );
    $r = apply_filters( 'wp_link_pages_args', $r );
    extract( $r, EXTR_SKIP );

    global $page, $numpages, $multipage, $more, $pagenow;

    if ( ! $multipage )
    {
        return;
    }

    $output = $before;

    for ( $i = 1; $i < ( $numpages + 1 ); $i++ )
    {
        $j       = str_replace( '%', $i, $pagelink );
        $output .= ' ';

        if ( $i != $page || ( ! $more && 1 == $page ) )
        {
            $output .= _wp_link_page( $i ) . "{$link_before}{$j}{$link_after}</a>";
        }
        else
        {   // highlight the current page
            // not sure if we need $link_before and $link_after
            $output .= "<$highlight>{$link_before}{$j}{$link_after}</$highlight>";
        }
    }

    print $output . $after;
}
?>