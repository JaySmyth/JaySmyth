<?php

namespace App\Http\Controllers;

use App\Models\Models\Log;
use Illuminate\Http\Request;

class LogsController extends Controller
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
     * List all log entries.
     *
     * @param Request $request
     * @return type
     */
    public function index(Request $request)
    {
        $this->authorize(new \App\Models\Models\Log);

        $logs = $this->search($request);

        return view('logs.index', compact('logs'));
    }

    /**
     * Get data.
     *
     * @param \App\Http\Controllers\Log $log
     * @return type
     */
    public function getData(Log $log)
    {
        if (request()->wantsJson()) {
            $this->authorize($log);

            $array = json_decode($log->data, true);

            $formatted = [];

            foreach ($array as $key => $value) {
                $formatted[snakeCaseToWords($key)] = $value;
            }

            return response()->json(json_encode($formatted));
        }
    }

    /*
     * Log search.
     *
     * @param   $request
     * @param   $paginate
     *
     * @return
     */

    private function search($request, $paginate = true)
    {
        $query = Log::orderBy('id', 'DESC')
                ->filter($request->filter)
                ->dateBetween($request->date_from, $request->date_to)
                ->hasInformation($request->information)
                ->hasComments($request->comments);

        if (! $paginate) {
            return $query->get();
        }

        return $query->paginate(50);
    }
}
