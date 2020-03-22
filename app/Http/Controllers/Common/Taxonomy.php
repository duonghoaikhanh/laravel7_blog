<?php
namespace App\Http\Controllers\Common;
use DB;

trait Taxonomy{
	public static function get_terms($taxonomy, $rows = 0, $limit = 0){
        $query = $terms = DB::table('term_taxonomy')->where('taxonomy', $taxonomy)->orderBy('term_order', 'ASC');
        if(!empty($limit))
            $query->limit($limit);

		if($rows == 0){
			$terms = $query->get();
		}else{
			$terms = $query->paginate($rows);
        }
        
        
		return $terms;
	}

    protected function get_taxonomy_by_slug($taxonomy, $term_slug){
        $data = DB::table('term_taxonomy')->where('taxonomy', $taxonomy)->where('term_slug', $term_slug)->first();
        return $data;
    }

    protected function get_taxonomy_by_id($term_taxonomy_id){
        $data = DB::table('term_taxonomy')->where('term_taxonomy_id', $term_taxonomy_id)->first();
        return $data;
    }

	public static function taxonomy_recursive($term_taxonomy, $level, $element = 'option', $char = '&nbsp;', $term_parent = 0, $select_value = 0, $ignore = false, $checked_data = [], $name = ''){
        $result = '';
        $checked = '';
        foreach($term_taxonomy as $item){
            if($item->term_parent == $term_parent){
            	if($element == 'option'){
                    $is_selected = $select_value == $item->term_taxonomy_id ? ' selected="selected"' : '';
                    if($ignore == false){
                        $tags = '<option value="' . $item->term_taxonomy_id . '"' . $is_selected . '>' . str_repeat($char, ($level-1)*3) . $item->term_name . '</option>';
                    }else{
                        if($ignore != $item->term_taxonomy_id){
                            $tags = '<option value="' . $item->term_taxonomy_id . '"' . $is_selected . '>' . str_repeat($char, ($level-1)*3) . $item->term_name . '</option>';
                        }else{
                            $tags = '';
                        }
                    }
            	}else if($element == 'tr'){
            		$tags = '<tr><td><input type="checkbox" value="' . $item->term_taxonomy_id . '" class="flat check_item" name="table_records"></td><td><div class="table_title"><a href="' . url('/admin/taxonomy/' . $item->taxonomy . '/edit/' . $item->term_taxonomy_id) . '">' . str_repeat($char, $level-1) . $item->term_name . '</a></div><ul class="table_title_actions"><li><a href="' . url('/admin/taxonomy/' . $item->taxonomy . '/edit/' . $item->term_taxonomy_id) . '">Edit</a></li><li><a href="' . self::get_taxonomy_permalink($item->term_slug, $item->taxonomy) . '" target="_blank">View</a></li><li><a href="' . url('/admin/taxonomy/' . $item->taxonomy . '/delete/' . $item->term_taxonomy_id) . '" class="action_delete">Delete</a></li></ul></td><td>' . $item->term_slug . '</td><td>' . $item->term_description . '</td><td>' . $item->count . '</td><tr>';
            	}else if($element == 'checkbox'){
                    $tr_primary = '';
                    if(is_array($checked_data) == true && in_array($item->term_taxonomy_id, $checked_data) == true && count($checked_data) > 0){
                        $checked = ' checked';
                        if($checked_data[0] == $item->term_taxonomy_id){
                            $make_primary = '<label>primary</label>';
                            $tr_primary = ' class="primary"';
                        }else{
                            $make_primary = '<a href="#" data-make="'.$item->term_taxonomy_id.'" class="post_make_primary_category">primary</a>';
                            $tr_primary = '';
                        }
                    }else{
                        $checked = '';
                        $make_primary = '';
                    }
                    $tags = '<tr'.$tr_primary.'><td>'.str_repeat($char, ($level-1)*5).'<input name="'.$name.'" value="'.$item->term_taxonomy_id.'" type="checkbox" id="post_category_item_of_list'.$item->term_taxonomy_id.'"'.$checked.'> <label for="post_category_item_of_list'.$item->term_taxonomy_id.'">'.$item->term_name.'</label></td><td class="action_make_primary">'.$make_primary.'</td></tr>';
                }
                $result .= $tags . self::taxonomy_recursive($term_taxonomy, $level+1, $element, $char, $item->term_taxonomy_id, $select_value, $ignore, $checked_data, $name);
            }
        }
        return $result ? $result : '';
    }

    protected function add_term_taxonomy($term_taxonomy){
        $existed = DB::table('term_taxonomy')->where('term_slug', $term_taxonomy['term_slug'])->where('taxonomy', $term_taxonomy['taxonomy'])->where('term_parent', $term_taxonomy['term_parent'])->count();
        if($existed > 0){
            return false;
        }else{
            $index = 2;
            $new_slug = $term_taxonomy['term_slug'];
            $check = DB::table('term_taxonomy')->where('term_slug', $term_taxonomy['term_slug'])->where('taxonomy', $term_taxonomy['taxonomy'])->count();
            while($check > 0){
                $new_slug = $term_taxonomy['term_slug'] . '-' . $index;
                $check = DB::table('term_taxonomy')->where('term_slug', $new_slug)->where('taxonomy', $term_taxonomy['taxonomy'])->count();
                $index++;
            }
            $term_taxonomy['term_slug'] = $new_slug;
            $term_taxonomy_id = DB::table('term_taxonomy')->insertGetId($term_taxonomy);
            return $term_taxonomy_id;
        }
    }

    protected function update_term_taxonomy($term_taxonomy_id, $term_taxonomy){
        $existed = DB::table('term_taxonomy')->where('term_slug', $term_taxonomy['term_slug'])->where('taxonomy', $term_taxonomy['taxonomy'])->where('term_parent', $term_taxonomy['term_parent'])->where('term_taxonomy_id', '!=', $term_taxonomy_id)->count();
        if($existed > 0){
            return false;
        }else{
            $index = 2;
            $new_slug = $term_taxonomy['term_slug'];
            $check = DB::table('term_taxonomy')->where('term_slug', $term_taxonomy['term_slug'])->where('taxonomy', $term_taxonomy['taxonomy'])->where('term_taxonomy_id', '!=', $term_taxonomy_id)->count();
            while($check > 0){
                $new_slug = $term_taxonomy['term_slug'] . '-' . $index;
                $check = DB::table('term_taxonomy')->where('term_slug', $new_slug)->where('taxonomy', $term_taxonomy['taxonomy'])->where('term_taxonomy_id', '!=', $term_taxonomy_id)->count();
                $index++;
            }
            $term_taxonomy['term_slug'] = $new_slug;
            DB::table('term_taxonomy')->where('term_taxonomy_id', $term_taxonomy_id)->update($term_taxonomy);
            return true;
        }
    }

    protected function delete_term_taxonomy($term_taxonomy_id){
        if($term_taxonomy_id != $this->get_option('post_category_default')){
            DB::table('term_taxonomy')->where('term_taxonomy_id', $term_taxonomy_id)->delete();
        }
    }

    protected function get_posts_from_term_slug($slug){
        $ids = DB::table('term_taxonomy as tt')
                    ->leftjoin('term_relationships as tr', 'tt.term_taxonomy_id', '=', 'tr.term_taxonomy_id')
                    ->where('tt.term_slug', $slug)
                    ->pluck('object_id')
                    ->toArray();
        return $ids;
    }
}