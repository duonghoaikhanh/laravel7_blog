<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Auth;
use Session;
use DB;

class UserController extends Controller
{
    public function __construct(){

    }

    function index($filter = ''){
        $data['filter_count'] = $this->get_user_filter_count();
        if($filter == ''){
            $data['users'] = $this->get_admin_all_users([['user_status', '!=', 2]]);
        }else if($filter == 'actived'){
            $data['users'] = $this->get_admin_all_users([['user_status', '!=', 2], ['email_verified_at', '<>', null]]);
        }else if($filter == 'not-actived'){
            $data['users'] = $this->get_admin_all_users([['user_status', '!=', 2], ['email_verified_at', '=', null]]);
        }else if($filter == 'banned'){
            $data['users'] = $this->get_admin_all_users([['user_status', '=', 1]]);
        }else if($filter == 'trash'){
            $data['users'] = $this->get_admin_all_users([['user_status', '=', 2]]);
        }else{
            abort('404');
        }
        return view('admin.users.index', $data);
    }

    function getProfile(){
        $data['title'] = 'Hồ sơ cá nhân';
        $user = $this->get_user_by_id(Auth::user()->id);
        $data['recent_logins'] = DB::table('recent_logins')->where('user_id', $user->id)->orderBy('id', 'DESC')->limit(30)->get();
        $data['user'] = $user;
    	return view('admin.users.profile', $data);
    }

    function postProfile(Request $request){
    	$rules = [
    		'name' => 'required|max:100',
    		'email' => ['required', 'max:100', Rule::unique('users')->ignore(Auth::user()->id)],
    		'phone' => 'max:50'
    	];
    	$messages = [
    		'name.required' => 'Please do not leave the blank information.',
    		'name.max' => 'Maximum 100 characters.',
    		'email.required' => 'Please do not leave the blank information.',
    		'email.max' => 'Maximum 100 characters.',
    		'email.unique' => 'Email already exists.',
    		'phone.max' => 'Maximum 100 characters.'
    	];
    	$this->validate($request, $rules, $messages);
    	$user_status = $request->user_status == 1 ? 1 : 0;
    	$email_verified_at = $request->email_verified_at == 1 ? date('Y-m-d H:i:s') : null;
        if(empty($request->roles)){
            $roles = json_encode(['user']);
        }else{
            $roles = json_encode($request->roles);
        }
    	$user = [
    		'name' => $request->name,
    		'email' => $request->email,
    		'phone' => $request->phone,
    		'address' => $request->address,
            'province' => $request->province,
            'company' => $request->company,
    		'avatar' => $request->avatar,
    		'description' => $request->description,
            'roles' => $roles,
    		'user_status' => $user_status,
    		'email_verified_at' => $email_verified_at,
    		'updated_at' => date('Y-m-d H:i:s'),
            'agency' => (int) $request->agency
    	];
    	$this->update_user(Auth::user()->id, $user);
        if(strlen(trim($request->password)) > 0){
        	if(strlen(trim($request->password)) >= 6){
        		$this->update_user(Auth::user()->id, ['password' => bcrypt(trim($request->password))]);
        	}else{
        		Session::flash('notify_type', 'error');
            	Session::flash('notify_content', 'Passwords must be at least 6 characters.');
            	return redirect()->back();
        	}
        }
    	Session::flash('notify_type', 'success');
        Session::flash('notify_content', 'Updated.');
    	return redirect()->back();
    }

    function getEdit($user_id){
        $data['active_url'] = url('/admin/users');
        $data['title'] = 'Thông tin người dùng';
        if(Auth::user()->id == $user_id){
            return redirect('/admin/users/action/profile');
        }else{
            $user = $this->get_user_by_id($user_id);
            $data['recent_logins'] = DB::table('recent_logins')->where('user_id', $user->id)->orderBy('id', 'DESC')->limit(30)->get();
            $data['user'] = $user;
            return view('admin.users.profile', $data);
        }
    }

