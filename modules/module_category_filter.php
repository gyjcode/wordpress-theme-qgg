<?php 
/**
  * 多重分类筛选
  * 根据分类别名(slug)升序排列
  */

// 获取当前分类ID
function module_category_filter($cat_root_id) {
    // 当前分类查询ID族
    $cur_search_ids = isset($_GET['cid']) ? htmlspecialchars(trim($_GET['cid']), ENT_QUOTES) : '';
    $cur_search_ids = explode(',', $cur_search_ids);
    $cur_search_ids = array_filter($cur_search_ids);
    // 非当前分类查询ID族的情空
    foreach ($cur_search_ids as $key => $value) {
        if( !is_numeric($value) || $cat_root_id !== _get_cat_root_id($value) ) unset($cur_search_ids[$key]);
    }

    // 获取根分类下的所有后代分类
    $categories = get_categories( array(
        'hide_empty' => false,
        'child_of'   => $cat_root_id,
        'order'      => 'ASC',
        'orderby'    => 'slug',
    ) );
    
    // 按子分类生成多重筛选列表
    $flags = array();
    foreach( $categories as $key => $category  ) {
        // 分类标志
        $flags_rid = $category->parent;
        if( $category->parent == $cat_root_id ) {
            $flags_rid = $category->term_id;
        }
        // 创建当前查询分类数组
        if( !isset($flags[$flags_rid]) ){
            $flags[$flags_rid] = array();
        }
        // 生成当前查询分类数组
        if( in_array($category->term_id, $cur_search_ids) ){
            $flags[$flags_rid][] = $category->term_id;
        }
    }

    $output = '';
    foreach( $categories as $key => $category ) {
        $on_search = in_array($category->term_id, $cur_search_ids);
        $old_search_ids = $cur_search_ids;

        // 判断生成 a 链接参数
        if( $on_search ){    // 包含当前ID则新链接移除
            $search_key = array_search($category->term_id, $old_search_ids);
            unset( $old_search_ids[$search_key] );
        } else {    // 不包含当前ID则新链接增加
            $old_search_ids[] = $category->term_id;
            
            // 清除同一 $flags 下的其他 ID 避免重复选择
            $flags_rid = $category->parent;
            if( $category->parent == $cat_root_id ) {
                $flags_rid = $category->term_id;
            }
            if( isset($flags[$flags_rid]) && $flags[$flags_rid] ){
                foreach ($flags[$flags_rid] as $flag_key => $flag) {
                    $search_key = array_search($flag, $old_search_ids);
                    unset($old_search_ids[$search_key]);
                }
            }
        }

        // 获取根分类链接
        $rlink = get_category_link($cat_root_id);
        $link = $rlink . ( $old_search_ids ? '?cid=' . implode(',', $old_search_ids) : '' );

        if( $category->parent == $cat_root_id ) {
            if( $key ) $output.= '</li><li>';
            $output.= '<strong>'. $category->name .'</strong>';
            $output.= '<a href="'. $link .'" '. ($on_search?'class="active"':'') .'>全部</a>';
        }else{
            $output.= '<a href="'. $link .'" '. ($on_search?'class="active"':'') .'>'. $category->name .'</a>';
        }

    }
    // 输出多重分类筛选
    echo '
    <section class="container">
        <div class="module category-filter">
            <ul><li>'.$output.'</li></ul>
        </div>
    </section>';

    // 返回用于查询的分类ID
    return $cur_search_ids ? implode(',', $cur_search_ids) : $cat_root_id;
}