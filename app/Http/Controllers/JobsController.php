<?php

namespace App\Http\Controllers;

use App\Http\Resources\FailedJobCollection;
use App\Http\Resources\FailedJobResource;
use App\Http\Resources\JobCollection;
use App\Http\Resources\JobResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('ifsAdmin');
    }

    /**
     * List jobs.
     *
     * @param Request $request
     * @return type
     */
    public function index()
    {
        return view('jobs.index');
    }

    /**
     * Retry job.
     *
     * @param Request $request
     * @return type
     */
    public function retryJob(Request $request)
    {
        if ($request->ajax()) {
            $job = DB::table('failed_jobs')->where('id', $request->id)->first();

            if ($job) {
                exec("cd /var/www/ifs; php artisan queue:retry $request->id", $output);

                return response()->json($output);
            }
        }
    }

    /**
     * Retry all.
     *
     * @param Request $request
     * @return type
     */
    public function retryAll(Request $request)
    {
        if ($request->ajax()) {
            $jobs = DB::table('failed_jobs')->get();

            if ($jobs->count() > 0) {
                exec('cd /var/www/ifs; php artisan queue:retry all', $output);

                return response()->json($output);
            }
        }
    }

    /**
     * Get jobs.
     *
     * @return json
     */
    public function getJobs()
    {
        if (request()->wantsJson()) {
            return new JobCollection(JobResource::collection(DB::table('jobs')->get()));
        }
    }

    /**
     * Get failed jobs.
     *
     * @return json
     */
    public function getFailedJobs()
    {
        if (request()->wantsJson()) {
            return new FailedJobCollection(FailedJobResource::collection(DB::table('failed_jobs')->get()));
        }
    }

    /**
     * List jobs.
     *
     * @param Request $request
     * @return type
     */
    public function processes()
    {
        return view('processes.index');
    }

    /**
     * Get running php processes.
     *
     * @return json
     */
    public function getProcesses()
    {
        if (request()->wantsJson()) {
            exec('ps -ef | grep php', $output);

            foreach ($output as $key => $value):
                if (stristr($value, 'grep php')) {
                    unset($output[$key]);
                }
            endforeach;

            return response()->json($output);
        }
    }
}
