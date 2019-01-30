@extends('layouts.app')

@section('content')
<div class="row">

    <div class="col-sm-7">

        <h2>Help</h2>
        
        <hr>

        <p class="text-large">Please take the time to read through the user guide provided. A PDF download is available below. If you are still having difficulties please do not hesitate to contact via the appropriate contact.</p>

        <br>

        <a href="download/user_guide.pdf" class="btn btn-info btn-lg">Download User Guide</a>

        <br>
        <h4 class="mb-2 mt-5">Remote Support</h4>

        <p>Need further assistance? Download and install <strong>AnyDesk</strong> to allow us to connect to your PC.</p>

        <p><br><a href="http://anydesk.com/download" class="btn btn-primary btn-lg" target="_blank">Download AnyDesk</a></p>

    </div>


    <div class="col-sm-5">

        <h2>Contacts</h2>

        <hr>
        
        <div class="row mb-3">
            <div class="col-sm-6"><strong>Courier Department</strong></div>
            <div class="col-sm-6"><a href="mailto:it@antrim.ifsgroup.com">courier@antrim.ifsgroup.com</a></div>
        </div>

        <div class="row mb-3">
            <div class="col-sm-6"><strong>Exports Department</strong></div>
            <div class="col-sm-6"><a href="mailto:exports@antrim.ifsgroup.com">exports@antrim.ifsgroup.com</a></div>
        </div>

        <div class="row mb-3">
            <div class="col-sm-6"><strong>Imports Department</strong></div>
            <div class="col-sm-6"><a href="mailto:imports@antrim.ifsgroup.com">imports@antrim.ifsgroup.com</a></div>
        </div>

        <div class="row mb-3">
            <div class="col-sm-6"><strong>Sea Freight Department</strong></div>
            <div class="col-sm-6"><a href="mailto:sea@antrim.ifsgroup.com">sea@antrim.ifsgroup.com</a></div>
        </div>

        <div class="row mb-3">
            <div class="col-sm-6"><strong>Transport Department</strong></div>
            <div class="col-sm-6"><a href="mailto:transport@antrim.ifsgroup.com">transport@antrim.ifsgroup.com</a></div>
        </div>

        <div class="row mb-3">
            <div class="col-sm-6"><strong>IT Department</strong></div>
            <div class="col-sm-6"><a href="mailto:it@antrim.ifsgroup.com">it@antrim.ifsgroup.com</a></div>
        </div>

        <div class="row mb-3">
            <div class="col-sm-6"><strong>Sales</strong></div>
            <div class="col-sm-6"><a href="mailto:sales@antrim.ifsgroup.com">sales@antrim.ifsgroup.com</a></div>
        </div>
        
        <hr>
        
        <div class="row mb-3">
            <div class="col-sm-6"><span class="fas fa-lg fa-phone" aria-hidden="true"></span> <strong class="ml-sm-3">Telephone</strong></div>
            <div class="col-sm-6">+44 28 9446 4211</div>
        </div>

        <div class="row mb-3">
            <div class="col-sm-6"><span class="fas fa-lg fa-external-link" aria-hidden="true"></span> <strong class="ml-sm-3">IFS Group Website</strong></div>
            <div class="col-sm-6"><a href="http://www.ifsgroup.com" target="_blank">http://www.ifsgroup.com</a></div>
        </div>
    </div>    
</div>
@endsection