<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceMessageRequest;
use App\Models\ServiceMessage;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ServiceMessagesController extends Controller
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
     * List service_messages.
     *
     * @return type
     */
    public function index()
    {
        $this->authorize(new ServiceMessage);

        $messages = ServiceMessage::orderBy('title')->paginate(50);

        return view('service_messages.index', compact('messages'));
    }

    /**
     * Show message details.
     *
     * @param type $id
     * @return type
     */
    public function show(Request $request, $id)
    {
        $message = ServiceMessage::findOrFail($id);

        $this->authorize($message);

        // Ajax response
        if ($request->ajax()) {
            $user = Auth::User();
            if ($message->sticky || (! $message->sticky && $message->users()->where('user_id', $user->id)->count() == 0)) {
                if ($message->ifs_only && !$user->hasIfsRole()) {
                    return;
                }

                // Insert a record to indicate that the message has been view by the user
                return $user;
                $message->users()->syncWithoutDetaching($user->id);

                return $message;
            }
        } else {

            // Non Ajax response
            flash()->info($message->title, $message->message, true, true);

            return view('service_messages.show', compact('message'));
        }
    }

    /**
     * Display create form.
     *
     * @param
     * @return
     */
    public function create()
    {
        $this->authorize('viewAny', new ServiceMessage);

        return view('service_messages.create');
    }

    /**
     * Save message record.
     *
     * @param
     * @return
     */
    public function store(ServiceMessageRequest $request)
    {
        $this->authorize(new Message);

        $message = ServiceMessage::create([
            'service_id' => $request->service_id,
            'title' => $request->title,
            'message' => $request->message,
            'valid_from' => Carbon::parse($request->valid_from),
            'valid_to' => Carbon::parse($request->valid_to),
            'sticky' => $request->sticky,
            'ifs_only' => $request->ifs_only,
            'enabled' => $request->enabled,
        ]);

        flash()->success('Created!', 'Message created successfully.');

        return redirect('service-messages');
    }

    /**
     * Display edit message form.
     *
     * @param
     * @return
     */
    public function edit($id)
    {
        $message = ServiceMessage::findOrFail($id);

        $this->authorize($message);

        flash()->warning('Message Editing', 'Edited messages will be redisplayed to users that have viewed them previously.', true);

        return view('service_messages.edit', compact('message'));
    }

    /**
     * Update message record.
     *
     * @param
     * @return
     */
    public function update(ServiceMessageRequest $request, $id)
    {
        $message = ServiceMessage::findOrFail($id);

        $this->authorize('viewAny', $message);

        $message->update([
            'service_id' => $request->service_id,
            'title' => $request->title,
            'message' => $request->message,
            'valid_from' => Carbon::parse($request->valid_from),
            'valid_to' => Carbon::parse($request->valid_to),
            'sticky' => $request->sticky,
            'ifs_only' => $request->ifs_only,
            'enabled' => $request->enabled,
        ]);

        // Remove any user views after update
        $message->users()->detach();

        flash()->success('Updated!', 'Message updated successfully.');

        return redirect('service-messages');
    }
}
