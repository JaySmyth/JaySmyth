
<div class="modal fade" id="commodities" tabindex="-1" role="dialog" aria-labelledby="commodities_label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">

                <div id="commodity-list">
                    <div class="row mb-3">          
                        <div class="col-sm-3"> 
                            <h4>Commodities</h4>
                        </div>        
                        <div class="col-sm-4"> 
                            <input id="commodity_filter" type="text" class="form-control form-control-sm" placeholder="Description or commodity code...">
                        </div>   
                        <div class="col-sm-2"> 
                            {!! Form::select('commodity_filter_currency', dropDown('currencies', 'All Currencies'), null, array('id' => 'commodity_filter_currency', 'class' => 'form-control form-control-sm')) !!}
                        </div> 
                        <div class="col-sm-3"> 
                            <button id="button-new-commodity" class="btn btn-success btn-sm float-right" type="button">Create Commodity</button>
                        </div>  
                    </div>

                    <div class="modal-loading"><i class="fas fa-spinner fa-pulse fa-2x fa-fw"></i><span class="sr-only">Loading...</span></div>

                    <div class="modal-overflow text-medium">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>                            
                                    <th>Description</th>
                                    <th>Commodity</th>
                                    <th>Harmonized</th>
                                    <th>Ctry</th>
                                    <th>Unit Value</th>                                                                                 
                                    <th>&nbsp;</th>                          
                                </tr>
                            </thead>
                            <tbody id="commodities_body" class="cursor-pointer"></tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-loading"><i class="fas fa-spinner fa-pulse fa-2x fa-fw"></i><span class="sr-only">Loading...</span></div>

                {!! Form::Open(['id' => 'commodity-form', 'url' => 'commodities', 'class' => 'form-compact', 'autocomplete' => 'off']) !!}

                {!! Form::hidden('commodity_id', null, array('id' => 'commodity_id')) !!}

                <div class="modal-form-title">Create Commodity</div>
                <p class="text-danger text-center">It is your legal responsibility to provide correct commodity information. Please contact IFS if you require assistance.</p>
                <p class="example text-center">See <a href="https://www.gov.uk/trade-tariff/sections" target="_blank">https://www.gov.uk/trade-tariff/sections</a> for commodity code and <a href="https://hts.usitc.gov/" target="_blank">https://hts.usitc.gov/</a> for harmonized code.</p>
                <br>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label class="col-sm-5  col-form-label">
                                Description: <abbr title="This information is required.">*</abbr>
                            </label>
                            <div class="col-sm-7">
                                {!! Form::Text('description', null, ['id' => 'commodity_description','class' => 'form-control form-control-sm', 'maxlength' => '100']) !!}
                            </div>   
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-5  col-form-label">
                                Product Code:
                            </label>
                            <div class="col-sm-7">
                                {!! Form::Text('product_code', null, ['id' => 'commodity_product_code', 'class' => 'form-control form-control-sm', 'maxlength' => '30']) !!}
                            </div> 
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-5  col-form-label">
                                Manufacturer:
                            </label>
                            <div class="col-sm-7">
                                {!! Form::Text('manufacturer', null, ['id' => 'commodity_manufacturer','class' => 'form-control form-control-sm', 'maxlength' => '100']) !!}
                            </div>                        
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-5  col-form-label">
                                Country Mfr.: <abbr title="This information is required.">*</abbr>
                            </label>
                            <div class="col-sm-7">
                                {!! Form::select('country_of_manufacture', dropDown('countries', 'Please select'), 'GB', array('id' => 'commodity_country_of_manufacture', 'class' => 'form-control form-control-sm')) !!}
                            </div>                        
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-5  col-form-label">
                                Unit Value:
                            </label>
                            <div class="col-sm-4">
                                {!! Form::Text('unit_value', null, ['id' => 'commodity_unit_value','class' => 'form-control  form-control-sm decimal-only', 'maxlength' => '8']) !!}
                            </div>
                            <div class="col-sm-3">                
                                {!! Form::select('currency_code', dropDown('currencies'), null, array('id' => 'commodity_currency_code', 'class' => 'form-control form-control-sm')) !!}                
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">

                        <div class="form-group row">
                            <label class="col-sm-6  col-form-label">
                                Commodity Code:
                            </label>
                            <div class="col-sm-6">
                                {!! Form::Text('commodity_code', null, ['id' => 'commodity_commodity_code','class' => 'form-control form-control-sm numeric-only', 'maxlength' => '12']) !!}
                            </div> 
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-6  col-form-label">
                                Harmonized Code:
                            </label>
                            <div class="col-sm-6">
                                {!! Form::Text('harmonized_code', null, ['id' => 'commodity_harmonized_code','class' => 'form-control form-control-sm numeric-only', 'maxlength' => '10']) !!}
                            </div>                        
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-6  col-form-label">
                                UOM: <abbr title="This information is required.">*</abbr>
                            </label>
                            <div class="col-sm-6">
                                {!! Form::select('uom', dropDown('uoms'), 'EA', array('id' => 'commodity_uom', 'class' => 'form-control form-control-sm')) !!}
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-6  col-form-label">Unit Weight:</label>
                            <div class="col-sm-3">
                                {!! Form::Text('unit_weight', null, ['id' => 'commodity_unit_weight','class' => 'form-control  form-control-sm decimal-only', 'maxlength' => '8']) !!}
                            </div>
                            <div class="col-sm-3">
                                {!! Form::select('weight_uom', dropDown('weightUom'), null, array('id' => 'commodity_weight_uom', 'class' => 'form-control form-control-sm')) !!}
                            </div>
                        </div>

                    </div>
                </div>

                <div class="buttons-main text-center">
                    <button id="button-cancel-commodity" class="btn btn-secondary" type="button">Cancel</button>
                    <button id="button-save-commodity" class="btn btn-primary" type="button">Save Commodity</button>
                </div>

                {!! Form::Close() !!}

            </div>
        </div>
    </div>
</div>