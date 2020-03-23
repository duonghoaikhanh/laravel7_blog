<?php
namespace App\Http\Controllers\Common;
use DB;

trait Chap{

    public static function theme(){
        $data = self::get_option('theme_active');
        return $data;
    }

    public static function site_name(){
        $data = self::get_option('site_name');
        return $data;
    }

    public static function timezone(){
        $data = self::get_option('timezone');
        return $data;
    }

    public static function logo(){
        $data = self::get_option('logo');
        return $data;
    }

    public static function custom_css(){
        $data = self::get_option('custom_css');
        return $data;
    }

    public static function site_language(){
        $data = self::get_option('site_language');
        return $data;
    }

    public static function app_config(){
        config([
            'app.name' => self::site_name(),
            'app.url' => url('/'),
            'app.timezone' => self::timezone(),
            'app.locale' => self::site_language()
        ]);
    }

    public static function tracking_by_date($days = 1){
        $today = date('Y-m-d');
        $data = 0;
        for($i = ($days - 1); $i >= 0; $i--){
            $old_day = date('Y-m-d', (strtotime($today) - 3600*24*$i));
            $year = date('Y', strtotime($old_day));
            $month = date('m', strtotime($old_day));
            $day = date('d', strtotime($old_day));
            $count = DB::table('trackings')->whereYear('tracking_date', $year)->whereMonth('tracking_date', $month)->whereDay('tracking_date', $day)->count();
            $data = (int)$data + (int)$count;
        }
        return $data;
    }

    public static function statistic(){
        $today = date('Y-m-d');
        $today_year = date('Y', strtotime($today));
        $today_month = date('m', strtotime($today));
        $today_day = date('d', strtotime($today));
        $today_count = DB::table('trackings')->whereYear('tracking_date', $today_year)->whereMonth('tracking_date', $today_month)->whereDay('tracking_date', $today_day)->count();
        $yesterday = date('Y-m-d', (strtotime($today) - 3600*24));
        $yesterday_year = date('Y', strtotime($yesterday));
        $yesterday_month = date('m', strtotime($yesterday));
        $yesterday_day = date('d', strtotime($yesterday));
        $yesterday_count = DB::table('trackings')->whereYear('tracking_date', $yesterday_year)->whereMonth('tracking_date', $yesterday_month)->whereDay('tracking_date', $yesterday_day)->count();
        $week_count = self::tracking_by_date(7);
        $month_count = self::tracking_by_date(30);
        $data['today'] = $today_count;
        $data['yesterday'] = $yesterday_count;
        $data['week'] = $week_count;
        $data['month'] = $month_count;
        return $data;
    }

    public static function media($media_source, $thumb = false){
        $media = DB::table('media')->where('media_source', $media_source)->first();
        $data = $media->media_url;
        // if(sizeof($media) > 0){
		if (is_object($media)) {
            if($media->media_type == 'image'){
                if($thumb == true){
                    $data = str_replace('_size_' . $media->media_width . 'x' . $media->media_height . '.' . $media->media_extension, '_thumb.' . $media->media_extension, $media->media_url);
                }else{
                    $data = $media->media_url;
                }
            }
        }
        return $data;
    }

    protected function update_option($option_name, $option_value, $autoload = 1){
        $check = DB::table('options')->where('option_name', $option_name)->first();
        if(count($check) > 0){
            $option = [
                'option_value' => $option_value,
                'autoload' => $autoload
            ];
            DB::table('options')->where('option_name', $option_name)->update($option);
        }else{
            $option = [
                'option_name' => $option_name,
                'option_value' => $option_value,
                'autoload' => $autoload
            ];
            DB::table('options')->insert($option);
        }
        return true;
    }

    public static function get_option($option_name){
        $option = DB::table('options')->where('option_name', $option_name)->first();
        // if(count($option) > 0){
        if(is_object($option)){
            return $option->option_value;
        }else{
            return '';
        }
    }

    public static function get_options($args = []){
        $data = [];
        if(!is_null($args)){
            foreach($args as $value){
                $data[$value] = '';
            }
            $query = DB::table('options')->select('option_name', 'option_value');
            if(!empty($args))
                $query->whereIn('option_name', $args);
            $data = $query->pluck('option_value', 'option_name')->toArray();
            
        }
        return $data;
    }

