<div class="col-md-8">
    <div class="table-responsive">
        <table class="table table-borderless">
            <tbody>
                <tr>
                    <td><b>CCCode</b></td>
                    <td><b>:</b></td>
                    <td>{{$RefChiefComplaints->CCCode??""}}</td>
                </tr>

                <tr>
                    <td><b>Description</b></td>
                    <td><b>:</b></td>
                    <td>{{$RefChiefComplaints->Description??""}}</td>
                </tr>
                <tr>
                    <td><b>Status</b></td>
                    <td><b>:</b></td>
                    <td>@if(isset($RefChiefComplaints->Status) && $RefChiefComplaints->Status==1) Active @else Inactive @endif</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>