    function postEdit(Request $request, $user_id){
        $rules = [
            'name' => 'required|max:100',
            'email' => ['required', 'max:100', Rule::unique('users')->ignore($user_id)],
            'phone' => 'max:50'
        ];
        $messages = [
            'name.required' => 'Please do not leave the blank information.',
            'name.max' => 'Maximum 100 characters.',
            'email.required' => 'Please do not leave the blank information.',
            'email.max' => 'Maximum 100 characters.',
            'email.unique' => 'Email already exists.',
            'phone.max' => 'Maximum 100 characters.'
        ];
        $this->validate($request, $rules, $messages);
        $user_status = $request->user_status == 1 ? 1 : 0;
        $email_verified_at = $request->email_verified_at == 1 ? date('Y-m-d H:i:s') : null;
        if(empty($request->roles)){
            $roles = json_encode(['user']);
        }else{
            $roles = json_encode($request->roles);
        }
        $user = [
            'name' => $request->name,
            'email' => $request->email,
            'email_verified_at' => $email_verified_at,
            'phone' => $request->phone,
            'address' => $request->address,
            'province' => $request->province,
            'avatar' => $request->avatar,
            'description' => $request->description,
            'roles' => $roles,
            'user_status' => $user_status,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $this->update_user($user_id, $user);
        if(strlen(trim($request->password)) > 0){
            if(strlen(trim($request->password)) >= 6){
                $this->update_user($user_id, ['password' => bcrypt(trim($request->password))]);
            }else{
                Session::flash('notify_type', 'error');
                Session::flash('notify_content', 'Passwords must be at least 6 characters.');
                return redirect()->back();
            }
        }
        Session::flash('notify_type', 'success');
        Session::flash('notify_content', 'Updated.');
        return redirect()->back();
    }

    function getAdd(){
        return view('admin.users.add');
    }

    function postAdd(Request $request){
        $rules = [
            'name' => 'required|max:100',
            'email' => 'required|max:100|unique:users',
            'phone' => 'max:50',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6'
        ];
        $messages = [
            'name.required' => 'Please do not leave the blank information.',
            'name.max' => 'Maximum 100 characters.',
            'email.required' => 'Please do not leave the blank information.',
            'email.max' => 'Maximum 100 characters.',
            'email.unique' => 'Email already exists.',
            'phone.max' => 'Maximum 100 characters.',
            'password.required' => 'Please do not leave the blank information.',
            'password.min' => 'Passwords must be at least 6 characters.',
            'password.confirmed' => 'Password confirmation does not match',
            'password.required' => 'Please do not leave the blank information.',
            'password.min' => 'Confirm Passwords must be at least 6 characters.'
        ];
        $this->validate($request, $rules, $messages);
        $user_status = $request->user_status == 1 ? 1 : 0;
        $email_verified_at = $request->email_verified_at == 1 ? date('Y-m-d H:i:s') : null;
        if(empty($request->roles)){
            $roles = json_encode(['user']);
        }else{
            $roles = json_encode($request->roles);
        }
        $date = date('Y-m-d H:i:s');
        $user = [
            'name' => $request->name,
            'email' => $request->email,
            'email_verified_at' => $email_verified_at,
            'password' => bcrypt(trim($request->password)),
            'phone' => $request->phone,
            'address' => $request->address,
            'avatar' => $request->avatar,
            'description' => $request->description,
            'roles' => $roles,
            'user_status' => $user_status,
            'created_at' => $date,
            'updated_at' => $date
        ];
        $user_id = $this->add_new_user($user);
        Session::flash('notify_type', 'success');
        Session::flash('notify_content', 'Successfully.');
        return redirect('/admin/users/edit/' . $user_id);
    }

    function getBan($user_id){
        $user = ['user_status' => 1];
        $this->update_user($user_id, $user);
        Session::flash('notify_type', 'success');
        Session::flash('notify_content', 'Updated.');
        return redirect()->back();
    }

    function getTrash($user_id){
        $user = ['user_status' => 2];
        $this->update_user($user_id, $user);
        Session::flash('notify_type', 'success');
        Session::flash('notify_content', 'Updated.');
        return redirect()->back();
    }

    function getRestore($user_id){
        $user = ['user_status' => 0];
        $this->update_user($user_id, $user);
        Session::flash('notify_type', 'success');
        Session::flash('notify_content', 'Updated.');
        return redirect()->back();
    }

    function getUnblock($user_id){
        $user = ['user_status' => 0];
        $this->update_user($user_id, $user);
        Session::flash('notify_type', 'success');
        Session::flash('notify_content', 'Updated.');
        return redirect()->back();
    }

    function postSearch(Request $request){
        $search_text = $request->search_text;
        $data['users'] = $this->search_users($search_text);
        return (string)view('admin.users._item', $data)->render();
    }

    function orderHistory($user_id){
        $data['active_url'] = url('/admin/users');
        $data['title'] = 'Lịch sử giao hàng';
        $data['user'] = Db::table('users')->where('id', $user_id)->first();
        return view('admin.users.order_history', $data);
    }
}
