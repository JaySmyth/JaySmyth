<?php

namespace App\Http\Controllers;

use App\Http\Requests\MailReportRecipientRequest;
use App\Models\MailReport;
use Illuminate\Http\Request;

class MailReportsController extends Controller
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
     * List mail reports.
     *
     * @param Request $request
     * @return type
     */
    public function index()
    {
        $this->authorize('view', new MailReport);

        $mailReports = MailReport::orderBy('name', 'ASC')->paginate(50);

        return view('mail_reports.index', compact('mailReports'));
    }

    /**
     * Displays a user record.
     *
     * @param
     * @return
     */
    public function show($id)
    {
        $mailReport = MailReport::findOrFail($id);

        $this->authorize($mailReport);

        return view('mail_reports.show', compact('mailReport'));
    }

    /**
     * Displays add recipient form.
     *
     * @param int $id
     * @return
     */
    public function addRecipient($id)
    {
        $mailReport = MailReport::findOrFail($id);

        $this->authorize($mailReport);

        return view('mail_reports.add_recipient', compact('mailReport'));
    }

    /**
     * Store recipient.
     *
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function storeRecipient(MailReportRecipientRequest $request, $id)
    {
        $mailReport = MailReport::findOrFail($id);

        $mailReport->recipients()->create($request->all());

        flash()->success('Recipient created!', 'Recipient added to mail report.');

        return back();
    }

    /**
     * Displays edit recipient form.
     *
     * @param
     * @return
     */
    public function editRecipient($id, $recipientId)
    {
        $mailReport = MailReport::findOrFail($id);

        $recipient = \App\Models\MailReportRecipient::findOrFail($recipientId);

        return view('mail_reports.edit_recipient', compact('mailReport', 'recipient'));
    }

    /**
     * Updates an existing recipient.
     *
     * @param
     * @return
     */
    public function updateRecipient(MailReportRecipientRequest $request, $id, $recipientId)
    {
        $mailReport = MailReport::findOrFail($id);

        $recipient = \App\Models\MailReportRecipient::findOrFail($recipientId);

        $recipient->update($request->all());

        flash()->success('Updated!', 'Recipient updated successfully.');

        return redirect('mail-reports/'.$mailReport->id);
    }
}
