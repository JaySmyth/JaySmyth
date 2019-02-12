<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Maintenance\CancelOldShipments::class,
        Commands\Maintenance\ClearUpTrackingEvents::class,
        Commands\Maintenance\CloseStagnantTransportJobs::class,
        Commands\Maintenance\DeleteOldLogEntries::class,
        Commands\Maintenance\EmptyTempStorage::class,
        Commands\Maintenance\CorrectStatusOnTransportJobs::class,
        Commands\Maintenance\CleanAddressTable::class,
        Commands\PurchaseInvoices\ImportFedexPurchaseInvoices::class,
        Commands\PurchaseInvoices\ImportUpsPurchaseInvoices::class,
        Commands\PurchaseInvoices\ImportDhlPurchaseInvoices::class,
        Commands\PurchaseInvoices\ImportPrimaryFreightPurchaseInvoices::class,
        Commands\Transend\SendJobs::class,
        Commands\Transend\CancelJobs::class,
        Commands\Transend\ProcessFiles::class,
        Commands\AutoManifest::class,
        Commands\CheckApi::class,
        Commands\CheckPricing::class,
        Commands\CheckJobQueue::class,
        Commands\UpdateStagnantShipments::class,
        Commands\StartRfserver::class,
        Commands\CheckRfserver::class,
        Commands\UploadFiles::class,
        Commands\CloseDriverManifests::class,
        Commands\OpenDriverManifests::class,
        Commands\NotifyTransportDepartmentPodRequired::class,
        Commands\UpdateScsJobNumbersOnPurchaseInvoiceLines::class,
        Commands\ProcessShipmentUploads::class,
        Commands\UpdatePrimaryFreightShipments::class,
        Commands\CheckForMissingPrimaryFreightDetails::class,
        Commands\GeneratePodDockets::class,
        Commands\ManifestUnmanifestedShipments::class,
        Commands\ImportMultifreightFiles::class,
        Commands\UpdateScsJobNumbersOnShipments::class,
        Commands\UploadShipmentsToPrimaryFreight::class,
        Commands\CheckForDuplicateShipments::class,
        Commands\ProcessScsCollectionRequests::class,
        Commands\SendTrackingNumbers::class,
        Commands\ProcessVendorvillageOrders::class,
        Commands\PerformRateIncrease::class,
        Commands\UpdateShopify::class,
        Commands\BulkCreateTrackers::class,
        Commands\SendScanningReportEmail::class,
        Commands\CreatePrimaryLogisticsOrders::class,
        Commands\LogScanningKpis::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /*
         * Maintenance
         */
        $schedule->exec('/sbin/reboot')->dailyAt('22:30');
        $schedule->command('ifs:empty-temp-storage')->dailyAt('03:30');
        $schedule->command('ifs:delete-old-log-entries')->dailyAt('05:20');

        /*
         * Purchase Invoice imports
         */
        $schedule->command('ifs:import-fedex-purchase-invoices')->weekdays()->hourly()->between('9:00', '18:00');
        $schedule->command('ifs:import-ups-purchase-invoices')->weekdays()->hourly()->between('9:00', '18:00');
        $schedule->command('ifs:import-dhl-purchase-invoices')->weekdays()->hourly()->between('9:00', '18:00');
        $schedule->command('ifs:import-primary-freight-purchase-invoices')->weekdays()->hourly()->between('9:00', '18:00');
        $schedule->command('ifs:update-scs-job-numbers-on-purchase-invoice-lines')->weekdays()->dailyAt('05:30');

        /*
         * Transend
         */
        $schedule->command('transend:send')->weekdays()->everyFiveMinutes()->withoutOverlapping()->between('7:10', '20:25');
        $schedule->command('transend:cancel')->weekdays()->everyFiveMinutes()->withoutOverlapping()->between('7:10', '20:25');
        $schedule->command('transend:process-files')->weekdays()->everyFiveMinutes()->withoutOverlapping()->between('8:00', '20:25');

        /*
         * Shipment related
         */
        $schedule->command('ifs:auto-manifest')->weekdays()->everyFiveMinutes()->withoutOverlapping();
        $schedule->command('ifs:cancel-old-shipments')->dailyAt('08:00');
        $schedule->command('ifs:update-stagnant-shipments')->dailyAt('08:05');
        $schedule->command('ifs:process-shipment-uploads')->everyMinute()->withoutOverlapping();
        $schedule->command('ifs:check-for-duplicate-shipments')->twiceDaily(13, 17);
        $schedule->command('ifs:update-shopify')->twiceDaily(11, 19);        
        
        /*
         * Primary Freight
         */
        $schedule->command('ifs:update-primary-freight-shipments')->hourly();
        $schedule->command('ifs:upload-shipments-to-primary-freight')->dailyAt(14, 00);
        $schedule->command('ifs:upload-shipments-to-primary-freight')->twiceDaily(17, 20);        
        $schedule->command('ifs:check-for-missing-primary-freight-details')->dailyAt(9, 00);
        
        /*
         * Primary Logistics
         */
        //$schedule->command('ifs:create-primary-logistics-orders')->hourly();
        //$schedule->command('ifs:cancel-primary-logistics-orders')->hourly();

        /*
         * RF server
         */
        $schedule->command('ifs:start-rfserver')->everyMinute()->withoutOverlapping()->between('7:00', '23:00');
        $schedule->command('ifs:check-rfserver')->everyFiveMinutes()->withoutOverlapping()->between('7:00', '23:00');

        /*
         * Misc
         */
        $schedule->command('ifs:upload-files')->everyMinute()->withoutOverlapping();
        $schedule->command('ifs:check-job-queue')->everyFiveMinutes()->withoutOverlapping()->between('6:00', '23:00');

        /*
         * Transport
         */
        $schedule->command('ifs:close-driver-manifests')->dailyAt('21:00');
        $schedule->command('ifs:open-driver-manifests')->dailyAt('07:00');        
        $schedule->command('ifs:close-stagnant-transport-jobs')->dailyAt('04:35');
        $schedule->command('ifs:correct-status-on-transport-jobs')->dailyAt('04:38');
        $schedule->command('ifs:notify-transport-department-pod-required')->weekdays()->dailyAt('09:30');
        $schedule->command('ifs:log-scanning-kpis')->weekdays()->dailyAt('21:28');
        $schedule->command('ifs:send-scanning-report-email')->weekdays()->dailyAt('21:30');
            
        /*
         * Multifreight
         */
        $schedule->command('ifs:import-multifreight-files')->weekdays()->everyFiveMinutes()->withoutOverlapping();
        $schedule->command('ifs:process-scs-collection-requests')->weekdays()->everyFiveMinutes()->withoutOverlapping();
        $schedule->command('ifs:update-scs-job-numbers-on-shipments --invoiced=1')->weekdays()->hourly()->between('10:00', '17:00')->withoutOverlapping();
        $schedule->command('ifs:update-scs-job-numbers-on-shipments --invoiced=0')->weekdays()->dailyAt('22:45');


        /*
         * Vendorvillage
         */
        $schedule->command('ifs:process-vendorvillage-orders')->everyFiveMinutes()->withoutOverlapping();
    }

}
