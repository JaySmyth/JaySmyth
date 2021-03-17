<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Log;
use App\Models\Manifest;
use App\Models\Report;
use App\Models\Service;
use App\Models\Shipment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
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
     * List the available reports.
     *
     * @param Request $request
     * @return type
     */
    public function index(Request $request)
    {
        $this->authorize(new Report);

        $reports = Report::orderBy('name', 'ASC')
            ->whereIn('mode_id', $request->user()->getAllowedModeIds())
            ->whereIn('depot_id', $request->user()->getDepotIds())
            ->with('depot', 'mode')
            ->paginate(100);

        return view('reports.index', compact('reports'));
    }

    /**
     * Customs report for FedEx international shipments.
     *
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function fedexCustoms(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        $this->authorize(new Report);

        if ($request->manifest_id) {
            $manifest = Manifest::findOrFail($request->manifest_id);
        } else {
            $manifest = null;
        }

        // decode the criteria
        $criteria = json_decode($report->criteria, true);

        // Load for manifests drop down
        $manifests = Manifest::whereIn('manifest_profile_id', [1, 2])->whereDepotId($report->depot_id)->whereCarrierId($criteria['carrier_id'])->orderBy('id', 'DESC')->limit(250)->get();

        $dropdown = [];

        foreach ($manifests as $m) {
            $dropdown[$m->id] = $m->number . ' (' . $m->created_at->timezone($request->user()->time_zone)->format($request->user()->date_format) . ')';
        }

        $parisShipments = $this->getCustomsReportShipments($report, $request, 'fedexRouteParis');
        $memphisShipments = $this->getCustomsReportShipments($report, $request, 'fedexRouteMemphis');
        $range = number_format($criteria['customs_value_low'], 2) . 'GBP - ' . number_format($criteria['customs_value_high'], 2) . 'GBP';

        return view('reports.customs', compact('manifest', 'dropdown', 'report', 'parisShipments', 'memphisShipments', 'range'));
    }

    /**
     * Load the shipments for the FedEx customs report.
     *
     * @param type $report
     * @param type $request
     * @param type $fedexRoute
     * @return type
     */
    private function getCustomsReportShipments($report, $request, $fedexRoute)
    {
        // decode the criteria
        $criteria = json_decode($report->criteria, true);

        $shipments = Shipment::select(DB::raw('round(customs_value / currencies.rate, 2) as customs_value_gbp, shipments.*'))
            ->join('currencies', 'shipments.customs_value_currency_code', '=', 'currencies.code')
            ->orderBy('pieces')
            ->orderBy('sender_company_name')
            ->orderBy('sender_name');

        if (is_numeric($request->manifest_id)) {
            $shipments->hasManifest($request->manifest_id);
        } else {
            $shipments->availableForManifesting()->where('ship_date', '>=', Carbon::today()->startOfDay());
        }

        $shipments->hasMode($report->mode_id)
            ->hasDepot($report->depot_id)
            ->hasCarrier($criteria['carrier_id'])
            ->hasServiceIn($criteria['services'])
            ->having('customs_value_gbp', '>=', $criteria['customs_value_low'])
            ->having('customs_value_gbp', '<', $criteria['customs_value_high'])
            ->notEu()
            ->where('bill_shipping', 'sender')
            ->$fedexRoute();

        return $shipments->get();
    }

    /**
     * Shippers report.
     *
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function shippers(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        $this->authorize(new Report);

        $dateFrom = new Carbon($request->date_from);
        $dateTo = new Carbon($request->date_to);

        $shipments = Shipment::orderBy('company_name')
            ->traffic($request->traffic)
            ->hasCarrier($request->carrier)
            ->hasDepot($request->depot)
            ->hasService($request->service)
            ->shipDateBetween($dateFrom, $dateTo)
            ->whereNotIn('status_id', [1, 7])
            ->restrictCompany($request->user()->getAllowedCompanyIds())
            ->join('companies', 'companies.id', '=', 'shipments.company_id')
            ->groupBy('company_id')
            ->select(DB::raw('count(*) as total, sum(pieces) as total_pieces, sum(weight) as total_weight, sum(volumetric_weight) as total_volumetric_weight, shipments.*'))
            ->with('company');

        // Restrict salespersons to their allocated companies
        if ($request->user()->hasRole('ifss')) {
            $saleId = \App\Models\Sale::whereName($request->user()->name)->firstOrFail()->id;
            $shipments = $shipments->whereSaleId($saleId);
        }

        $shipments = $shipments->paginate(2500);

        return view('reports.shippers', compact('report', 'shipments'));
    }

    /**
     * Non shippers report.
     *
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function nonShippers(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        $this->authorize(new Report);

        $dateFrom = new Carbon($request->date_from);
        $dateTo = new Carbon($request->date_to);

        $shippers = Shipment::select('company_id')
            ->hasDepot($request->depot)
            ->shipDateBetween($dateFrom, $dateTo)
            ->whereNotIn('status_id', [1, 7])
            ->groupBy('company_id')
            ->pluck('company_id');

        $companies = Company::orderBy('company_name')
            ->whereNotIn('id', $shippers)
            ->whereNotIn('depot_id', [4])
            ->hasDepot($request->depot)
            ->hasSalesperson($request->salesperson)
            ->whereEnabled(1)
            ->whereTesting(0)
            ->with('sale', 'depot');

        // Restrict salespersons to their allocated companies
        if ($request->user()->hasRole('ifss')) {
            $saleId = \App\Models\Sale::whereName($request->user()->name)->firstOrFail()->id;
            $companies = $companies->whereSaleId($saleId);
        }

        $companies = $companies->paginate(2500);

        return view('reports.non_shippers', compact('report', 'companies'));
    }

    /**
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function scanning(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        $this->authorize(new Report);

        if (! $request->date) {
            $date = Carbon::today();
        } else {
            $date = new Carbon($request->date);
        }

        $packages = \App\Models\Package::orderBy('shipments.sender_company_name')
            ->orderBy('packages.id')
            ->select('packages.*')
            ->join('shipments', 'packages.shipment_id', '=', 'shipments.id')
            ->whereBetween('ship_date', [Carbon::parse($date)->startOfDay(), Carbon::parse($date)->endOfDay()])
            ->where('shipments.depot_id', 1)
            ->whereNotIn('shipments.status_id', [1, 7])
            ->whereNotIn('shipments.service_id', [7, 18, 20, 39, 44, 45, 48, 50])
            ->where('sender_postcode', 'LIKE', 'BT%')
            ->with('shipment', 'shipment.route', 'shipment.service', 'shipment.company', 'shipment.depot');

        if ($request->company) {
            $packages->where('shipments.company_id', $request->company);
        }

        if (strlen($request->received) > 0) {
            $packages->where('packages.received', $request->received);
        }

        if (strlen($request->routed) > 0) {
            $packages->where('packages.loaded', $request->routed);
        }

        $packages = $packages->get();

        $routes = [];
        $totals = ['expected' => $packages->count(), 'collected' => 0, 'received' => 0, 'loaded' => 0];
        $percentages = ['collected' => 0, 'received' => 0, 'loaded' => 0];

        foreach ($packages as $package) {
            $routes[$package->route]['packages'][] = $package;
            inc($routes[$package->route]['collected'], $package->collected);
            inc($routes[$package->route]['received'], $package->true_receipt_scan);
            inc($routes[$package->route]['loaded'], $package->loaded);
        }

        foreach ($routes as $route) {
            $totals['collected'] += $route['collected'];
            $totals['received'] += $route['received'];
            $totals['loaded'] += $route['loaded'];
        }

        ksort($routes);

        foreach ($percentages as $key => $value) {
            if ($totals['expected'] > 0 && $totals[$key] > 0) {
                $percentages[$key] = round((100 / $totals['expected']) * $totals[$key], 1);
            }
        }

        return view('reports.scanning', compact('report', 'routes', 'totals', 'percentages'));
    }

    /**
     * Dim report.
     *
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function dims(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        $this->authorize(new Report);

        $dateFrom = new Carbon($request->date_from);
        $dateTo = new Carbon($request->date_to);

        $shipments = Shipment::select('shipments.*')
            ->orderBy('created_at', 'DESC')
            ->orderBy('shipments.id', 'DESC')
            ->traffic($request->traffic)
            ->hasCarrier($request->carrier)
            ->hasDepot($request->depot)
            ->hasCompany($request->company)
            ->hasService($request->service)
            ->hasStatus($request->status)
            ->shipDateBetween($dateFrom, $dateTo)
            ->whereNotIn('status_id', [1])
            ->paginate(250);

        return view('reports.dims', compact('report', 'shipments'));
    }

    /**
     * Performance report.
     *
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function performance(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        $this->authorize(new Report);

        $dateFrom = new Carbon($request->date_from);
        $dateTo = new Carbon($request->date_to);
        $services = [];
        if (isset($request->service) && $request->service > '') {
            $services = \App\Models\Service::where('code', $request->service)->pluck('id');
        }
        $total = 0;
        $results = [];

        Shipment::select('shipments.*')
            ->orderBy('ship_date')
            ->hasCompany($request->company)
            ->hasCarrier($request->carrier)
            ->hasServiceIn($services)
            ->shipDateBetween($dateFrom, $dateTo)
            ->whereDelivered(1)
            ->chunk(500, function ($shipments) use (&$total, &$results) {
                foreach ($shipments as $shipment) {
                    $total++;
                    if ($shipment->delay == '') {
                        $shipment->getDelay();
                        $shipment->save();
                    }
                    $results[$shipment->delay][] = $shipment;
                }
            });

        foreach (array_keys($results) as $delay) {
            $results['percentages'][$delay] = round((100 / $total) * count($results[$delay]), 1) . '%';
        }

        return view('reports.performance', compact('report', 'results', 'total'));
    }

    /**
     * Active shipments report.
     *
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function activeShipments(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        $this->authorize(new Report);

        $shipments = Shipment::orderBy('ship_date', 'ASC')->hasMode($report->mode_id)->isActive()->restrictCompany($request->user()->getAllowedCompanyIds())->with('service', 'status', 'company', 'carrier')->get();

        $services = Service::whereIn('id', $shipments->pluck('service_id')->unique())->pluck('name', 'id')->toArray();

        $shipmentsByService = [];

        // Loop through the shipments, group them by service and total
        foreach ($shipments as $shipment) {
            $shipmentsByService[$shipment->service_id]['shipments'][] = $shipment;
            inc($shipmentsByService[$shipment->service_id]['pieces'], $shipment->pieces);
            inc($shipmentsByService[$shipment->service_id]['weight'], $shipment->weight);
        }

        return view('reports.active_shipments', compact('report', 'shipments', 'shipmentsByService', 'services'));
    }

    /**
     * Exceptions report.
     *
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function exceptions(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        $this->authorize(new Report);

        if ($request->status) {
            $status = [$request->status];
        } else {
            $status = [8, 9, 10, 11, 12, 17];
        }

        $shipments = Shipment::orderBy('ship_date', 'DESC')
            ->filter($request->filter)
            ->hasCompany($request->company)
            ->shipDateBetween($request->date_from, $request->date_to)
            ->hasMode($report->mode_id)
            ->where('service_id', '<>', 4)
            ->whereReceived(1)
            ->whereIn('status_id', $status)
            ->traffic($request->traffic)
            ->hasService($request->service)
            ->restrictCompany($request->user()->getAllowedCompanyIds())
            ->with('service')
            ->paginate(250);

        return view('reports.exceptions', compact('report', 'shipments'));
    }

    /**
     * Active shipments report.
     *
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function pod(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        $this->authorize(new Report);

        $shipments = Shipment::orderBy('ship_date', 'DESC')
            ->filter($request->filter)
            ->hasCompany($request->company)
            ->shipDateBetween($request->date_from, $request->date_to)
            ->hasMode($report->mode_id)
            ->whereDelivered(1)
            ->restrictCompany($request->user()->getAllowedCompanyIds())
            ->with('service')
            ->paginate(250);

        return view('reports.pod', compact('report', 'shipments'));
    }

    /**
     * Purchase Invoices - unknown jobs.
     *
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function unknownJobs(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        $this->authorize(new Report);

        $purchaseInvoiceLines = \App\Models\PurchaseInvoiceLine::select('purchase_invoice_lines.*')
            ->where('purchase_invoices.status', 0)
            ->whereNull('scs_job_number')
            ->join('purchase_invoices', 'purchase_invoice_lines.purchase_invoice_id', '=', 'purchase_invoices.id')
            ->orderBy('purchase_invoice_lines.ship_date', 'DESC')
            ->with('charges')
            ->get();

        return view('reports.unknown_jobs', compact('report', 'purchaseInvoiceLines'));
    }

    /**
     * Shippers per day.
     *
     * @param Request $request
     * @param type $id
     */
    public function dailyStats(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        $this->authorize(new Report);

        $date = Carbon::parse('this month');

        if ($request->month) {
            $date = Carbon::parse($request->month);
        }

        $date->startOfMonth();

        $results = [];
        $results['total_weight'] = 0;
        $results['total_volumetric_weight'] = 0;
        $results['total_pieces'] = 0;
        $results['total_shipments'] = 0;
        $results['total_shippers'] = 0;
        $totalDays = 0;

        for ($i = 0; $i < $date->daysInMonth; $i++) {
            $shipmentsGroupedByCompany = Shipment::orderBy('id')
                ->traffic($request->traffic)
                ->hasCarrier($request->carrier)
                ->hasDepot($request->depot)
                ->hasService($request->service)
                ->hasCompany($request->company)
                ->shipDateBetween($date->startOfDay(), $date->endOfDay())
                ->restrictCompany($request->user()->getAllowedCompanyIds())
                ->whereNotIn('status_id', [1, 7])
                ->groupBy('company_id')
                ->select(DB::raw('count(*) as total_shipments, sum(pieces) as total_pieces, sum(weight) as total_weight, sum(volumetric_weight) as total_volumetric_weight, shipments.*'))
                ->get();

            $results[$i]['date'] = $date->format('l jS F, Y');
            $results[$i]['date_short'] = $date->format('d-m-Y');
            $results[$i]['total_weight'] = $shipmentsGroupedByCompany->sum('total_weight');
            $results[$i]['total_volumetric_weight'] = $shipmentsGroupedByCompany->sum('total_volumetric_weight');
            $results[$i]['total_pieces'] = $shipmentsGroupedByCompany->sum('total_pieces');
            $results[$i]['total_shipments'] = $shipmentsGroupedByCompany->sum('total_shipments');
            $results[$i]['total_shippers'] = $shipmentsGroupedByCompany->count();

            inc($results['total_weight'], $results[$i]['total_weight']);
            inc($results['total_volumetric_weight'], $results[$i]['total_volumetric_weight']);
            inc($results['total_pieces'], $results[$i]['total_pieces']);
            inc($results['total_shipments'], $results[$i]['total_shipments']);

            if ($date->isWeekday() && $date < $date->tomorrow()) {
                inc($results['total_shippers'], $results[$i]['total_shippers']);
                $totalDays++;
            }

            $date->addDay();
        }

        $results['average_shippers_per_day'] = round(($results['total_shippers'] / $totalDays), 0, PHP_ROUND_HALF_DOWN);

        return view('reports.daily_stats', compact('report', 'results'));
    }

    /**
     * User's logged in - displays screen resolution and browser info.
     *
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function userAgents(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        $this->authorize(new Report);

        $users = \App\Models\User::whereEnabled(1)
            ->whereNotNull('browser')
            ->orderBy('last_login', 'DESC')
            ->orderBy('browser')
            ->orderBy('platform')
            ->orderBy('screen_resolution')
            ->get();

        return view('reports.user_agents', compact('report', 'users'));
    }

    /**
     * FedEx international receipts - available for manifesting.
     *
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function fedexInternationalAvailable(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        $this->authorize(new Report);

        $shipments = \App\Models\Shipment::whereCarrierId(2)
            ->availableForManifesting()
            ->isInternational()
            ->whereStatusId(3)
            ->restrictCompany($request->user()->getAllowedCompanyIds())
            ->orderBy('sender_company_name')
            ->orderBy('id')
            ->get();

        $total = $shipments->count();
        $ant = $shipments->where('route_id', 1);
        $bfs = $shipments->where('route_id', 2);

        return view('reports.fedex_international_available', compact('report', 'total', 'ant', 'bfs'));
    }

    /**
     * Margin report.
     *
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function margins(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        $this->authorize(new Report);

        $dateFrom = new Carbon($request->date_from);
        $dateTo = new Carbon($request->date_to);

        $shipments = Shipment::orderBy('sender_company_name')
            ->orderBy('ship_date', 'desc')
            ->traffic($request->traffic)
            ->hasCarrier($request->carrier)
            ->hasDepot($request->depot)
            ->hasService($request->service)
            ->hasCompany($request->company)
            ->shipDateBetween($dateFrom, $dateTo)
            ->whereNotIn('status_id', [1, 7])
            ->paginate(500);

        return view('reports.margins', compact('report', 'shipments'));
    }

    /**
     * Carrier scans report.
     *
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function carrierScans(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        $this->authorize(new Report);

        $tracking = \App\Models\Tracking::orderBy('id', 'desc')
            ->where('tracking.message', 'LIKE', '%(carrier scan)%')
            ->with('shipment', 'shipment.service', 'shipment.company', 'shipment.depot')
            ->paginate(50);

        return view('reports.carrier_scans', compact('report', 'tracking'));
    }

    /**
     * Purchase Invoices - unknown jobs.
     *
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function purchaseInvoiceLines(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        $this->authorize(new Report);

        $purchaseInvoiceLines = \App\Models\PurchaseInvoiceLine::orderBy('purchase_invoice_lines.ship_date', 'DESC')
            ->filter($request->filter)
            ->shipDateBetween($request->date_from, $request->date_to)
            ->invoiceDateBetween($request->invoice_date_from, $request->invoice_date_to)
            ->hasCarrier($request->carrier)
            ->orderBy('purchase_invoice_lines.ship_date', 'DESC')
            ->with('charges')
            ->paginate(2000);

        return view('reports.purchase_invoice_lines', compact('report', 'purchaseInvoiceLines'));
    }

    /**
     * Exceptions report.
     *
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function preTransit(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        $this->authorize(new Report);

        // Default results to "today"
        if (! $request->date_from && ! $request->date_to) {
            $request->date_from = Carbon::today()->startOfDay();
            $request->date_to = Carbon::today()->endOfDay();
        }

        $shipments = Shipment::orderBy('id', 'DESC')
            ->filter($request->filter)
            ->hasCompany($request->company)
            ->shipDateBetween($request->date_from, $request->date_to)
            ->hasMode($report->mode_id)
            ->hasService($request->service)
            ->whereReceived(0)
            ->whereStatusId(2)
            ->restrictCompany($request->user()->getAllowedCompanyIds())
            ->with('service')
            ->paginate(250);

        return view('reports.pre_transit', compact('report', 'shipments'));
    }

    /**
     * Hazardous / dry ice report.
     *
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function hazardous(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        $this->authorize(new Report);

        // Default results to "today"
        if (! $request->date_from && ! $request->date_to) {
            $request->date_from = Carbon::today()->startOfDay();
            $request->date_to = Carbon::today()->endOfDay();
        }

        $shipments = Shipment::orderBy('id', 'DESC')
            ->hasCompany($request->company)
            ->shipDateBetween($request->date_from, $request->date_to)
            ->hasMode($report->mode_id)
            ->hasService($request->service)
            ->whereBillShipping('sender')
            ->where(function ($query) {
                $query->orWhere('hazardous', '>', 0)
                    ->orWhere('hazardous', 'E')
                    ->orWhere('dry_ice_flag', 1);
            })
            ->restrictCompany($request->user()->getAllowedCompanyIds())
            ->with('service')
            ->paginate(250);

        return view('reports.hazardous', compact('report', 'shipments'));
    }

    /**
     * Shippers report.
     *
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function shipmentsByCarrier(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        $this->authorize(new Report);

        $dateFrom = new Carbon($request->date_from);
        $dateTo = new Carbon($request->date_to);

        $shipments = Shipment::orderBy('carriers.name')
            ->traffic($request->traffic)
            ->hasCompany($request->company)
            ->hasDepot($request->depot)
            ->hasService($request->service)
            ->shipDateBetween($dateFrom, $dateTo)
            ->hasStatus('S')
            ->join('carriers', 'carriers.id', '=', 'shipments.carrier_id')
            ->groupBy('shipments.carrier_id')
            ->select(DB::raw('count(*) as total, sum(pieces) as total_pieces, sum(weight) as total_weight, sum(volumetric_weight) as total_volumetric_weight, shipments.*'));

        $shipments = $shipments->paginate(2500);

        return view('reports.carriers', compact('report', 'shipments'));
    }

    /**
     * Collection settings report.
     *
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function collectionSettings(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        $this->authorize(new Report);

        $companies = Company::whereDepotId(1)->whereEnabled(1)->whereTesting(0)->get();

        $companies = $companies->sortBy('postcode', SORT_NATURAL);

        $day = Carbon::now()->dayOfWeekIso;

        foreach ($companies as $company) {
            $settings = $company->getCollectionSettingsForDay($day);

            if ($settings instanceof \App\Models\CollectionSetting) {
                $settings = $settings->toArray();
            }

            if (is_array($settings)) {
                $collectionSettings[] = ['id' => $company->id, 'company_name' => $company->company_name, 'postcode' => $company->postcode] + $settings;
            }
        }

        return view('reports.collection_settings', compact('report', 'collectionSettings'));
    }

    /**
     * Exceptions report.
     *
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function scanningKpis(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        $this->authorize(new Report);

        $date = Carbon::parse('this month');

        if ($request->month) {
            $date = Carbon::parse($request->month);
        }

        $start = Carbon::parse($date)->startOfMonth();
        $finish = Carbon::parse($date)->endOfMonth();
        $collectionPercentageForMonth = 0;
        $receiptPercentageForMonth = 0;
        $routePercentageForMonth = 0;
        $totalReceiptMissed = 0;
        $totalRouteMissed = 0;
        $averageReceiptMissed = 0;
        $averageRouteMissed = 0;

        $kpis = \App\Models\ScanningKpi::whereBetween('date', [$start, $finish])->get();

        if ($kpis->count() > 0) {
            $collectionPercentageForMonth = ($kpis->sum('expected') > 0 && $kpis->sum('collection') > 0) ? round((100 / $kpis->sum('expected')) * $kpis->sum('collection'), 1) : 0;
            $receiptPercentageForMonth = ($kpis->sum('expected') > 0 && $kpis->sum('receipt') > 0) ? round((100 / $kpis->sum('expected')) * $kpis->sum('receipt'), 1) : 0;
            $routePercentageForMonth = ($kpis->sum('expected') > 0 && $kpis->sum('route') > 0) ? round((100 / $kpis->sum('expected')) * $kpis->sum('route'), 1) : 0;

            $totalReceiptMissed = $kpis->sum('receipt_missed');
            $totalRouteMissed = $kpis->sum('route_missed');

            $averageReceiptMissed = round(($totalReceiptMissed / $kpis->count()), 0, PHP_ROUND_HALF_UP);
            $averageRouteMissed = round(($totalRouteMissed / $kpis->count()), 0, PHP_ROUND_HALF_UP);
        }

        return view('reports.scanning_kpis', compact('report', 'kpis', 'collectionPercentageForMonth', 'receiptPercentageForMonth', 'routePercentageForMonth', 'totalReceiptMissed', 'totalRouteMissed', 'averageReceiptMissed', 'averageRouteMissed'));
    }

    /**
     * Label downloads.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function labelDownloads(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        $this->authorize(new Report);

        $logs = Log::select('logs.*')
            ->join('users', 'logs.user_id', '=', 'users.id')
            ->dateBetween($request->date_from, $request->date_to)
            ->hasInformation('Downloaded Label')
            ->where('users.email', 'LIKE', '%@antrim.ifsgroup.com%')
            ->orderBy('logs.id', 'DESC')
            ->paginate(250);

        return view('reports.label_downloads', compact('report', 'logs'));
    }
}
