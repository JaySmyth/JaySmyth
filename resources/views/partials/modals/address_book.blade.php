<div class="modal" id="address_book" tabindex="-1" role="dialog" aria-labelledby="address_book_label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row mb-3">          
                    <div class="col-sm-6"> 
                        <h4 class="float-left"><span class="far fa-address-book fa-lg mr-sm-2" aria-hidden="true"></span></h4>
                        <h4 class="mb-3 text-capitalize" id="address_book_title">Address Book</h4>                
                    </div>
                    <div class="col-sm-6">    
                        <input id="address_book_filter" type="text" class="form-control form-control-sm" placeholder="Type a name or company to filter results...">
                    </div>
                </div>

                <div class="modal-loading"><i class="fas fa-spinner fa-pulse fa-2x fa-fw"></i><span class="sr-only">Loading...</span></div>

                <div class="modal-overflow text-medium">
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Company</th>
                                <th>Address</th>             
                                <th>Country</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody id="address_book_body" class="cursor-pointer"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>