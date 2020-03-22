<?php
namespace App\Http\Controllers\Common;
use DB;

trait Post{
    protected function content_replace($content){
        $content = html_entity_decode($content);
        if(strpos($content, '../../../../') != null){
            $content = str_replace('../../../../', url('/') . '/', $content);
        }
        if(strpos($content, '../../../') != null){
            $content = str_replace('../../../', url('/') . '/', $content);
        }
        if(strpos($content, '../../') != null){
            $content = str_replace('../../', url('/') . '/', $content);
        }
        if(strpos($content, '../') != null){
            $content = str_replace('../', url('/') . '/', $content);
        }
        return $content;
    }

    protected function get_post_filter_count($post_type){
        $data = [
            'all' => DB::table('posts')->where('post_type', $post_type)->where('post_status', '!=', 'trash')->count(),
            'publish' => DB::table('posts')->where('post_type', $post_type)->where('post_status', 'publish')->count(),
            'draft' => DB::table('posts')->where('post_type', $post_type)->where('post_status', 'draft')->count(),
            'trash' => DB::table('posts')->where('post_type', $post_type)->where('post_status', 'trash')->count()
        ];
        return $data;
    }

    protected function get_all_posts($post_type, $wheres, $count = 20, $whereIn = [],$pagination = true){
        if ($post_type === 'introduction') {
            $query = DB::table('posts')->where('post_type', $post_type)->where($wheres)->orderBy('post_order', 'ASC');
        } else {
            $query = DB::table('posts')->where('post_type', $post_type)->where($wheres)->orderBy('updated_at', 'DESC');
        }
        if(!empty($whereIn)){
            $query->whereIn($whereIn[0], $whereIn[1]);
        }
        if($pagination) {
            $data = $query->paginate($count);
        }
        else {
            if ($post_type !== 'product' && $post_type !== 'service') {
                $data = $query->limit($count)->get();
            } else {
                $data = $query->limit($count)->get()->toArray();
            }
        }

        if(!empty($data)){
            foreach($data as $key => $value){
                $data[$key]->post_url = $this->get_post_permalink($value->post_name, $value->post_type);
                $data[$key]->comments['approved'] = $this->get_post_comment_count($value->post_id, [['comment_approved', '=', 1], ['comment_deleted', '=', 0], ['comment_spam', '=', 0]]);
                $data[$key]->comments['unapproved'] = $this->get_post_comment_count($value->post_id, [['comment_approved', '=', 0], ['comment_deleted', '=', 0], ['comment_spam', '=', 0]]);
                $data[$key]->categories = $this->get_post_terms($value->post_id, $post_type . '-category');
                $data[$key]->tags = $this->get_post_terms($value->post_id, $post_type . '-tag');
                $data[$key]->author = DB::table('users')->where('id', $value->post_author)->select('name', 'email', 'avatar')->first();
                $post_metas = DB::table('postmeta')->where('post_id', $value->post_id)->get();
                if(!empty($post_metas)){
                    foreach($post_metas as $post_meta){
                        $data[$key]->{$post_meta->meta_key} = $post_meta->meta_value;
                    }
                }
            }
        }
        return $data;
    }

    protected function get_post_comment_count($post_id, $wheres = [['post_id', '!=', '0']]){
        $data = DB::table('comments')->where('post_id', $post_id)->where($wheres)->count();
        return $data;
    }

    protected function get_post_by_id($post_id, $post_type, $comment_count = ''){
        $data = DB::table('posts')->where('post_id', $post_id)->where('post_type', $post_type)->first();
        // if(count($data) > 0){
        if (is_object($data)) {
            $data->post_url = $this->get_post_permalink($data->post_name, $data->post_type);
            $data->categories = $this->get_post_terms($post_id, $post_type . '-category');
            $data->tags = $this->get_post_terms($post_id, $post_type . '-tag');
            if($comment_count == ''){
                $data->comments = $this->get_post_comments($post_id);
            }else{
                $data->comments = $this->get_post_comments($post_id, $comment_count);
            }
            if(count($data->comments) > 0){
                foreach($data->comments as $key => $comment){
                    $data->comments[$key]->author = DB::table('users')->where('id', $comment->user_id)->select('id', 'name', 'email', 'avatar')->first();
                }
            }
            $metas = DB::table('postmeta')->where('post_id', $post_id)->get();
            if(count($metas) > 0){
                foreach($metas as $meta){
                    $data->{$meta->meta_key} = $meta->meta_value;
                }
            }
        }
        return $data;
    }

    protected function get_post_by_slug($slug, $post_type, $comment_count = ''){
        $data = DB::table('posts')->where('post_name', $slug)->where('post_type', $post_type)->first();
        $post_id = $data->post_id;
        // if(count($data) > 0){
        if (is_object($data)) {
            $data->post_url = $this->get_post_permalink($data->post_name, $data->post_type);
            $data->categories = $this->get_post_terms($post_id, $post_type . '-category');
            $data->tags = $this->get_post_terms($post_id, $post_type . '-tag');
            if($comment_count == ''){
                $data->comments = $this->get_post_comments($post_id);
            }else{
                $data->comments = $this->get_post_comments($post_id, $comment_count);
            }
            if(count($data->comments) > 0){
                foreach($data->comments as $key => $comment){
                    $data->comments[$key]->author = DB::table('users')->where('id', $comment->user_id)->select('id', 'name', 'email', 'avatar')->first();
                }
            }
            $metas = DB::table('postmeta')->where('post_id', $post_id)->get();
            if(count($metas) > 0){
                foreach($metas as $meta){
                    $data->{$meta->meta_key} = $meta->meta_value;
                }
            }
        }
        
        return $data;
    }

