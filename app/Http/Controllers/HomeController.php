<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
    	// Get list category post
		$post_categories = $this->get_terms('post-category', 0, 20);
		//get products list
		$posts = $this->getPostFromCategoryId($post_categories[0]->term_taxonomy_id);

		$data = [
			'post_categories' => $post_categories,
			'posts'           => $posts
		];

        return view('home', $data);
    }

	/**
	 * getProductFromCategoryId
	 * @param $category_id
	 * @return mixed
	 */
	private function getPostFromCategoryId($category_id)
	{
		// get product ids from category id
		$post_ids = DB::table('term_relationships')->select('object_id')->where('term_taxonomy_id', $category_id)->pluck('object_id')->toArray();
		$post_ids = empty($post_ids) ? [] : $post_ids;
		$posts = $this->get_all_posts('post', [], 6, ['post_id', $post_ids]);

		return $posts;
	}

	public function postDetail($post_slug) {
		$post = $this->get_post_by_slug($post_slug, 'post');
		$data = [
			'post' => $post
		];

		return view('detail_post', $data);
	}
}
