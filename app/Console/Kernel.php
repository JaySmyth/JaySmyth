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
        Commands\Maintenance\CheckForUnprocessedUploads::class,
        Commands\PurchaseInvoices\ImportFedexPurchaseInvoices::class,
        Commands\PurchaseInvoices\ImportUpsPurchaseInvoices::class,
        Commands\PurchaseInvoices\ImportDhlPurchaseInvoices::class,
        Commands\PurchaseInvoices\ImportPrimaryFreightPurchaseInvoices::class,
        Commands\PurchaseInvoices\ImportTntPurchaseInvoices::class,
        Commands\PurchaseInvoices\ImportExpressFreightPurchaseInvoices::class,
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
        Commands\UpdatePrimaryFreightShipments::class,
        Commands\CheckForMissingPrimaryFreightDetails::class,
        Commands\GeneratePodDockets::class,
        Commands\ManifestUnmanifestedShipments::class,
        Commands\ImportMultifreightFiles::class,
        Commands\UpdateScsJobNumbersOnShipments::class,
        Commands\ExpressFreight\ResetExpressFreightConsignmentNumberSequence::class,
        Commands\ExpressFreight\UploadShipmentsToExpressFreight::class,
        Commands\ExpressFreight\UploadNIShipmentsToExpressFreight::class,
        Commands\ExpressFreight\ProcessExpressFreightTracking::class,
        Commands\XDP\ProcessXDPTracking::class,
        Commands\CheckForDuplicateShipments::class,
        Commands\ProcessScsCollectionRequests::class,
        Commands\SendTrackingNumbers::class,
        Commands\ProcessVendorvillageOrders::class,
        Commands\IncreaseCostRate::class,
        Commands\PerformRateIncrease::class,
        Commands\UpdateShopify::class,
        Commands\BulkCreateTrackers::class,
        Commands\PrimaryLogistics\CreateOrders::class,
        Commands\PrimaryLogistics\CancelOrders::class,
        Commands\PrimaryLogistics\GetTrackingNumbers::class,
        Commands\PrimaryLogistics\GetInventory::class,
        Commands\LogScanningKpis::class,
        Commands\TntTotalVolume::class,
        Commands\GetTracking::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /*
         * Maintenance
         */
        $schedule->exec('/sbin/reboot')->dailyAt('22:30');
        $schedule->command('ifs:empty-temp-storage')->dailyAt('04:30');
        $schedule->command('ifs:delete-old-log-entries')->dailyAt('06:20');
        $schedule->command('ifs:check-for-unprocessed-uploads')->hourly();

        /*
         * Purchase Invoice imports
         */
        $schedule->command('ifs:import-fedex-purchase-invoices')->weekdays()->hourly()->between('8:00', '16:00');
        $schedule->command('ifs:import-ups-purchase-invoices')->weekdays()->hourly()->between('8:00', '16:00');
        $schedule->command('ifs:import-dhl-purchase-invoices')->weekdays()->hourly()->between('8:00', '16:00');
        $schedule->command('ifs:import-primary-freight-purchase-invoices')->weekdays()->hourly()->between('8:00', '16:00');
        $schedule->command('ifs:import-tnt-purchase-invoices')->weekdays()->hourly()->between('8:00', '16:00');
        $schedule->command('ifs:import-express-freight-purchase-invoices')->weekdays()->hourly()->between('8:00', '16:00');
        $schedule->command('ifs:update-scs-job-numbers-on-purchase-invoice-lines')->weekdays()->twiceDaily(8, 14);

        /*
         * Transend
         */
        $schedule->command('transend:send')->weekdays()->everyFiveMinutes()->between('6:10', '20:25')->withoutOverlapping(5);
        $schedule->command('transend:cancel')->weekdays()->everyFiveMinutes()->between('6:10', '20:25')->withoutOverlapping(5);
        $schedule->command('transend:process-files')->weekdays()->everyTenMinutes()->between('6:10', '20:25')->withoutOverlapping(5);

        /*
         * Shipment related
         */
        $schedule->command('ifs:auto-manifest')->weekdays()->everyFiveMinutes();
        $schedule->command('ifs:cancel-old-shipments')->dailyAt('07:00');
        $schedule->command('ifs:update-stagnant-shipments')->dailyAt('07:05');
        $schedule->command('ifs:process-shipment-uploads')->withoutOverlapping(2);
        $schedule->command('ifs:check-for-duplicate-shipments')->twiceDaily(12, 16);

        /*
         * Tracking updates
         */
        $schedule->command('ifs:get-tracking --active=1')->everyThirtyMinutes()->withoutOverlapping(10);
        $schedule->command('ifs:get-tracking --active=0')->twiceDaily(8, 22);

        /*
         * Primary Logistics
         */
        $schedule->command('primary-logistics:create-orders')->hourly();
        $schedule->command('primary-logistics:cancel-orders')->hourly();
        $schedule->command('primary-logistics:get-tracking-numbers')->hourly();
        $schedule->command('primary-logistics:get-inventory')->weekdays()->dailyAt('09:30');
        $schedule->command('ifs:check-for-missing-primary-freight-details')->dailyAt(8, 00);

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
        $schedule->command('ifs:log-scanning-kpis')->weekdays()->dailyAt('08:00');

        /*
         * Express Freight
         */
        $schedule->command('ifs:reset-express-freight-consignment-number-sequence')->dailyAt('00:00');
        $schedule->command('ifs:upload-shipments-to-express-freight')->weekdays()->dailyAt('16:45');
        $schedule->command('ifs:upload-ni-shipments-to-express-freight')->weekdays()->dailyAt('16:45');
        $schedule->command('ifs:process-express-freight-tracking')->weekdays()->hourly()->between('07:00', '19:00');

        /*
         * XDP
         */
        $schedule->command('ifs:process-xdp-tracking')->hourly();

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
    }
}