    protected function get_post_comments($post_id, $count = ''){
        if($count == ''){
            $data = DB::table('comments')->where('post_id', $post_id)->where('comment_spam', 0)->where('comment_deleted', 0)->orderBy('comment_id', 'DESC')->get();
        }else{
            $data = DB::table('comments')->where('post_id', $post_id)->where('comment_spam', 0)->where('comment_deleted', 0)->orderBy('comment_id', 'DESC')->limit($count)->get();
        }
        if(count($data) > 0){
            foreach($data as $key => $value){
                $data[$key]->comments['approved'] = $this->get_post_comment_count($value->post_id, [['comment_approved', '=', 1], ['comment_deleted', '=', 0], ['comment_spam', '=', 0]]);
                $data[$key]->comments['unapproved'] = $this->get_post_comment_count($value->post_id, [['comment_approved', '=', 0], ['comment_deleted', '=', 0], ['comment_spam', '=', 0]]);
                $data[$key]->author = $this->get_user_of_comment($value->user_id);
                $post = DB::table('posts')->where('post_id', $value->post_id)->select('post_id', 'post_title', 'post_name', 'post_type', 'comment_count')->first();
                $data[$key]->post = $post;
                if(count($post) > 0){
                    $data[$key]->post_url = $this->get_post_permalink($post->post_name, $post->post_type);
                }
                $comment_metas = DB::table('commentmeta')->where('comment_id', $value->comment_id)->get();
                if(count($comment_metas) > 0){
                    foreach($comment_metas as $comment_meta){
                        $data[$key]->{$comment_meta->commentmeta_key} = $comment_meta->commentmeta_value;
                    }
                }
                if($value->comment_parent != 0 && !empty($value->comment_parent)){
                    $data[$key]->parent = $this->get_comment_by_id($value->comment_parent);
                }
            }
        }
        return $data;
    }

    protected function get_post_terms($post_id, $taxonomy){
        $data = DB::table('term_relationships')->where('object_id', $post_id)->join('term_taxonomy', 'term_relationships.term_taxonomy_id', '=', 'term_taxonomy.term_taxonomy_id')->where('taxonomy', $taxonomy)->get();
        return $data;
    }

    protected function post_name_replace($post_name, $post_id = ''){
        $index = 2;
        $new_name = $post_name;
        $check = DB::table('posts')->where('post_name', $new_name)->where('post_id', '!=', $post_id)->first();
        // while(count($check) > 0){
        while(is_object($check) > 0){
            $new_name = $post_name . '-' . $index;
            $index++;
            $check = DB::table('posts')->where('post_name', $new_name)->where('post_id', '!=', $post_id)->first();
        }
        return $new_name;
    }

    protected function store_post($post){
        $post_id = DB::table('posts')->insertGetId($post);
        return $post_id;
    }

    protected function update_post($post, $post_id){
        $status = DB::table('posts')->where('post_id', $post_id)->update($post);
        return $status;
    }

    protected function store_post_categories($post_id, $categories){
        $get_post_type = DB::table('posts')->where('post_id', $post_id)->select('post_type')->first();
        // if(count($get_post_type) > 0){
        if(is_object($get_post_type) > 0){
            $post_type = $get_post_type->post_type;
            if(count($categories) > 0){
                $term_relationships = [];
                foreach($categories as $value){
                    $check = DB::table('term_relationships')->where('object_id', $post_id)->where('term_taxonomy_id', $value)->count();
                    if($check == 0){
                        DB::table('term_taxonomy')->where('term_taxonomy_id', $value)->increment('count', 1);
                    }
                    $term_relationships[] = [
                        'object_id' => $post_id,
                        'term_taxonomy_id' => $value
                    ];
                }
                if(count($term_relationships) > 0){
                    DB::table('term_relationships')->join('term_taxonomy', 'term_relationships.term_taxonomy_id', '=', 'term_taxonomy.term_taxonomy_id')->where('term_relationships.object_id', $post_id)->where('term_taxonomy.taxonomy', $post_type . '-category')->delete();
                    DB::table('term_relationships')->insert($term_relationships);
                }
            }
        }
    }

