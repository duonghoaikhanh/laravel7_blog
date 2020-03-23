<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Session;
use Auth;

class PostController extends Controller
{
    public function __construct(){ }

    function index($post_type){
        if(isset($_GET['filter'])){
            $filter = $_GET['filter'];
        }else{
            $filter = '';
        }
        $filter_count = $this->get_post_filter_count($post_type);
        if($filter == ''){
            $data = $this->get_all_posts($post_type, [['post_status', '!=', 'trash']]);
        }else if($filter == 'publish'){
            $data = $this->get_all_posts($post_type, [['post_status', '=', 'publish']]);
        }else if($filter == 'draft'){
            $data = $this->get_all_posts($post_type, [['post_status', '=', 'draft']]);
        }else{
            $data = $this->get_all_posts($post_type, [['post_status', '=', 'trash']]);
        }
        return view('admin.posts.index', ['post_type' => $post_type, 'data' => $data, 'filter' => $filter, 'filter_count' => $filter_count]);
    }

    function getPost($post_type, $action, $post_id = ''){
        if($action == 'delete' && $post_id != ''){
            $this->delete_post($post_id);
            Session::flash('notify_type', 'success');
            Session::flash('notify_content', 'Successfully.');
            return redirect()->back();
        }else if($action == 'trash' && $post_id != ''){
            $this->trash_post($post_id);
            Session::flash('notify_type', 'success');
            Session::flash('notify_content', 'Successfully.');
            return redirect()->back();
        }else if($action == 'restore' && $post_id != ''){
            $this->restore_post($post_id);
            Session::flash('notify_type', 'success');
            Session::flash('notify_content', 'Successfully.');
            return redirect()->back();
        }else{
            $active_url = url('/admin/posts/' . $post_type . '/' . $action);
            $post = $this->get_post_by_id($post_id, $post_type, 5);
            $authors = $this->get_all_users();
            if($action == 'edit'){
                $active_url = url('/admin/posts/' . $post_type);
            }
            $data['post'] = $post;
            $seo['seo_separator'] = $this->get_option('seo_separator');
            $seo['site_name'] = $this->get_option('site_name');
            $seo['seo_use_meta_keyword'] = $this->get_option('seo_use_meta_keyword');
            return view('admin.posts.post', ['active_url' => $active_url, 'data' => $data, 'action' => $action, 'post_type' => $post_type, 'authors' => $authors, 'seo' => $seo]);
        }
    }

