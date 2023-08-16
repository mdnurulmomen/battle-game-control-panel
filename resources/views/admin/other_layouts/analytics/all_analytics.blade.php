
@extends('admin.master_layout.app')

@section('stylebar')

    .widget-small .icon {
        
        min-width: 55px;
    }

    .fa-stack {
        
        width: 1em;
    }

@endsection

@section('contents')     

    <div class="row">    
        <div class="col-md-6">
            <div class="tile">
                <h3 class="tile-title">Talktime</h3>

                <div class="card mb-3 border-dark">
                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-6 mb-4">
                                <label for="validationServerUsername">Start Date</label>
                                <div class="input-group">
                                    <input class="form-control is-valid datePicker talkTimeTable" type="text" name="talkTimeStartDate" placeholder="Select Date" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="validationServerUsername">Close Date</label>
                                <div class="input-group">
                                    <input class="form-control is-valid datePicker talkTimeTable" type="text" name="talkTimeEndDate" placeholder="Select Date" autocomplete="off">
                                </div>
                            </div>

                        </div>                        

                        <div class="row">

                            <div class="col-md-6 text-center">
                                <div class="widget-small info">
                                    <i class="icon fa fa-stack  ">#</i>

                                    <div class="info">
                                        <h4 class="talkTimeNumber">
                                            <b>{{ $allTreasureRedemptions->count() ?? 0 }}</b> 
                                        </h4>
                                    </div>
                                </div>    
                            </div>

                            <div class="col-md-6">
                                <div class="widget-small info">
                                    <i class="icon fa fa-money "></i>
                                    <div class="info">
                                        <h4 class="talkTimeCost">
                                            
                                            BDT : 
                                            <b>{{ round($allTreasureRedemptions->sum('equivalent_price'), 2) ?? 0 }}</b> 
                                        </h4>
                                    </div>
                                </div>
                            </div> 

                        </div>
                    </div>
                </div> 
            </div>
        </div>

        <div class="col-md-6">
            <div class="tile">
                <h3 class="tile-title">Earning</h3>

                <div class="card mb-3 border-dark">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="validationServerUsername">Start Date</label>
                                <div class="input-group">
                                    <input class="form-control is-valid datePicker earningTable" type="text" name="earningStartDate" placeholder="Select Date" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="validationServerUsername">Close Date</label>
                                <div class="input-group">
                                    <input class="form-control is-valid datePicker earningTable" type="text" name="earningEndDate" placeholder="Select Date" autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <div class="row text-center">
                        
                            <div class="col-md-12">
                                <div class="widget-small info">
                                    <i class="icon fa fa-money"></i>
                                    <div class="info">
                                        <h4 class="earningAmount">
                                            BDT : <b>{{ round($updatedEarning->total_currency_earning, 2) ?? 0 }}</b> 
                                        </h4>
                                    </div>
                                </div>
                            </div>  
                                      
                        </div>   
                    </div>
                </div>
                
            </div>
        </div>
    </div>


    <div class="row">   
        <div class="col-md-6">
            <div class="tile">    
                <div class="row">   
                    <div class="col-md-7 col-5">
                        <h3 class="tile-title">Physical Treasure </h3> 
                    </div>

                    <div class="col-md-5 col-7 float-right">    
                        <p class="bg-info m-0">
                            # Treasure Gifted : <b>{{ $treasureCounter->total_treasure_gifted ?? 0 }}</b>
                        </p>
                    
                        <p class="bg-warning m-0">
                            # Treasure Picked : <b>{{ $treasureCounter->total_treasure_collected ?? 0 }}</b>
                        </p>       
                   </div>
                </div>
                
                <div class="card mb-3 border-dark">
                    <div class="card-body">                                     
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="validationServerUsername">Start Date</label>
                                <div class="input-group">
                                    <input class="form-control is-valid datePicker treasureTable" type="text" name="treasureStartDate" placeholder="Select Date" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="validationServerUsername">Close Date</label>
                                <div class="input-group">
                                    <input class="form-control is-valid datePicker treasureTable" type="text" name="treasureEndDate" placeholder="Select Date" autocomplete="off">
                                </div>
                            </div>
                        </div>                        

                        <div class="row">
                            <div class="col-md-6 text-center">
                                <div class="widget-small info">
                                    <i class="icon fa fa-stack  ">#</i>

                                    <div class="info">
                                        <h4 class="treasureNumber"><b>{{ $allPhysicalTreasureRedemptions->count() ?? 0 }}</b> </h4>
                                    </div>
                                </div>    
                            </div>

                            <div class="col-md-6">
                                <div class="widget-small info">
                                    <i class="icon fa fa-money "></i>
                                    <div class="info">
                                        <h4 class="treasureCost">
                                            
                                            BDT : 
                                            <b>{{ round($allPhysicalTreasureRedemptions->sum('equivalent_price'), 2) ?? 0 }}</b> 
                                        </h4>
                                    </div>
                                </div>
                            </div> 
                        </div>
                    </div>
                </div> 
            </div>
        </div>

        <div class="col-md-6">
            <div class="tile">
                <h3 class="tile-title">Gems</h3>
                <div class="card mb-3 border-dark">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="validationServerUsername">Start Date</label>
                                <div class="input-group">
                                    <input class="form-control is-valid datePicker gemPacksTable" type="text" name="gemsPackStartDate" placeholder="Select Date" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="validationServerUsername">Close Date</label>
                                <div class="input-group">
                                    <input class="form-control is-valid datePicker gemPacksTable" type="text" name="gemsPackEndDate" placeholder="Select Date" autocomplete="off">
                                </div>
                            </div>
                        </div>                        

                        <div class="row">
                            <div class="col-md-6 text-center">
                                <div class="widget-small info">
                                    <i class="icon fa fa-stack  ">#</i>

                                    <div class="info">
                                        <h4 class="gemPacksNumber"><b>{{ $allSoldGemPacks->count() ?? 0 }}</b> </h4>
                                    </div>
                                </div>    
                            </div>

                            <div class="col-md-6">
                                <div class="widget-small info">
                                    <i class="icon fa fa-money "></i>
                                    <div class="info">
                                        <h4 class="gemPacksCost">
                                            
                                            @php

                                                $totalCost = 0;

                                                if (!$allSoldGemPacks->isEmpty()) {
                                                    
                                                    foreach($allSoldGemPacks as $soldPack){

                                                        $totalCost += App\Models\Store::find($soldPack->item_id)->offered_price_taka ?? 0;
                                                    }
                                                }
 
                                            @endphp

                                            BDT : <b>{{ round($totalCost, 2) ?? 0 }}</b> 
                                        </h4>
                                    </div>
                                </div>
                            </div> 

                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>

    <div class="row">    
        <div class="col-md-12">
            <div class="tile">

                <div class="tile-title-w-btn">
                    
                    <h3 class="tile-title">Purchase </h3>
                    
                </div>  

                <div class="card mb-3 border-dark">

                    <div class="card-body">                       

                        <div class="row">

                            <table class="table table-hover table-striped table-bordered text-center" cellspacing="0" width="100%" id="purchaseTable">
                        
                                <thead>

                                    <tr>
                                        <th>Item Id</th>
                                        <th>Buyer Id</th>
                                        <th>Buyer Name</th>
                                        <th>Mobile Number</th>
                                        <th>Location</th>
                                        <th>Gateway</th>
                                    </tr>

                                </thead>
                                
                            </table>
                            
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>

