<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use DB;
use Session;

class TaxonomyController extends Controller
{
    public function __construct(){

    }

    function index($taxonomy){
    	if(trans('taxonomy.' . $taxonomy)){
            $title = trans('taxonomy.' . $taxonomy);
        }else{
            abort('404');
        }
    	$data = $this->get_terms($taxonomy);
    	if(count($data) > 0){
    		$parents = $this->taxonomy_recursive($data, 1, 'option');
    	}else{
    		$parents = '';
    	}
    	if(count($data) > 0){
    		$get_data = $this->taxonomy_recursive($data, 1, 'tr', '— ');
    	}else{
    		$get_data = '';
    	}
    	return view('admin.taxonomy.index', ['title' => $title, 'parents' => $parents, 'data' => $data, 'get_data' => $get_data, 'taxonomy' => $taxonomy]);
    }

    function postIndex(Request $request, $taxonomy){
        $rules = [
            'term_name' => 'required|max:180',
            'term_slug' => 'max:180',
            'term_description' => 'max:5000'
        ];
        $messages = [
            'term_name.required' => 'Please do not leave the blank information.',
            'term_name.max' => 'Maximum 180 characters.',
            'term_slug.max' => 'Maximum 180 characters.',
            'term_description.max' => 'Maximum 180 characters'
        ];
        $this->validate($request, $rules, $messages);
        $term_taxonomy = [
            'term_name' => trim($request->term_name),
            'term_slug' => str_slug(trim($request->term_slug)),
            'taxonomy' => $taxonomy,
            'term_description' => trim($request->term_description),
            'term_parent' => $request->term_parent,
            'term_order' => $request->term_order,
            'count' => 0
        ];
        $check = DB::table('term_taxonomy')->where('term_slug', str_slug(trim($request->term_slug)))->where('taxonomy', $taxonomy)->where('term_parent', $request->term_parent)->count();
        if($check > 0){
            Session::flash('notify_type', 'error');
            Session::flash('notify_content', 'A term with the name provided already exists with this parent.');
        }else{
            Session::flash('notify_type', 'success');
            Session::flash('notify_content', 'Successfully.');
            $this->add_term_taxonomy($term_taxonomy);
        }
        return redirect()->back();
    }

    function getEdit($taxonomy, $term_taxonomy_id){
        $active_url = url('/admin/taxonomy/' . $taxonomy);
        $data = $this->get_taxonomy_by_id($term_taxonomy_id);
        // if(count($data) > 0){
        if (is_object($data)) {
            if(trans('taxonomy.' . $taxonomy)){
                $title = trans('taxonomy.' . $taxonomy);
            }else{
                abort('404');
            }
            $term_taxonomy = $this->get_terms($taxonomy);
            if(count($term_taxonomy) > 0){
                $parents = $this->taxonomy_recursive($term_taxonomy, 1, 'option', '&nbsp;', 0, $data->term_parent, $data->term_taxonomy_id);
            }else{
                $parents = '';
            }
            return view('admin.taxonomy.edit', ['active_url' => $active_url, 'title' => $title, 'parents' => $parents, 'data' => $data]);
        }else{
            abort('404');
        }
    }

    function postEdit(Request $request, $taxonomy, $term_taxonomy_id){
        $rules = [
            'term_name' => 'required|max:180',
            'term_slug' => 'required|max:180',
            'term_parent' => 'required|integer',
            'term_description' => 'max:5000'
        ];
        $messages = [
            'term_name.required' => 'Please do not leave the blank information.',
            'term_name.max' => 'Maximum 180 characters.',
            'term_slug.required' => 'Please do not leave the blank information.',
            'term_slug.max' => 'Maximum 180 characters.',
            'term_parent.required' => 'Please select one.',
            'term_parent.integer' => 'Please enter a number.',
            'term_description.max' => 'Maximum 180 characters'
        ];
        $this->validate($request, $rules, $messages);
        $term_name = trim($request->term_name);
        $term_slug = str_slug(trim($request->term_slug));
        if(empty($term_slug)){
            $term_slug = str_slug($term_name);
        }
        $get_term_taxonomy = DB::table('term_taxonomy')->where('term_taxonomy_id', $term_taxonomy_id)->first();
        if(count($get_term_taxonomy) > 0){
            $term_taxonomy = [
                'term_name' => $term_name,
                'term_slug' => $term_slug,
                'taxonomy' => $taxonomy,
                'term_description' => $request->term_description,
                'term_parent' => $request->term_parent,
                'term_order' => $request->term_order
            ];
            $this->update_term_taxonomy($term_taxonomy_id, $term_taxonomy);
            Session::flash('notify_type', 'success');
            Session::flash('notify_content', 'Successfully.');
            return redirect()->back();
        }else{
            return redirect()->back();
        }
    }

    function postTaxonomyAjax(Request $request){
        $term_taxonomy = [
            'term_name' => trim($request->term_name),
            'term_slug' => str_slug(trim($request->term_name)),
            'taxonomy' => $request->taxonomy,
            'term_description' => '',
            'term_parent' => $request->term_parent,
            'term_order' => $request->term_order,
            'count' => 0
        ];
        $check = DB::table('term_taxonomy')->where('term_slug', str_slug(trim($request->term_name)))->where('taxonomy', $request->taxonomy)->where('term_parent', $request->term_parent)->count();
        if($check > 0){
            return 'existed';
        }else{
            $term_taxonomy_id = $this->add_term_taxonomy($term_taxonomy);
            $term_taxonomy = $this->get_taxonomy_by_id($term_taxonomy_id);
            return Response::json($term_taxonomy);
        }
    }

    function getDelete($taxonomy, $term_taxonomy_id){
        $this->delete_term_taxonomy($term_taxonomy_id);
        Session::flash('notify_type', 'success');
        Session::flash('notify_content', 'Successfully.');
        return redirect()->back();
    }

    function postSearch(Request $request, $taxonomy){
        $search_text = $request->search_text;
        $data = DB::table('term_taxonomy')->where('term_name', 'LIKE', '%' . $search_text . '%')->orWhere('term_slug', 'LIKE', '%' . $search_text . '%')->orderBy('term_name')->limit(20)->get();
        if(count($data) == 0){
            return '<tr><td colspan="5">No items found.</td></tr>';
        }else{
            $view_base = $this->get_option('category_base');
            if($taxonomy == 'post-category'){
                $view_base = $this->get_option('category_base');
            }else if($taxonomy == 'post-tag'){
                $view_base = $this->get_option('tag_base');
            }
            $display_data = '';
            foreach($data as $value){
                $display_data .= '<tr><td><input type="checkbox" value="'.$value->term_taxonomy_id.'" class="flat check_item" name="table_records"></td><td><div class="table_title"><a href="'.url('/admin/taxonomy/'.$taxonomy.'/edit/'.$value->term_taxonomy_id).'">'.$value->term_name.'</a></div><ul class="table_title_actions"><li><a href="'.url('/admin/taxonomy/'.$taxonomy.'/edit/'.$value->term_taxonomy_id).'">Edit</a></li><li><a href="'.url('/'.$view_base.'/'.$value->term_slug).'" target="_blank">View</a></li><li><a href="'.url('/admin/taxonomy/'.$taxonomy.'/delete/' . $value->term_taxonomy_id).'" class="action_delete">Delete</a></li></ul></td><td>'.$value->term_slug.'</td><td>'.$value->term_description.'</td><td>'.$value->count.'</td></tr>';
            }
            return $display_data;
        }
    }
}
