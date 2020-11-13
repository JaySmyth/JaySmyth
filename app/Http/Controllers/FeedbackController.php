<?php

namespace App\Http\Controllers;

use App\Mail\SendFeedback;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

class FeedbackController extends Controller
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
     * Handle the incoming request.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'smiley'   => 'required|in:frown,meh,smile',
            'comments' => 'required|string|min:20|max:200'
        ]);

        Mail::to('feedback@antrim.ifsgroup.com')->bcc('it@antrim.ifsgroup.com')->queue(new SendFeedback($request->user(), $request->input('smiley'), $request->input('comments')));
    }
}