@stop

@push('scripts')

    <script type = "text/javascript" src = "https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js">
    </script>

    <script src="{{ asset('assets/admin/js/bootstrap-datepicker.min.js') }}"></script>


    <script type="text/javascript">
        
        $( function() {

            $('.datePicker').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
                todayHighlight: true
            });

            $("input.talkTimeTable").change(function() { 

                jQuery.ajax({

                    url: "{{ route('admin.show_talktime_analytics') }}",
                    method: 'get',
                    data: {
                        talkTimeStartDate: $("input[name=talkTimeStartDate]").val(),
                        talkTimeEndDate: $("input[name=talkTimeEndDate]").val(),
                    },

                    success: function(result){
                        
                        $('h4.talkTimeNumber').html('<b>' + result.totalNumber + '</b>').effect( "shake", {distance : 7}, 500 );
                        
                        $('h4.talkTimeCost').html('BDT : <b>' + result.totalCost.toFixed(2) + '</b>').effect( "shake", {distance : 7}, 500 );

                    }
                });

            });

            $("input.earningTable").change(function() { 

                jQuery.ajax({

                    url: "{{ route('admin.show_earnings_analytics') }}",
                    method: 'get',
                    data: {
                        earningStartDate: $("input[name=earningStartDate]").val(),
                        earningEndDate: $("input[name=earningEndDate]").val(),
                    },

                    success: function(result){
                        
                        $('h4.earningAmount').html('BDT : <b>' + result.totalEarning.toFixed(2) + '</b>').effect( "shake", {distance : 7}, 500 );

                    }
                });

            });

            $("input.treasureTable").change(function() { 

                jQuery.ajax({

                    url: "{{ route('admin.show_treasures_analytics') }}",
                    method: 'get',
                    data: {
                        treasureStartDate: $("input[name=treasureStartDate]").val(),
                        treasureEndDate: $("input[name=treasureEndDate]").val(),
                    },

                    success: function(result){
                        
                        $('h4.treasureNumber').html('<b>' + result.totalNumber + '</b>').effect( "shake", {distance : 7}, 500 );
                        
                        $('h4.treasureCost').html('BDT : <b>' + result.totalCost.toFixed(2) + '</b>').effect( "shake", {distance : 7}, 500 );

                    }
                });

            });

            $("input.gemPacksTable").change(function() { 

                jQuery.ajax({

                    url: "{{ route('admin.show_gem_packs_analytics') }}",
                    method: 'get',
                    data: {
                        gemsPackStartDate: $("input[name=gemsPackStartDate]").val(),
                        gemsPackEndDate: $("input[name=gemsPackEndDate]").val(),
                    },

                    success: function(result){
                        
                        
                        $('h4.gemPacksNumber').html('<b>' + result.totalNumber + '</b>').effect( "shake", {distance : 7}, 500 );
                        
                        $('h4.gemPacksCost').html('BDT : <b>' + result.totalCost.toFixed(2) + '</b>').effect( "shake", {distance : 7}, 500 );

                    }

                });

            });


            $('#purchaseTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.show_purchase_analytics') }}",
                columns: [
                    { data: 'item_id', name: 'item_id' },
                    { data: 'buyer_id', name: 'buyer_id' },
                    { data: 'buyer.user.username', name: 'buyer.user.username' },
                    { data: 'buyer.user.phone', name: 'buyer.user.phone' },
                    { data: 'buyer.user.location', name: 'buyer.user.location' },
                    { data: 'gateway_name', name: 'gateway_name' }
                ],
                initComplete : function(settings, json){
                    // console.log(json.data);
                }
            });            

        });

    </script>

@endpush