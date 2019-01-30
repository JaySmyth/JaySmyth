<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Message;
use App\Http\Requests\MessageRequest;
use Carbon\Carbon;
use Auth;

class MessagesController extends Controller
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
     * List messages.
     *
     * @return type
     */
    public function index()
    {
        $this->authorize(new Message);

        $messages = Message::orderBy('title')->paginate(50);

        return view('messages.index', compact('messages'));
    }

    /**
     * Show message details.
     *
     * @param type $id
     * @return type
     */
    public function show($id)
    {
        $message = Message::findOrFail($id);

        $this->authorize($message);

        flash()->info($message->title, $message->message, true, true);

        return view('messages.show', compact('message'));
    }

    /**
     * Display create diver form.
     *
     * @param
     * @return
     */
    public function create()
    {
        $this->authorize('index', new Message);
        return view('messages.create');
    }

    /**
     * Save message record.
     *
     * @param
     * @return
     */
    public function store(MessageRequest $request)
    {
        $this->authorize(new Message);

        $message = Message::create([
                    'title' => $request->title,
                    'message' => $request->message,
                    'valid_from' => Carbon::parse($request->valid_from),
                    'valid_to' => Carbon::parse($request->valid_to),
                    'sticky' => $request->sticky,
                    'ifs_only' => $request->ifs_only,
                    'enabled' => $request->enabled
        ]);

        $message->depots()->sync($request->depots);
        $message->companies()->sync($request->companies);

        flash()->success('Created!', 'Message created successfully.');

        return redirect('messages');
    }

    /**
     * Display edit message form.
     *
     * @param
     * @return
     */
    public function edit($id)
    {
        $message = Message::findOrFail($id);

        $this->authorize($message);

        flash()->warning('Message Editing', 'Edited messages will be redisplayed to users that have viewed them previously.', true);

        return view('messages.edit', compact('message'));
    }

    /**
     * Update message record.
     *
     * @param
     * @return
     */
    public function update(MessageRequest $request, $id)
    {
        $message = Message::findOrFail($id);

        $this->authorize('index', $message);

        $message->update([
            'title' => $request->title,
            'message' => $request->message,
            'valid_from' => Carbon::parse($request->valid_from),
            'valid_to' => Carbon::parse($request->valid_to),
            'sticky' => $request->sticky,
            'ifs_only' => $request->ifs_only,
            'enabled' => $request->enabled
        ]);

        $message->depots()->sync($request->depots);
        $message->companies()->sync($request->companies);

        // Remove any user views after update
        $message->users()->detach();

        flash()->success('Updated!', 'Message updated successfully.');

        return redirect('messages');
    }

}
