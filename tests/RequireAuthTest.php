<?php

namespace Tests\Feature;

use Tests\TestCase;

class RequireAuthTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRequireAuthTest()
    {
        $this->get('/')->assertStatus(302);
        $this->get('/preferences')->assertStatus(302);
        $this->get('/label/token')->assertStatus(404);
        $this->get('/labels/token/userid')->assertStatus(404);
        $this->get('/commercial-invoice/token')->assertStatus(302);
        // $this->get('/commercial-invoices/source')->assertStatus(404);
        $this->get('/despatch-note/token')->assertStatus(302);
        //$this->get('/despatch-notes/source')->assertStatus(404);
    }

    /**
     * @test
     * @return void
     */
    public function address_routes_should_be_authenticated()
    {
        $this->get('/addresses')->assertStatus(302);
    }

    /**
     * @test
     * @return void
     */
    public function shipment_routes_should_be_authenticated()
    {
        $this->get('/shipments')->assertStatus(302);
        $this->get('/shipments/collection-manifest')->assertStatus(302);
        $this->get('/shipments/batched-labels/labelType')->assertStatus(302);
        $this->get('/shipments/batched-commercial-invoices')->assertStatus(302);
        $this->get('/shipments/batched-shipping-docs/labelType')->assertStatus(302);
        $this->get('/shipments/pod')->assertStatus(302);
        $this->get('/shipments/update-dims')->assertStatus(302);
        $this->get('/shipments/rts')->assertStatus(302);
        $this->get('/shipments/todays-labels')->assertStatus(302);
    }

    /**
     * @test
     * @return void
     */
    public function invoice_run_routes_should_be_authenticated()
    {
        $this->get('/invoice-runs')->assertStatus(302);
        $this->get('/invoice-runs/create')->assertStatus(302);
        $this->get('/invoice-runs/id')->assertStatus(302);
    }

    /**
     * @test
     * @return void
     */
    public function account_routes_should_be_authenticated()
    {
        $this->get('/account')->assertStatus(302);
        $this->get('/account/settings')->assertStatus(302);
        $this->get('/account/password')->assertStatus(302);
    }

    /**
     * @test
     * @return void
     */
    public function user_routes_should_be_authenticated()
    {
        $this->get('/users')->assertStatus(302);
        $this->get('/users/userId/add-company')->assertStatus(302);
        $this->get('/users/userId/remove-company/companyId')->assertStatus(302);
        $this->get('/users/userId/reset-password')->assertStatus(302);
    }

    /**
     * @test
     * @return void
     */
    public function tracking_routes_should_be_authenticated()
    {
        $this->get('/tracking/shipmentId/create')->assertStatus(302);
        $this->get('/tracking/token/type')->assertStatus(404);
        $this->get('/tracking/tracking/edit')->assertStatus(404);
        $this->get('/track')->assertStatus(200);
        $this->get('/tracker/consignment')->assertStatus(302);
        $this->get('/create-tracker')->assertStatus(302);
        $this->get('/bulk-create-trackers')->assertStatus(302);
        $this->get('easypost-push')->assertStatus(302);
    }

    /**
     * @test
     * @return void
     */
    public function document_routes_should_be_authenticated()
    {
        $this->get('documents/create/parent/id')->assertStatus(302);
    }

    /**
     * @test
     * @return void
     */
    public function companies_routes_should_be_authenticated()
    {
        $this->get('/companies')->assertStatus(302);
        $this->get('companies/{companies}/services')->assertStatus(302);
        $this->get('companies/{companies}/status')->assertStatus(302);
        $this->get('companies/download')->assertStatus(302);
        $this->get('companies/companies/collection-settings')->assertStatus(302);
        $this->get('localisation')->assertStatus(302);
    }

    /**
     * @test
     * @return void
     */
    public function companies_service_routes_should_be_authenticated()
    {
        $this->get('company-service-rate/companies/services/delete')->assertStatus(302);
        $this->get('company-service-rate/companies/status')->assertStatus(302);
    }
}
