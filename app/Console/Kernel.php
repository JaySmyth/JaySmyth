<?php

namespace App\Console;

use App\Console\Commands\SendShipmentResetsNotification;
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
        Commands\RateIncrease\RecalcNiRates::class,
        Commands\Brexit\UpgradeDhlEsxService::class,
        Commands\Maintenance\CancelOldShipments::class,
        Commands\Maintenance\DeleteSavedShipments::class,
        Commands\Maintenance\ClearUpTrackingEvents::class,
        Commands\Maintenance\CloseStagnantTransportJobs::class,
        Commands\Maintenance\DeleteOldLogEntries::class,
        Commands\Maintenance\EmptyTempStorage::class,
        Commands\Maintenance\CorrectStatusOnTransportJobs::class,
        Commands\Maintenance\CleanAddressTable::class,
        Commands\Maintenance\CheckForUnprocessedUploads::class,
        Commands\PurchaseInvoices\ImportFedexPurchaseInvoices::class,
        Commands\PurchaseInvoices\ImportUpsPurchaseInvoices::class,
        Commands\PurchaseInvoices\ImportDhlPurchaseInvoices::class,
        Commands\PurchaseInvoices\ImportPrimaryFreightPurchaseInvoices::class,
        Commands\PurchaseInvoices\ImportTntPurchaseInvoices::class,
        Commands\PurchaseInvoices\ImportExpressFreightPurchaseInvoices::class,
        Commands\PurchaseInvoices\ImportXdpPurchaseInvoices::class,
        Commands\PurchaseInvoices\ImportDxPurchaseInvoices::class,
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
        Commands\NotifyTransportDepartmentPodRequired::class,
        Commands\UpdateScsJobNumbersOnPurchaseInvoiceLines::class,
        Commands\ProcessShipmentUploads::class,
        Commands\GeneratePodDockets::class,
        Commands\ManifestUnmanifestedShipments::class,
        Commands\ImportMultifreightFiles::class,
        Commands\UpdateScsJobNumbersOnShipments::class,
        Commands\ExpressFreight\ResetExpressFreightConsignmentNumberSequence::class,
        Commands\ExpressFreight\UploadShipmentsToExpressFreight::class,
        Commands\ExpressFreight\UploadNIShipmentsToExpressFreight::class,
        Commands\ExpressFreight\ProcessExpressFreightTracking::class,
        Commands\XDP\ProcessXDPTracking::class,
        Commands\DX\ProcessDXTracking::class,
        Commands\CheckForDuplicateShipments::class,
        Commands\ProcessScsCollectionRequests::class,
        Commands\SendTrackingNumbers::class,
        Commands\ProcessVendorvillageOrders::class,
        Commands\RateIncrease\IncreaseCostRate::class,
        Commands\RateIncrease\PerformRateIncrease::class,
        Commands\UpdateShopify::class,
        Commands\LogScanningKpis::class,
        Commands\TntTotalVolume::class,
        Commands\GetTracking::class,
        Commands\DispatchEasypostTrackers::class,
        Commands\UploadFedExCommercialInvoices::class,
        Commands\RepriceShipments::class,
        Commands\DispatchCustomerDomesticRateReport::class,
        Commands\DispatchDeliveryPerformance::class,
        Commands\DispatchExtendedTransitReport::class,
        Commands\DbSchenker\SendDbSchenkerSeaExportTransactions::class,
        Commands\SendShipmentResetsNotification::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  Schedule  $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /*
         * Maintenance
         */
        $schedule->exec('/sbin/reboot')->dailyAt('23:30');
        $schedule->command('ifs:empty-temp-storage')->dailyAt('05:30');
        $schedule->command('ifs:delete-old-log-entries')->dailyAt('07:20');
        $schedule->command('ifs:check-for-unprocessed-uploads')->hourly();

        /*
         * Purchase Invoice imports
         */
        $schedule->command('ifs:import-fedex-purchase-invoices')->weekdays()->hourly()->between('8:00', '18:00');
        $schedule->command('ifs:import-ups-purchase-invoices')->weekdays()->hourly()->between('8:00', '18:00');
        $schedule->command('ifs:import-dhl-purchase-invoices')->weekdays()->hourly()->between('8:00', '18:00');
        $schedule->command('ifs:import-primary-freight-purchase-invoices')->weekdays()->hourly()->between('8:00', '18:00');
        $schedule->command('ifs:import-tnt-purchase-invoices')->weekdays()->hourly()->between('8:00', '18:00');
        $schedule->command('ifs:import-express-freight-purchase-invoices')->weekdays()->hourly()->between('8:00', '18:00');
        $schedule->command('ifs:import-xdp-purchase-invoices')->weekdays()->hourly()->between('8:00', '18:00');
        $schedule->command('ifs:import-dx-purchase-invoices')->twiceDaily(10, 15);
        $schedule->command('ifs:update-scs-job-numbers-on-purchase-invoice-lines')->weekdays()->everyThreeHours();

        /*
         * Transend
         */
        $schedule->command('transend:send')->weekdays()->everyFiveMinutes()->between('6:10', '20:25')->withoutOverlapping(5);
        $schedule->command('transend:cancel')->weekdays()->everyFiveMinutes()->between('6:10', '20:25')->withoutOverlapping(5);
        $schedule->command('transend:process-files')->weekdays()->everyFiveMinutes()->between('6:10', '17:30');
        $schedule->command('transend:process-files')->weekdays()->between('17:31', '20:25');

        /*
         * Shipment related
         */
        $schedule->command('ifs:auto-manifest')->weekdays()->everyMinute();
        $schedule->command('ifs:cancel-old-shipments')->dailyAt('07:00');
        //$schedule->command('ifs:delete-saved-shipments')->dailyAt('06:55');
        $schedule->command('ifs:update-stagnant-shipments')->dailyAt('07:05');
        $schedule->command('ifs:process-shipment-uploads')->withoutOverlapping(2);
        $schedule->command('ifs:check-for-duplicate-shipments')->twiceDaily(12, 14);
        $schedule->command('ifs:dispatch-easypost-trackers')->dailyAt('20:40');
        $schedule->command('ifs:dispatch-delivery-performance')->dailyAt('08:15');
        // $schedule->command('ifs:dispatch-extended-transit-report')->dailyAt('08:15');
        $schedule->command('ifs:upload-fedex-commercial-invoices')->weekdays()->everyFiveMinutes()->between('16:20', '17:45');
        $schedule->command('ifs:send-shipment-resets-notification')->weekdays()->dailyAt('09:30');

        /*
         * Tracking updates
         */
        $schedule->command('ifs:get-tracking --active=1')->everyThirtyMinutes()->withoutOverlapping(10);
        $schedule->command('ifs:get-tracking --active=0')->twiceDaily(8, 22);

        /*
         * RF server
         */
        $schedule->command('ifs:start-rfserver')->between('7:00', '21:00')->withoutOverlapping(1);
        $schedule->command('ifs:check-rfserver')->between('7:00', '21:00')->withoutOverlapping(1);

        /*
         * Misc
         */
        $schedule->command('ifs:upload-files')->withoutOverlapping(5);
        $schedule->command('ifs:check-job-queue')->everyFiveMinutes()->between('6:15', '22:00')->withoutOverlapping(2);

        /*
         * Transport
         *
         */
        $schedule->command('ifs:close-stagnant-transport-jobs')->dailyAt('05:35');
        $schedule->command('ifs:correct-status-on-transport-jobs')->dailyAt('05:38');
        $schedule->command('ifs:notify-transport-department-pod-required')->weekdays()->dailyAt('08:20');
        $schedule->command('ifs:log-scanning-kpis')->weekdays()->dailyAt('07:55');

        /*
         * Express Freight
         */
        $schedule->command('ifs:reset-express-freight-consignment-number-sequence')->dailyAt('00:00');
        $schedule->command('ifs:upload-shipments-to-express-freight')->weekdays()->dailyAt('17:00');
        $schedule->command('ifs:upload-ni-shipments-to-express-freight')->weekdays()->dailyAt('17:00');
        $schedule->command('ifs:process-express-freight-tracking')->weekdays()->hourly()->between('07:00', '19:00');

        /*
         * XDP
         */
        $schedule->command('ifs:process-xdp-tracking')->hourly();

        /*
          * DX
          */
        $schedule->command('ifs:process-dx-tracking')->everyFifteenMinutes()->withoutOverlapping(15);
        ;

        /*
         * Multifreight
         */
        $schedule->command('ifs:import-multifreight-files')->weekdays()->everyFiveMinutes()->withoutOverlapping(10);
        $schedule->command('ifs:process-scs-collection-requests')->weekdays()->everyFiveMinutes()->withoutOverlapping(5);
        $schedule->command('ifs:update-scs-job-numbers-on-shipments --invoiced=1')->weekdays()->hourly()->between('08:00', '16:00');
        $schedule->command('ifs:update-scs-job-numbers-on-shipments --invoiced=0')->weekdays()->dailyAt('20:45');

        /*
         * Vendorvillage
         */
        $schedule->command('ifs:process-vendorvillage-orders')->everyFiveMinutes()->withoutOverlapping(5);

        /*
         * DBSchenker
         */
        //$schedule->command('ifs:send-dbschenker-sea-export-transactions')->weekdays()->hourly()->between('09:30', '17:45');
    }
}
