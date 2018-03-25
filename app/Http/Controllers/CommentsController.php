<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comments;


class CommentsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Comment's index page
     *
     * @param Request $request
     * @param Uuid $pid
     * @return json
     */
    public function index(Request $request, $pid)
    {
        $response = ['success' => false];

        if ($request->ajax()) {

            try {

                $comments = Comments::get_comments($pid);

                $html = view('partials.Comments._list', compact('comments'))->render();

                $response = ['success' => true, 'html' => $html];

            } catch (Exception $e) {
                throw $e;
            }
        }

        return response()->json($response);
    }
}