    function postPost(Request $request, $post_type, $action, $post_id = ''){
        $rules = [
            'post_title' => 'required'
        ];
        $messages = [
            'post_title.required' => 'The title field is required.'
        ];
        $this->validate($request, $rules, $messages);
        $date = date('Y-m-d H:i:s');
        $post_title = $request->post_title;
        $post_name = !empty($request->post_name) ? $request->post_name : str_slug($post_title);
        $post_excerpt = $request->post_excerpt;
        $post_content = $this->content_replace($request->post_content);
        $post_function = $this->content_replace($request->post_function);
        $post_howtouse = $this->content_replace($request->post_howtouse);
        $service_time_price = $this->content_replace($request->service_time_price);
        $service_info_bottle = $this->content_replace($request->service_info_bottle);
        $service_info_face = $this->content_replace($request->service_info_face);
        $service_info_flower = $this->content_replace($request->service_info_flower);
        $post_img = $request->post_img;
        $post_author = !empty($request->post_author) ? $request->post_author : Auth::user()->id;
        $post_submit = $request->post_submit;
        $post_status = $request->post_status;
        $post_item_focus = $request->post_item_focus;
        $post_image_focus = $request->post_image_focus;
        $post_visibility = $request->post_visibility;
        $post_password = '';
        if($post_submit == 'draft'){
            $post_status = $post_submit;
        }else{
            if($post_visibility == 'private'){
                $post_status = $post_visibility;
            }else if($post_visibility == 'password'){
                $post_password = $request->post_password;
            }
        }
        $comment_status = empty($request->comment_status) ? 0 : 1;
        $get_published_at = $request->datetime;
        $published_at = $date;
        if(count($get_published_at) > 0){
            $published_at = $get_published_at['year'] . '-' . $get_published_at['month'] . '-' . $get_published_at['day'] . ' ' . $get_published_at['hour'] . ':' . $get_published_at['minute'];
        }
        if(strtotime($published_at) > strtotime($date)){
            $post_status = 'pending';
        }
        if($action == 'add'){
            $post_name = $this->post_name_replace($post_name);
            $post = [
                'post_title' => $post_title,
                'post_name' => $post_name,
                'post_excerpt' => $post_excerpt,
                'post_content' => $post_content,
                'post_function' => $post_function,
                'post_howtouse' => $post_howtouse,
                'service_time_price' => $service_time_price,
                'service_info_bottle' => $service_info_bottle,
                'service_info_face' => $service_info_face,
                'service_info_flower' => $service_info_flower,
                'post_img' => $post_img,
                'post_author' => $post_author,
                'post_status' => $post_status,
                'post_item_focus' => $post_item_focus,
                'post_image_focus' => $post_image_focus,
                'comment_status' => $comment_status,
                'post_password' => $post_password,
                'post_type' => $post_type,
                'post_parent' => 0,
                'view_count' => 0,
                'comment_count' => 0,
                'published_at' => $published_at,
                'created_at' => $date,
                'updated_at' => $date
            ];
            $post_id = $this->store_post($post);
        }else if($action == 'edit' && $post_id != ''){
            $post_name = $this->post_name_replace($post_name, $post_id);
            $post = [
                'post_title' => $post_title,
                'post_name' => $post_name,
                'post_excerpt' => $post_excerpt,
                'post_content' => $post_content,
                'post_function' => $post_function,
                'post_howtouse' => $post_howtouse,
                'service_time_price' => $service_time_price,
                'service_info_bottle' => $service_info_bottle,
                'service_info_face' => $service_info_face,
                'service_info_flower' => $service_info_flower,
                'post_img' => $post_img,
                'post_author' => $post_author,
                'post_status' => $post_status,
                'post_item_focus' => $post_item_focus,
                'post_image_focus' => $post_image_focus,
                'comment_status' => $comment_status,
                'post_password' => $post_password,
                'published_at' => $published_at,
                'updated_at' => $date
            ];
            $this->update_post($post, $post_id);
        }
        $categories = trim($request->post_categories, ',');
        $categories = empty($categories) ? [$this->get_option('post_category_default')] : explode(',', $categories);
        $this->store_post_categories($post_id, $categories);
        $post_tags = trim($request->post_tags, ',');
        $post_tags = empty($post_tags) ? [] : explode(',', $post_tags);
        $this->store_post_tags($post_id, $post_tags, $post_type . '-tag');
        $metas = $request->meta;
        // if(count($metas) > 0){
		if (is_object($metas)) {
            foreach($metas as $key => $value){
                $this->store_post_meta($post_id, $key, $value);
            }
        }
        Session::flash('notify_type', 'success');
        Session::flash('notify_content', 'Successfully.');
        return redirect('/admin/posts/' . $post_type . '/edit/' . $post_id);
    }

    function postSearch(Request $request, $post_type){
        $search_text = $request->search_text;
        $data = $this->search_posts($search_text, $post_type);
        $columns = config('posts.' . $post_type . '.columns');
        if(count($data) == 0){
            return '<tr><td colspan="' . (count($columns) + 1) . '">No items found.</td></tr>';
        }else{
            $display_data = '';
            foreach($data as $value){
                $display_data .= '<tr><td><input type="checkbox" value="' . $value->post_id . '" class="flat check_item" name="table_records"></td>';
                foreach($columns as $column){
                    $display_data .= View::make('admin.posts.columns.' . $column, ['value' => $value, 'post_type' => $post_type])->render();
                }
                $display_data .= '</tr>';
            }
            return $display_data;
        }
    }
}
