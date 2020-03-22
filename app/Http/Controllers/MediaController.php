<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function __construct(){
        
    }

    function getMedia(){
    	$data = $this->get_media();
        $date_filter = $this->media_date_filter();
    	return view('admin.media.index', ['data' => $data, 'date_filter' => $date_filter]);
    }

    function postMedia(Request $request){
    	$file = $request->file('file');
    	$media = $this->media_upload($file, 'public/uploads', true);
    	return $media;
    }

    function getMediaLazy(Request $request){
        $offset = $request->offset;
        $limit = $request->limit;
        $media_type = $request->media_type;
        $media_date = $request->media_date;
        $media_search = $request->media_search;
        $data = $this->get_media_lazy($media_type, $media_date, $media_search, $offset, $limit);
        return response()->json($data);
    }

    function getMediaFilter(Request $request){
        $media_type = $request->media_type;
        $media_date = $request->media_date;
        $media_search = $request->media_search;
        $data = $this->get_media_filter($media_type, $media_date, $media_search);
        return $data;
    }

    function getMediaAlone(Request $request){
        $media_id = $request->media_id;
        $data = $this->get_media_by_id($media_id);
        return response()->json($data);
    }

    function postSaveMedia(Request $request){
        $media_id = $request->media_id;
        $media_alt = $request->media_alt;
        $media_description = $request->media_description;
        $media = ['media_alt' => $media_alt, 'media_description' => $media_description];
        $this->update_media($media_id, $media);
        return 'true';
    }

    function postDeleteMedia(Request $request){
        $media_id = $request->media_id;
        $this->delete_media($media_id);
        return 'true';
    }

    function postDeleteMultiMedia(Request $request){
        $media_ids = $request->media_ids;
        if(count($media_ids) > 0){
            foreach($media_ids as $media_id){
                $this->delete_media($media_id);
            }
            return 'true';
        }else{
            return 'false';
        }
    }
}