<?php
/**
 * 分类页面相关函数
 */

// 根分类目录 ID 获取
function _get_cat_root_id($cat){
    $this_category = get_category($cat); 
    while($this_category->category_parent){
        $this_category = get_category($this_category->category_parent);
    }
    return $this_category->term_id;     // 返回跟分类的 ID
}
// 分类属性获取
function _get_tax_meta($id=0, $field=''){
    $ops = get_option( "_taxonomy_meta_$id" );

    if( empty($ops) ){
        return '';
    }

    if( empty($field) ){
        return $ops;
    }

    return isset($ops[$field]) ? $ops[$field] : '';
}

// 分类添加更多属性
class __Tax_Cat{

    function __construct(){
        // 新建分类页面添加自定义字段输入框 
        add_action( 'category_add_form_fields', array( $this, 'add_tax_field' ) );
        // 编辑分类页面添加自定义字段输入框 
        add_action( 'category_edit_form_fields', array( $this, 'edit_tax_field' ) );
        // 保存自定义字段数据
        add_action( 'edited_category', array( $this, 'save_tax_meta' ), 10, 2 );
        add_action( 'create_category', array( $this, 'save_tax_meta' ), 10, 2 );
    }
    
    //新建分类页面添加自定义字段输入框
    public function add_tax_field(){    
        echo '
        <div class="form-field">
            <label for="term_meta[style]">展示样式</label>
            <select name="term_meta[style]" id="term_meta[style]" class="postform">
                <option value="default">默认样式</option>
                <option value="video">视频展示</option>
                <option value="product">产品展示</option>
            </select>
            <p class="description">选择后前台展示样式将有所不同</p>
        </div>
        
        <div class="form-field">
            <label for="term_meta[title]">SEO 标题</label>
            <input type="text" name="term_meta[title]" id="term_meta[title]" />
        </div>
        
        <div class="form-field">
            <label for="term_meta[keywords]">SEO 关键字（keywords）</label>
            <input type="text" name="term_meta[keywords]" id="term_meta[keywords]" />
        </div>
        
        <div class="form-field">
            <label for="term_meta[keywords]">SEO 描述（description）</label>
            <textarea name="term_meta[description]" id="term_meta[description]" rows="4" cols="40"></textarea>
        </div>';
    }
    
    // 编辑分类页面添加自定义字段输入框
    public function edit_tax_field( $term ){    

        $term_id = $term->term_id;    // 获取当前分类 ID
        $term_meta = get_option( "_taxonomy_meta_$term_id" );    // 获取已保存的 Option
        
        $meta_style = isset($term_meta['style']) ? $term_meta['style'] : '';  // 自定义添加分类样式

        $meta_title = isset($term_meta['title']) ? $term_meta['title'] : '';    // 自定义添加分类标题
        $meta_keywords = isset($term_meta['keywords']) ? $term_meta['keywords'] : '';    // 自定义添加分类关键字
        $meta_description = isset($term_meta['description']) ? $term_meta['description'] : '';    // 自定义添加分类描述
        
        echo '
            <tr class="form-field">
                <th scope="row">
                    <label for="term_meta[style]">展示样式</label>
                    <td>
                        <select name="term_meta[style]" id="term_meta[style]" class="postform">
                            <option value="default" '. ('default'==$meta_style?'selected="selected"':'') .'>默认样式</option>
                            <option value="video" '. ('video'==$meta_style?'selected="selected"':'') .'>视频展示</option>
                            <option value="product" '. ('product'==$meta_style?'selected="selected"':'') .'>产品展示</option>
                        </select>
                        <p class="description">选择后前台展示样式将有所不同</p>
                    </td>
                </th>
            </tr>
            
            <tr class="form-field">
                <th scope="row">
                    <label for="term_meta[title]">SEO 标题</label>
                    <td>
                        <input type="text" name="term_meta[title]" id="term_meta[title]" value="'. $meta_title .'" />
                    </td>
                </th>
            </tr>
            
            <tr class="form-field">
                <th scope="row">
                    <label for="term_meta[keywords]">SEO 关键字（keywords）</label>
                    <td>
                        <input type="text" name="term_meta[keywords]" id="term_meta[keywords]" value="'. $meta_keywords .'" />
                    </td>
                </th>
            </tr>
            
            <tr class="form-field">
                <th scope="row">
                    <label for="term_meta[description]">SEO 描述（description）</label>
                    <td>
                        <textarea name="term_meta[description]" id="term_meta[description]" rows="4">'. $meta_description .'</textarea>
                    </td>
                </th>
            </tr>
            
        ';
    }
    
    public function save_tax_meta( $term_id ){    // 保存自定义字段的数据
 
        if ( isset( $_POST['term_meta'] ) ) {
            
            $term_meta = array();
            
            // 获取表单传过来的POST数据，POST数组一定要做过滤
            $term_meta['style'] = isset ( $_POST['term_meta']['style'] ) ? esc_sql( $_POST['term_meta']['style'] ) : '';
            $term_meta['title'] = isset ( $_POST['term_meta']['title'] ) ? esc_sql( $_POST['term_meta']['title'] ) : '';
            $term_meta['keywords'] = isset ( $_POST['term_meta']['keywords'] ) ? esc_sql( $_POST['term_meta']['keywords'] ) : '';
            $term_meta['description'] = isset ( $_POST['term_meta']['description'] ) ? esc_sql( $_POST['term_meta']['description'] ) : '';
            
            // 保存 Option 数组
            update_option( "_taxonomy_meta_$term_id", $term_meta );
 
        }
    }
 
}
 
$tax_cat = new __Tax_Cat();