    public static function widget($widget_name = ''){
        if($widget_name == ''){
            return '';
        }else{
            $data = DB::table('widgets')->where('widget_name', $widget_name)->select('widget_content')->first();
            if(count($data) > 0){
                return nl2br($data->widget_content);
            }else{
                return '';
            }
        }
    }

    public static function widgets($widgets){
        $data = [];
        if(count($widgets) > 0){
            foreach($widgets as $value){
                $data[$value] = '';	
                $get_widgets = DB::table('widgets')->where('widget_name', $value)->get();
                if(count($get_widgets) > 0){
                    foreach($get_widgets as $widget){
                        $data[$widget->widget_name] = $widget->widget_content;
                    }
                }
            }
        }
        return $data;
    }

    public static function menu($menu_location = '', $class = ''){
        if($menu_location == ''){
            return '';
        }else{
            $get_menu_id = DB::table('menus')->where('menu_location', 'like', '%"' . $menu_location . '"%')->select('menu_id')->first();
            if(count($get_menu_id) > 0){
                $menu_id = $get_menu_id->menu_id;
                $menu_items = DB::table('menu_items')->where('menu_id', $menu_id)->orderBy('sort_order')->get();
                $data = self::menu_item($menu_items, 1, 0, $menu_location . ' menu_' . $menu_id, $class);
                return $data;
            }else{
                return '';
            }
        }
    }

    public static function menu_item($items, $level, $menu_parent = 0, $menu_class_name = '', $class = ''){
        $result = '';
        if($menu_parent == 0){
            $menu_class = $menu_class_name;
        }else{
            $menu_class = 'sub_menu_' . $level;
        }
        foreach ($items as $item){
            if ($item->menu_item_parent == $menu_parent) {
                if(!empty($item->menu_item_icon)){
                    $menu_icon = '<i class="'.$item->menu_item_icon.'"></i>';
                }else{
                    $menu_icon = '';
                }
                $blank_target = $item->menu_item_blank == 1 ? ' target="_blank"' : '';
                $result .= '<li class="'.$item->menu_item_class.'">
                    <a href="'.$item->menu_item_link.'"'.$blank_target.'>'.$menu_icon.' <span>'.$item->menu_item_name.'</span></a>
                    ' . self::menu_item($items, $level+1, $item->menu_item_id) . '
                </li>';
            }
        }
        return $result ? '<ul class="' . $menu_class . ' ' . $class . '">' . $result . '</ul>' : '';
    }

    public static function main_menu(){
        $get_menu_id = DB::table('menus')->where('menu_location', 'like', '%"' . 'main_menu' . '"%')->select('menu_id')->first();
        if(count($get_menu_id) > 0){
            $menu_id = $get_menu_id->menu_id;
            $menu_items = DB::table('menu_items')->where('menu_id', $menu_id)->orderBy('sort_order')->get();
            $data = self::main_menu_item($menu_items, 1, 0);
            return $data;
        }else{
            return '';
        }
    }

    public static function main_menu_item($items, $level, $menu_parent = 0){
        $result = '';
        if($menu_parent == 0){
            $menu_class = 'navbar-nav ml-auto';
            $menu_id = 'id="menu" ';
            $item_custom = '<li class="nav-item d-block d-md-none">
                <a class="nav-link text-right nav_remove" href="#">
                    <i class="fa fa-remove"></i>
                </a>
                <div class="clearfix"></div>
            </li>';
        }else{
            $menu_class = 'dropdown-menu';
            $menu_id = '';
            $item_custom = '';
        }
        foreach ($items as $item){
            if ($item->menu_item_parent == $menu_parent) {
                if(!empty($item->menu_item_icon)){
                    $menu_icon = '<i class="'.$item->menu_item_icon.'"></i>';
                }else{
                    $menu_icon = '';
                }
                $blank_target = $item->menu_item_blank == 1 ? ' target="_blank"' : '';
                if($level <= 1){
                    $result .= '<li class="nav-item '.$item->menu_item_class.'">';
                }
                if($level <= 1){
                    $link_class = 'nav-link dropdown-toggle';
                }else{
                    $link_class = 'dropdown-item';
                }
                $result .= '<a class="' . $link_class . '" href="'.$item->menu_item_link.'"'.$blank_target.'>'.$menu_icon.' <span>'.$item->menu_item_name.'</span></a>
                    ' . self::main_menu_item($items, $level+1, $item->menu_item_id);
                if($level <= 1){
                    $result .= '</li>';
                }
            }
        }
        return $result ? '<ul ' . $menu_id . 'class="' . $menu_class . '">' . $item_custom . $result . '</ul>' : '';
    }

