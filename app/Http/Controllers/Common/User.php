<?php
namespace App\Http\Controllers\Common;
use DB;

trait User{
	protected function get_user_filter_count(){
		$data = [
            'all' => DB::table('users')->where('user_status', '!=', 2)->count(),
            'actived' => DB::table('users')->where('user_status', '!=', 2)->where('email_verified_at', '<>', null)->count(),
            'not-actived' => DB::table('users')->where('user_status', '!=', 2)->where('email_verified_at', null)->count(),
            'banned' => DB::table('users')->where('user_status', 1)->count(),
            'trash' => DB::table('users')->where('user_status', 2)->count()
        ];
        return $data;
	}

	protected function get_admin_all_users($wheres, $count = 20){
		$data = DB::table('users')->where($wheres)->orderBy('updated_at', 'DESC')->paginate($count);
		if(!empty($data)){
            foreach($data as $key => $value){
                $user_metas = DB::table('usermeta')->where('user_id', $value->id)->get();
                if(!empty($user_metas)){
                    foreach($user_metas as $user_meta){
                        $data[$key]->{$user_meta->umeta_key} = $user_meta->utmeta_value;
                    }
                }
            }
        }
        return $data;
	}

	protected function get_all_users($order_by = 'name'){
		$data = DB::table('users')->orderBy($order_by)->get();
		if(count($data) > 0){
			for($i = 0; $i < count($data); $i++){
				$metas = DB::table('usermeta')->where('user_id', $data[$i]->id)->get();
				if(count($metas) > 0){
					for($j = 0; $j < count($metas); $j++){
						$data[$i]->{$metas[$j]->umeta_key} = $metas[$j]->umeta_value;
					}
				}
			}
		}
		return $data;
	}

	protected function get_user_by_id($user_id){
		$data = DB::table('users')->where('id', $user_id)->first();
		if(count($data) > 0){
			$umetas = DB::table('usermeta')->where('user_id', $user_id)->get();
			if(count($umetas) > 0){
				foreach($umetas as $umeta){
					$data->{$umeta->umeta_key} = $umeta->umeta_value;
				}
			}
		}
		return $data;
	}

	protected function get_roles(){
		$data = DB::table('roles')->orderBy('role_name')->select('role_id', 'role_name', 'roles.role_description')->get();
		return $data;
	}

	protected function update_user($user_id, $user){
		DB::table('users')->where('id', $user_id)->update($user);
	}

	protected function add_new_user($user){
		$user_id = DB::table('users')->insertGetId($user);
		return $user_id;
	}

	protected function check_online_user_session($online_user_session){
		$check = DB::table('online_users')->where('online_user_session', $online_user_session)->select('online_user_id')->first();
		if(count($check) > 0){
			return $check->online_user_id;
		}else{
			return false;
		}
	}

	protected function store_online_user($online_user){
		if($this->check_online_user_session($online_user['online_user_session']) == false){
			DB::table('online_users')->insert($online_user);
		}else{
			DB::table('online_users')->where('online_user_session', $online_user['online_user_session'])->increment('refresh_count');
			DB::table('online_users')->where('online_user_session', $online_user['online_user_session'])->update([
				'online_user_ip' => $online_user['online_user_ip'],
				'online_user_referer' => $online_user['online_user_referer'],
				'updated_at' => date('Y-m-d H:i:s')
			]);
		}
	}

	protected function search_users($search_text, $count = 20){
		$data = DB::table('users')->where('name', 'LIKE', '%' . $search_text . '%')->orWhere('email', 'LIKE', '%' . $search_text . '%')->orWhere('phone', 'LIKE', '%' . $search_text . '%')->orWhere('roles', 'LIKE', '%' . $search_text . '%')->orWhere('created_at', 'LIKE', '%' . $search_text . '%')->orderBy('updated_at', 'DESC')->paginate($count);
		if(!empty($data)){
            foreach($data as $key => $value){
            	$data[$key]->post_count = DB::table('posts')->where('post_author', $value->id)->count();
                $user_metas = DB::table('usermeta')->where('user_id', $value->id)->get();
                if(!empty($user_metas)){
                    foreach($user_metas as $user_meta){
                        $data[$key]->{$user_meta->umeta_key} = $user_meta->utmeta_value;
                    }
                }
            }
        }
        return $data;
	}
}