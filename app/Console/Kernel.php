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
        Commands\PurchaseInvoices\ImportTntPurchaseInvoices::class,
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
        Commands\PrimaryLogistics\CreateOrders::class,
        Commands\PrimaryLogistics\CancelOrders::class,
        Commands\PrimaryLogistics\GetTrackingNumbers::class,
        Commands\PrimaryLogistics\GetInventory::class,
        Commands\LogScanningKpis::class,
        Commands\TntTotalVolume::class,
        Commands\NormaliseRates::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /*
         * Maintenance
         */
        $schedule->exec('/sbin/reboot')->dailyAt('21:30');
        $schedule->command('ifs:empty-temp-storage')->dailyAt('03:30');
        $schedule->command('ifs:delete-old-log-entries')->dailyAt('05:20');

        /*
         * Purchase Invoice imports
         */
        $schedule->command('ifs:import-fedex-purchase-invoices')->weekdays()->hourly()->between('8:00', '17:00');
        $schedule->command('ifs:import-ups-purchase-invoices')->weekdays()->hourly()->between('8:00', '17:00');
        $schedule->command('ifs:import-dhl-purchase-invoices')->weekdays()->hourly()->between('8:00', '17:00');
        $schedule->command('ifs:import-primary-freight-purchase-invoices')->weekdays()->hourly()->between('8:00', '17:00');
        $schedule->command('ifs:import-tnt-purchase-invoices')->weekdays()->hourly()->between('8:00', '17:00');
        $schedule->command('ifs:update-scs-job-numbers-on-purchase-invoice-lines')->weekdays()->twiceDaily(8, 14);

        /*
         * Transend
         */
        $schedule->command('transend:send')->weekdays()->everyFiveMinutes()->between('6:10', '20:25')->withoutOverlapping();
        $schedule->command('transend:cancel')->weekdays()->everyFiveMinutes()->between('6:10', '20:25')->withoutOverlapping();
        $schedule->command('transend:process-files')->weekdays()->everyFiveMinutes()->between('7:00', '20:25')->withoutOverlapping();

        /*
         * Shipment related
         */
        $schedule->command('ifs:auto-manifest')->weekdays()->everyFiveMinutes();
        $schedule->command('ifs:cancel-old-shipments')->dailyAt('07:00');
        $schedule->command('ifs:update-stagnant-shipments')->dailyAt('07:05');
        $schedule->command('ifs:process-shipment-uploads')->everyMinute()->withoutOverlapping();
        $schedule->command('ifs:check-for-duplicate-shipments')->twiceDaily(12, 16);
        $schedule->command('ifs:update-shopify')->twiceDaily(10, 19);

        /*
         * Primary Logistics
         */
        $schedule->command('primary-logistics:create-orders')->hourly();
        $schedule->command('primary-logistics:cancel-orders')->hourly();
        $schedule->command('primary-logistics:get-tracking-numbers')->hourly();
        $schedule->command('primary-logistics:get-inventory')->weekdays()->dailyAt('09:30');
        $schedule->command('ifs:check-for-missing-primary-freight-details')->dailyAt(7, 00);

        /*
         * RF server
         */
        $schedule->command('ifs:start-rfserver')->everyMinute()->between('8:00', '22:00')->withoutOverlapping();
        $schedule->command('ifs:check-rfserver')->everyMinute()->between('8:00', '22:00')->withoutOverlapping();

        /*
         * Misc
         */
        $schedule->command('ifs:upload-files')->everyMinute()->withoutOverlapping();
        $schedule->command('ifs:check-job-queue')->everyFiveMinutes()->between('6:00', '22:00')->withoutOverlapping();

        /*
         * Transport
         *
         */
        $schedule->command('ifs:close-driver-manifests')->dailyAt('20:00');
        $schedule->command('ifs:open-driver-manifests')->dailyAt('06:00');
        $schedule->command('ifs:close-stagnant-transport-jobs')->dailyAt('04:35');
        $schedule->command('ifs:correct-status-on-transport-jobs')->dailyAt('04:38');
        $schedule->command('ifs:notify-transport-department-pod-required')->weekdays()->dailyAt('08:20');
        $schedule->command('ifs:log-scanning-kpis')->weekdays()->dailyAt('08:30');

        /*
         * Multifreight
         */
        $schedule->command('ifs:import-multifreight-files')->weekdays()->everyFiveMinutes()->withoutOverlapping();
        $schedule->command('ifs:process-scs-collection-requests')->weekdays()->everyFiveMinutes()->withoutOverlapping();
        $schedule->command('ifs:update-scs-job-numbers-on-shipments --invoiced=1')->weekdays()->hourly()->between('09:00', '16:00');
        $schedule->command('ifs:update-scs-job-numbers-on-shipments --invoiced=0')->weekdays()->dailyAt('21:45');

        /*
         * Vendorvillage
         */
        $schedule->command('ifs:process-vendorvillage-orders')->everyFiveMinutes()->withoutOverlapping();
    }

}