    public static function get_post_permalink($post_name, $post_type = 'post'){
        $part = config('permalink.post.' . $post_type . '.part');
        $use_html = config('permalink.post.' . $post_type . '.use_html');
        $html = $use_html == true ? '.html' : '';
        if($part == ''){
            return url('/' . $post_name . $html);
        }else{
            return url('/' . $part . '/' . $post_name . $html);
        }
    }

    public static function get_taxonomy_permalink($term_slug, $taxonomy){
        $part = config('permalink.taxonomy.' . $taxonomy . '.part');
        if($part == ''){
            return url('/' . $term_slug);
        }else{
            return url('/' . $part . '/' . $term_slug);
        }
    }

    public static function comment_unapproved_count(){
        $data = DB::table('comments')->where('post_id', '!=', 0)->where('comment_deleted', 0)->where('comment_spam', 0)->where('comment_approved', 0)->count();
        return $data;
    }

    public static function slide($slide_name){
        $slide_id = self::get_option('slide_' . $slide_name);
        $slide_html = '';
        if(empty($slide_id)){
            $slide_html = 'No data.';
        }else{
            $slide = DB::table('slides')->where('slide_id', $slide_id)->first();
            if(count($slide) > 0){
                $slide_options = json_decode($slide->slide_options);
                $slide_items = DB::table('slide_items')->where('slide_id', $slide_id)->orderBy('sort_order')->orderBy('slide_item_id')->get();
                if(count($slide_items) > 0){
                    $slide_html .= '<style>.slide_'.$slide->slide_name.'{width: '.$slide_options->width.';height: '.$slide_options->height.';margin-top: '.$slide_options->margin_top.';margin-left: '.$slide_options->margin_left.';margin-bottom: '.$slide_options->margin_bottom.';margin-right: '.$slide_options->margin_right.';overflow:hidden}.slide_'.$slide->slide_name.' .img_wrapper{height: '.$slide_options->height.';}.owl-dots{position: absolute;left:0;right:0;bottom:'.$slide_options->dot_bottom.';}.owl-theme .owl-nav{margin-top:0;}.owl-theme .owl-nav [class*=owl-]{font-size:28px;background-color: rgba(0,0,0,0.3);border-radius:0;margin-top: -24px;}.owl-prev{position: absolute;top:50%;left:10px;}.owl-next{position: absolute;top:50%;right:10px;}
                        @media screen and (max-width: 768px){
                            .slide_'.$slide->slide_name.'{height:auto;
                        }
                    </style>';
                    $slide_html .= '<div class="slide_'.$slide->slide_name.' owl-carousel owl-theme">';
                    foreach($slide_items as $value){
                        $slide_item_options = json_decode($value->slide_item_options);
                        if($slide_item_options->display == 1){
                            $slide_html .= '<div class="slide_item">';
                            if($value->slide_item_link == ''){
                                $slide_html .= '<img class="portrait" src="'.$value->slide_item_image.'">';
                            }else{
                                $slide_html .= '<a href="'.$value->slide_item_link.'">
                                    <img class="portrait" src="'.$value->slide_item_image.'">
                                </a>';
                            }
                            if($slide_item_options->caption == 1){
                                $_bg_color = empty($slide_item_options->caption_background_color) ? 'none' : $slide_item_options->caption_background_color;
                                $_caption_left = empty($slide_item_options->caption_left) ? 0 : $slide_item_options->caption_left;
                                $_caption_right = empty($slide_item_options->caption_right) ? 0 : $slide_item_options->caption_right;
                                $_caption_bottom = empty($slide_item_options->caption_bottom) ? 0 : $slide_item_options->caption_bottom;
                                $_caption_padding_top = empty($slide_item_options->caption_padding_top) ? 0 : $slide_item_options->caption_padding_top;
                                $_caption_padding_left = empty($slide_item_options->caption_padding_left) ? 0 : $slide_item_options->caption_padding_left;
                                $_caption_padding_bottom = empty($slide_item_options->caption_padding_bottom) ? 0 : $slide_item_options->caption_padding_bottom;
                                $_caption_padding_right = empty($slide_item_options->caption_padding_right) ? 0 : $slide_item_options->caption_padding_right;
                                $_caption_title_font_size = empty($slide_item_options->caption_title_font_size) ? 14 : $slide_item_options->caption_title_font_size;
                                $_caption_title_color = empty($slide_item_options->caption_title_color) ? '#ffffff' : $slide_item_options->caption_title_color;
                                $_caption_description_font_size = empty($slide_item_options->caption_description_font_size) ? 14 : $slide_item_options->caption_description_font_size;
                                $_caption_description_color = empty($slide_item_options->caption_description_color) ? '#ffffff' : $slide_item_options->caption_description_color;
                                $_caption_text_align = empty($slide_item_options->caption_text_align) ? 'center' : $slide_item_options->caption_text_align;
                                $slide_html .= '<div class="slide_caption" style="position:absolute;background-color:'.$_bg_color.';left:'.$_caption_left.';right:'.$_caption_right.';bottom:'.$_caption_bottom.';padding-top:'.$_caption_padding_top.';padding-left:'.$_caption_padding_left.';padding-bottom:'.$_caption_padding_bottom.';padding-right:'.$_caption_padding_right.';text-align:'.$_caption_text_align.';">';
                                $slide_html .= '<h3 style="margin:0;font-size:'.$_caption_title_font_size.';color:'.$_caption_title_color.';">'.$slide_item_options->caption_title.'</h3>';
                                $slide_html .= '<p style="margin:0;margin-top:5px;font-size:'.$_caption_description_font_size.';color:'.$_caption_description_color.';">'.$slide_item_options->caption_description.'</p>';
                                $slide_html .= '</div>';
                            }
                            $slide_html .= '</div>';
                        }
                    }
                    $slide_html .= '</div>';
                    $_items = empty($slide_options->items) ? 1 : $slide_options->items;
                    $_auto_play_timeout = empty($slide_options->auto_play_timeout) ? 5000 : $slide_options->auto_play_timeout;
                    $_auto_play_speed = empty($slide_options->auto_play_speed) ? 'false' : $slide_options->auto_play_speed;
                    $_smart_speed = empty($slide_options->smart_speed) ? 250 : $slide_options->smart_speed;
                    $_animation_in = empty($slide_options->animation_in) ? 'false' : $slide_options->animation_in;
                    $_animation_out = empty($slide_options->animation_out) ? 'false' : $slide_options->animation_out;
                    $_loop = $slide_options->loop == 1 ? 'true' : 'false';
                    $_auto_play = $slide_options->auto_play == 1 ? 'true' : 'false';
                    $_lazy_load = $slide_options->lazy_load == 1 ? 'true' : 'false';
                    $_center = $slide_options->center == 1 ? 'true' : 'false';
                    $_dot = $slide_options->dot == 1 ? 'true' : 'false';
                    $_nav_icon = $slide_options->nav_icon == 1 ? 'true' : 'false';
                    $_mouse_drag = $slide_options->mouse_drag == 1 ? 'true' : 'false';
                    $_touch_drag = $slide_options->touch_drag == 1 ? 'true' : 'false';
                    $slide_html .= '<script type="text/javascript">
                        $(".slide_'.$slide->slide_name.'").owlCarousel({
                            items:'.$_items.',
                            loop:'.$_loop.',
                            autoplay:'.$_auto_play.',
                            autoplayHoverPause:true,
                            lazyLoad:'.$_lazy_load.',
                            center:'.$_center.',
                            dots:'.$_dot.',
                            nav:'.$_nav_icon.',
                            navText:["<i class=\"glyphicon glyphicon-chevron-left\"></i>","<i class=\"glyphicon glyphicon-chevron-right\"></i>"],
                            mouse_drag:'.$_mouse_drag.',
                            touch_drag:'.$_touch_drag.',
                            autoplayTimeout:'.$_auto_play_timeout.',
                            autoplaySpeed:'.$_auto_play_speed.',
                            smartSpeed:'.$_smart_speed.',
                            animateIn:"'.$_animation_in.'",
                            animateOut:"'.$_animation_out.'",
                        });
                    </script>';
                }else{
                    $slide_html = 'No data.';
                }
            }else{
                $slide_html = 'No data.';
            }
        }
        return $slide_html;
    }

    public static function word_limit($str = '', $limit = 0, $split = '...'){
        if(strpos($str, ' ')){
            $str_arr = explode(' ', $str);
            if(count($str_arr) > $limit){
                $new_str = array_slice($str_arr, 0, $limit);
                return implode(' ', $new_str) . $split;
            }else{
                return $str;
            }
        }else{
            return $str;
        }
    }
    
}