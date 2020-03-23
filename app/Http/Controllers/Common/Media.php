<?php
namespace App\Http\Controllers\Common;
use Illuminate\Support\Facades\Storage;
use DB;
use Auth;
use Image;

trait Media{

    protected function get_media_item($media_location, $media_name, $media_extension){
        $data = DB::table('media')->where('media_name', $media_name)->where('media_extension', $media_extension)->where('media_location', $media_location)->first();
        return $data;
    }

    /**
        @file               = request file upload
        @storage_path       = path to upload in storage/app/
        @use_date_folder    = create date folder in @storage
        @file_name          = file name of file upload
    **/

    protected function media_upload($file, $storage_path, $use_date_folder = false, $file_name = ''){
//        $max_size = $file->getMaxFilesize();
        $file_size = $file->getSize();
//        if($file_size > $max_size){
//            return false;
//        }else{
            $storage_path = trim($storage_path, '/');
            $date_folder = date('Y-m-d');
            if($use_date_folder == true){
                $path = $storage_path . '/' . $date_folder;
            }else{
                $path = $storage_path;
            }
            $extension = $file->getClientOriginalExtension();
            $mime_type = $file->getClientMimeType();
            $file_type = $this->get_media_type($extension);
            if($file_name == ''){
                $slug_name = str_replace('.' . $extension, '', trim($file->getClientOriginalName()));
                // $file_name = str_slug($slug_name);
				$file_name = $slug_name;
            }else{
                $file_name = trim($file_name);
            }
            $filename_origin = $file_name;
            $get_file_name = DB::table('media')->where('media_name', $file_name)->count();
            $file_index = 2;
            while($get_file_name > 0){
                $file_name = $filename_origin . '-' . $file_index;
                $get_file_name = DB::table('media')->where('media_name', $file_name)->where('media_extension', $extension)->count();
                $file_index++;
            }
            $width = 0;
            $height = 0;
            $media_name = $file_name;
            if($file_type == 'image' || $file_type == 'icon'){
                list($width, $height) = getimagesize($file);
                if($width < $height){
                    $media_style = 'portrait';
                }else{
                    $media_style = 'landscape';
                }
                $file_name = $file_name . '_size_' . $width . 'x' . $height;
            }
            $file_path = Storage::putFileAs($path, $file, $file_name . '.' . $extension);
            $media_source = url('/') . Storage::url($file_path);
            $media_style = 'landscape';
            $media_path = str_replace($file_name . '.' . $extension, '', $media_source);
            if($file_type == 'image' || $file_type == 'icon'){
                $media_url = $media_source;
                if($file_type == 'image'){
                    // $this->makeThumbnail($media_source, storage_path('/app/' . $path . '/'), $media_name, $extension);
                }
            }else{
                $media_url = url('/contents/images/media_thumbs/' . $file_type . '.png');
            }
            $media_author = !Auth::guest() ? Auth::user()->id : 0;
            $media = [
                'media_name' => $media_name,
                'media_extension' => $extension,
                'media_width' => $width,
                'media_height' => $height,
                'media_style' => $media_style,
                'media_size' => $file_size,
                'mime_type' => $mime_type,
                'media_type' => $file_type,
                'media_source' => $media_source,
                'media_url' => $media_url,
                'media_alt' => $media_name,
                'media_description' => '',
                'media_path' => $media_path,
                'media_location' => $path,
                'media_folder' => $date_folder,
                'media_author' => $media_author,
                'created_at' => date('Y-m-d H:i:s')
            ];
            $media_id = DB::table('media')->insertGetId($media);
            $media['media_id'] = $media_id;
            return $media;
        // }
    }

    protected function makeThumbnail($img_path, $save_path, $file_name, $file_extension){
        $this->cropImage($img_path, (double) $this->get_option('thumbnail_width'), (double) $this->get_option('thumbnail_height'), $save_path, $file_name . '_thumb.' . $file_extension);
    }

    protected function cropImage($img_path, $crop_width, $crop_height, $save_path, $full_name){
        $img = Image::make($img_path);
        $width = $img->width();
        $height = $img->height();
        if($crop_width <= $width && $crop_height <= $height){
            if($width > $height){
                $ratio = $width / $height;
                if($crop_width > $crop_height){
                    $img->resize($crop_width, round($ratio * $crop_width));
                    $height = $img->height();
                    $img->crop($crop_width, $crop_height, 0, round(($height / 2) - ($crop_height / 2)));
                    $img->save($save_path . $full_name);
                }else{
                    $img->resize($ratio * $crop_height, $crop_height);
                    $width = $img->width();
                    $img->crop($crop_width, $crop_height, round(($width / 2) - ($crop_width / 2)), 0);
                    $img->save($save_path . $full_name);
                }
            }else if($width < $height){
                $ratio = $height / $width;
                if($crop_width >= $crop_height){
                    $img->resize($crop_width, round($ratio * $crop_width));
                    $height = $img->height();
                    $img->crop($crop_width, $crop_height, 0, round(($height / 2) - ($crop_height / 2)));
                    $img->save($save_path . $full_name);
                }else{
                    $img->resize($ratio * $crop_height, $crop_height);
                    $width = $img->width();
                    $img->crop($crop_width, $crop_height, round(($width / 2) - ($crop_width / 2)), 0);
                    $img->save($save_path . $full_name);
                }
            }else{
                $img->resize($crop_width, $crop_height);
                $img->save($save_path . $full_name);
            }
        }else{
            $img->save($save_path . $full_name);
        }
    }

