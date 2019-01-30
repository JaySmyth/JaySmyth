<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Mail;

class HelpController extends Controller
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
     * 
     * @return type
     */
    public function index()
    {
        return view('help.index');
    }

    /**
     * 
     * @return type
     */
    public function feedback()
    {
        return view('help.feedback');
    }

    /**
     * 
     * @return type
     */
    public function sendFeedback(Request $request)
    {
        $this->validate($request, [
            'question_1' => 'required',
            'question_2' => 'required',
            'question_3' => 'required',
            'question_4' => 'required',
            'question_5' => 'required',
            'question_6' => 'required',
            'question_7' => 'required',
            'question_8' => 'required',
            'question_9' => 'required',
            'question_10' => 'required',
        ]);

        Mail::to('feedback@antrim.ifsgroup.com')->bcc('it@antrim.ifsgroup.com')->queue(new \App\Mail\Feedback($request->user(), $request->all()));

        flash()->success('Feedback sent!', 'Thank you for your time.');

        return redirect('/');
    }

}