    protected function store_post_tags($post_id, $post_tags, $taxonomy){
        $get_post_type = DB::table('posts')->where('post_id', $post_id)->select('post_type')->first();
        // if(count($get_post_type) > 0){
        if(is_object($get_post_type) > 0){
            $post_type = $get_post_type->post_type;
            if(count($post_tags) > 0){
                $term_relationships = [];
                foreach($post_tags as $value){
                    $term_taxonomy = [
                        'term_name' => trim($value),
                        'term_slug' => str_slug(trim($value)),
                        'taxonomy' => $taxonomy,
                        'term_description' => '',
                        'term_parent' => 0,
                        'count' => 0
                    ];
                    $term_taxonomy_id = $this->add_term_taxonomy($term_taxonomy);
                    if($term_taxonomy_id == false){
                        $get_term_taxonomy = $this->get_taxonomy_by_slug($taxonomy, str_slug(trim($value)));
                        $term_taxonomy_id = $get_term_taxonomy->term_taxonomy_id;
                    }
                    $check = DB::table('term_relationships')->where('object_id', $post_id)->where('term_taxonomy_id', $term_taxonomy_id)->count();
                    if($check == 0){
                        DB::table('term_taxonomy')->where('term_taxonomy_id', $value)->increment('count', 1);
                    }
                    $term_relationships[] = [
                        'object_id' => $post_id,
                        'term_taxonomy_id' => $term_taxonomy_id
                    ];
                }
                if(count($term_relationships) > 0){
                    DB::table('term_relationships')->join('term_taxonomy', 'term_relationships.term_taxonomy_id', '=', 'term_taxonomy.term_taxonomy_id')->where('term_relationships.object_id', $post_id)->where('term_taxonomy.taxonomy', $post_type . '-tag')->delete();
                    DB::table('term_relationships')->insert($term_relationships);
                }
            }
        }
    }

    protected function store_post_meta($post_id, $meta_key, $meta_value){
        if($meta_key == 'gallery'){
            if(!empty(trim($meta_value))){
                $get_data = explode(',', trim(trim($meta_value), ','));
                $meta_value = json_encode($get_data);
            }         
        }
        $check = DB::table('postmeta')->where('post_id', $post_id)->where('meta_key', $meta_key)->count();
        if($check == 0){
            DB::table('postmeta')->insertGetId([
                'post_id' => $post_id,
                'meta_key' => $meta_key,
                'meta_value' => $meta_value
            ]);
        }else{
            DB::table('postmeta')->where('post_id', $post_id)->where('meta_key', $meta_key)->update([
                'meta_value' => $meta_value
            ]);
        }
    }

    protected function search_posts($search_text, $post_type, $count = 20){
        $data = DB::table('posts')->where('posts.post_type', $post_type)->join('users', 'posts.post_author', '=', 'users.id')->where(function($query) use ($search_text){
            $query->where('posts.post_title', 'LIKE', '%' . $search_text . '%')->orWhere('posts.post_name', 'LIKE', '%' . $search_text . '%')->orWhere('users.name', 'LIKE', '%' . $search_text . '%')->orWhere('posts.published_at', 'LIKE', '%' . $search_text . '%');
        })->select('posts.*')->orderBy('updated_at', 'DESC')->paginate($count);
        if(!empty($data)){
            foreach($data as $key => $value){
                $data[$key]->comments['approved'] = $this->get_post_comment_count($value->post_id, [['comment_approved', '=', 1], ['comment_deleted', '=', 0], ['comment_spam', '=', 0]]);
                $data[$key]->comments['unapproved'] = $this->get_post_comment_count($value->post_id, [['comment_approved', '=', 0], ['comment_deleted', '=', 0], ['comment_spam', '=', 0]]);
                $data[$key]->categories = $this->get_post_terms($value->post_id, $post_type . '-category');
                $data[$key]->tags = $this->get_post_terms($value->post_id, $post_type . '-tag');
                $data[$key]->author = DB::table('users')->where('id', $value->post_author)->select('name', 'email', 'avatar')->first();
                $post_metas = DB::table('postmeta')->where('post_id', $value->post_id)->get();
                if(!empty($post_metas)){
                    foreach($post_metas as $post_meta){
                        $data[$key]->{$post_meta->meta_key} = $post_meta->meta_value;
                    }
                }
            }
        }
        return $data;
    }

    protected function delete_post($post_id){
        DB::table('term_relationships')->where('object_id', $post_id)->delete();
        DB::table('comments')->where('post_id', $post_id)->delete();
        DB::table('postmeta')->where('post_id', $post_id)->delete();
        DB::table('posts')->where('post_id', $post_id)->delete();
    }

    protected function trash_post($post_id){
        $term_relationships = DB::table('term_relationships')->where('object_id', $post_id)->get();
        if(count($term_relationships) > 0){
            foreach($term_relationships as $value){
                DB::table('term_taxonomy')->where('term_taxonomy_id', $value->term_taxonomy_id)->decrement('count', 1);
            }
        }
        DB::table('posts')->where('post_id', $post_id)->update(['post_status' => 'trash']);
    }

    protected function restore_post($post_id){
        $term_relationships = DB::table('term_relationships')->where('object_id', $post_id)->get();
        if(count($term_relationships) > 0){
            foreach($term_relationships as $value){
                DB::table('term_taxonomy')->where('term_taxonomy_id', $value->term_taxonomy_id)->increment('count', 1);
            }
        }
        DB::table('posts')->where('post_id', $post_id)->update(['post_status' => 'publish']);
    }
}