    protected function get_media($limit = 100){
        if($limit == 0){
            $data = DB::table('media')->orderBy('media_id', 'DESC')->get();
        }else{
            $data = DB::table('media')->orderBy('media_id', 'DESC')->offset(0)->limit($limit)->get();
        }
        return $data;
    }

    protected function media_date_filter(){
        $data = DB::table('media')->distinct()->select('media_folder')->orderBy('media_folder', 'DESC')->get();
        return $data;
    }

    protected function get_media_lazy($media_type, $media_date, $media_search, $offset, $limit){
        $type_operator = '=';
        if($media_type == 'all'){
            $media_type = '';
            $type_operator = '!=';
        }
        $date_operator = '=';
        if($media_date == 'all'){
            $media_date = '';
            $date_operator = '!=';
        }
        $search_operator = 'like';
        if($media_search == ''){
            $search_operator = '!=';
        }
        $data = DB::table('media')->where('media_type', $type_operator, $media_type)->where('media_folder', $date_operator, $media_date)->where('media_name', $search_operator, '%' . $media_search . '%')->orderBy('media_id', 'DESC')->offset($offset)->limit($limit)->get();
        return $data;
    }

    protected function get_media_filter($media_type, $media_date, $media_search, $limit = 100){
        $type_operator = '=';
        if($media_type == 'all'){
            $media_type = '';
            $type_operator = '!=';
        }
        $date_operator = '=';
        if($media_date == 'all'){
            $media_date = '';
            $date_operator = '!=';
        }
        $search_operator = 'like';
        if($media_search == ''){
            $search_operator = '!=';
        }
        $data = DB::table('media')->where('media_type', $type_operator, $media_type)->where('media_folder', $date_operator, $media_date)->where('media_name', $search_operator, '%' . $media_search . '%')->orderBy('media_id', 'DESC')->offset(0)->limit($limit)->get();
        return $data;
    }

    protected function get_media_by_id($media_id){
        $data = DB::table('media')->where('media_id', $media_id)->first();
        return $data;
    }

    protected function get_media_by_name($media_name){
        $data = DB::table('media')->where('media_name', $media_name)->first();
        return $data;
    }

    protected function update_media($media_id, $media){
        DB::table('media')->where('media_id', $media_id)->update($media);
    }

    protected function delete_media($media_id){
        $media = DB::table('media')->where('media_id', $media_id)->first();
        Storage::delete($media->media_location . '/' . $media->media_name . '.' . $media->media_extension);
        DB::table('media')->where('media_id', $media_id)->delete();
    }

    protected function get_media_type($type){
        // image, audio, video, document, other
        $image = ['JPE','JPEG','JPG','PNG', 'GIF', 'SVG', 'ICO'];
        $icon = ['ICO'];
        $video = ['WEBM', 'MKV', 'FLV', 'VOB', 'OGV', 'OGG', 'DRC', 'GIFV', 'MNG', 'AVI', 'MOV', 'QT', 'WMV', 'YUV', 'RM', 'RMVB', 'ASF', 'AMV', 'MP4', 'M4P', 'M4V', 'MPG', 'MP2', 'MPEG', 'MPE', 'MPV', 'SVI', '3GP', '3G2', 'MXF', 'ROQ', 'NSV', 'F4V', 'F4P', 'F4A', 'F4B'];
        $audio = ['3GP','AA','AAC','AAX','ACT','AIFF','AMR','APE','AU','AWB','DCT','DSS','DVF','FLAC','GSM','IKLAX','IVS','M4A','M4B','M4P','MMF','MP3','MPC','MSV','OGG','OPUS','RA','RAW','SLN','TTA','VOX','WAV','WMA','WV','WEBM'];
        $document = ['DOC', 'DOCX', 'XLS', 'XLSX', 'PDF', 'HTM', 'HTML', 'TXT'];
        $file = ['ANI','BMP','CAL','CGM','FAX','JBG','IMG','MAC','PBM','PCD','PCX','PCT','PGM','PPM','PSD','RAS','TGA','TIFF','WMF','AI'];
        $compress = ['RAR','ZIP','GZIP'];
        $javascript = ['JS'];
        $css = ['CSS'];
        $sql = ['SQL'];
        $xml = ['XML', 'XSD', 'DTD'];
        $res = 'other';
        if(in_array(strtoupper($type), $icon)){
            $res = 'icon';
        }
        if(in_array(strtoupper($type), $image)){
            $res = 'image';
        }
        if(in_array(strtoupper($type), $audio)){
            $res = 'audio';
        }
        if(in_array(strtoupper($type), $video)){
            $res = 'video';
        }
        if(in_array(strtoupper($type), $document)){
            $res = 'document';
        }
        if(in_array(strtoupper($type), $file)){
            $res = 'file';
        }
        if(in_array(strtoupper($type), $compress)){
            $res = 'compress';
        }
        if(in_array(strtoupper($type), $javascript)){
            $res = 'javascript';
        }
        if(in_array(strtoupper($type), $css)){
            $res = 'css';
        }
        if(in_array(strtoupper($type), $sql)){
            $res = 'sql';
        }
        if(in_array(strtoupper($type), $xml)){
            $res = 'xml';
        }
        return $res;
    }
}