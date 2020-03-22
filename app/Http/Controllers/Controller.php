<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\Common\Chap;
use App\Http\Controllers\Common\Media;
use App\Http\Controllers\Common\Taxonomy;
use App\Http\Controllers\Common\User;
use App\Http\Controllers\Common\Post;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use Chap, Media, Taxonomy, User, Post;

    public function __construct(){
        $this->getAllOptions();
    }

    private function getAllOptions(){
        $options = $this->get_options();
        // set to global config
        config(['db_config' => $options]);
    }
}
