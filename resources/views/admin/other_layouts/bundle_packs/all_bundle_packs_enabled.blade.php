
@extends('admin.master_layout.app')

@push('extraStyleLink')
    <style type="text/css">
        .fa.fa-eye:hover, .fa.fa-edit:hover, .fa.fa-trash:hover{
            border-radius: 10%;
            background:#b4b4b4;
        }
    </style>
@endpush

@section('contents')

    <div class="card mb-4">
        <div class="card-body">


            <div class="row">
                <div class="col-6">
                    <h3 class="float-left"> Enabled Bundle Packs List </h3>
                </div>

                <div class="col-6">

                    @if(auth()->user()->can('read'))
                    
                    <a href="{{route('admin.view_disabled_bundle_packs')}}" class="btn btn-outline-danger float-right btn-sm mr-1 ml-1" type="button">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                        Disabled Packs
                    </a>

                    @endif

                    @if(auth()->user()->can('create'))

                    <button type="button" class="btn btn-success float-right btn-sm" data-toggle="modal" data-target="#addModal">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        New Bundle Pack
                    </button>

                    @endif
                </div>

            </div>

            <hr>

            <div class="row">
                <div class="col-12 table-responsive">

                    <table class="table table-hover table-striped table-bordered text-center" cellspacing="0" width="100%" id="bundleTable">

                        <thead class="thead-dark">
                            <tr>
                                <th>Bundle Pack Name</th>
                                <th>Prices</th>
                                <th>Offers</th>
                                <th>Bundle Components</th>
                                <th class="actions">Actions</th>
                            </tr>
                        </thead>

                    </table>

                </div>
            </div>

            <div class="row">

                @if(auth()->user()->can('read'))
                <!--- View Modal --->
                <div class="modal fade" id="viewModal" role="dialog">
                    <div class="modal-dialog  modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4> Bundle Pack Details </h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>

                            <div class="modal-body">  
                                <div class="form-row">
                                    <div class="col-md-5">
                                        <label for="validationServerUsername">Name</label>
                                    </div>
                                        
                                    <div class="col-md-7">
                                        <p></p>
                                    </div>
                                </div>

                                
                                <div class="form-row">
                                    <div class="col-md-5">
                                        <label for="validationServerUsername">Description</label>
                                    </div>
                                    <div class="col-md-7">
                                        <p></p>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-5">
                                        <label for="validationServerUsername">Components</label>
                                    </div>
                                    <div class="col-md-7">
                                        <p></p>
                                    </div>
                                </div>
                                

                                <div class="form-row">
                                    <div class="col-md-5">
                                        <label for="validationServer01">Price </label>
                                    </div>
                                    <div class="col-md-7">
                                        <p></p>
                                    </div>
                                </div>

                                <div class="form-row mb-4">
                                    <div class="col-md-5">
                                        <label for="validationServerUsername">Discount</label>
                                    </div>

                                    <div class="col-md-7">
                                        <div>
                                            <span></span>
                                        </div>   
                                        <div class="form-check form-check-inline">
                                            <span></span>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <span></span>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <span></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-sm-12">
                                        <button type="button" class="btn btn-secondary btn-danger" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @endif

                @if(auth()->user()->can('delete'))
                <!-- Delete Modal -->
                <div class="modal fade" id="deleteModal" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Confirmation</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <form method="POST" action="{{-- {{ route('admin.delete_bundle_pack', $bundlePack->id) }} --}}">

                                @method('DELETE')
                                @csrf

                                <div class="modal-body">

                                    <p>You are about to delete.</p> 

                                    <p class="text-muted">This item will be removed to recycle bin.</p>
                                    
                                    <h5>Do you want to proceed ?</h5>

                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Yes</button>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>

                @endif

                @if(auth()->user()->can('create'))
                <!--- Add Modal --->
                <div class="modal fade" id="addModal" role="dialog">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h3> Create Bundle Pack </h3>
                                <button type="button" class="close" data-dismiss="modal">
                                    &times;
                                </button>
                            </div>

                            <div class="modal-body">
                                
                                <form method="post" action= "{{ route('admin.created_bundle_pack_submit') }}" enctype="multipart/form-data">

                                    @csrf


                                    <div class="row">
                                        
                                        <div class="col-md-6">

                                            <h4>Bundle Introduction</h4>

                                            <div class="tile">    
                                                <div class="tile-body">    
                                                    <div class="form-row">
                                                        <div class="col-md-12 mb-4">
                                                            <label for="validationServerUsername">Bundle Name</label>
                                                            <div class="input-group">
                                                                <input type="text" name="name" class="form-control form-control-lg is-valid" placeholder="Unique Name(required)" aria-describedby="inputGroupPrepend3" data-validation='required' data-validation-help='Name has to be unique' data-validation-error-msg='Bundle name is required and unique'>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 mb-4">
                                                            <label for="validationServer01">Type</label>
                                                            <select class="form-control form-control-lg is-valid" name="type" readonly>
                                                                <option value="Bundle">Bundle Pack</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-12 mb-4">
                                                            <label for="validationServerUsername">Description</label>
                                                            <div class="input-group">
                                                                <input type="text" name="description" class="form-control form-control-lg is-valid" placeholder="Short Description" aria-describedby="inputGroupPrepend3">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <h4>Price Details</h4>
                                            <div class="tile">
                                                <div class="tile-body">
                                                    
                                                    <div class="form-row">
                                                        <div class="col-md-12 mb-4">
                                                            <label for="validationServerUsername">Price (taka)</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">@ taka</span>
                                                                </div>
                                                                <input type="text" name="price_taka" class="form-control form-control-lg is-valid" placeholder="(required)" aria-describedby="inputGroupPrepend3" data-validation='required number' data-validation-allowing='float' data-validation-help='Minimun price 0 taka' data-validation-error-msg='Price taka is required'>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 mb-4">
                                                            <label for="validationServerUsername">Price (gems)</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">@ gems</span>
                                                                </div>
                                                                <input type="text" name="price_gems" class="form-control form-control-lg is-valid" placeholder="(required)" aria-describedby="inputGroupPrepend3" data-validation='required number' data-validation-help='Minimum price 0 gem' data-validation-error-msg='Price gem is required and numeric only'>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 mb-4">
                                                            <label for="validationServer01">Price (coins)</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">@ coins</span>
                                                                </div>
                                                                <input type="text" name="price_coins" class="form-control form-control-lg is-valid"  placeholder="(required)" data-validation='required number' data-validation-help='Minimun price 0 coin' data-validation-error-msg='Price coin is required and numeric only'>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col-md-12">
                                            <h4>Component Details</h4>
                                            <div class="tile">
                                                <div class="tile-body">
                                                    <div class="form-row">
                                                        <div class="col-md-6 mb-4">
                                                            <label for="validationServer01">Bundle Component Type</label>
                                                            <select class="form-control form-control-lg is-valid" name="elements[]" data-validation='required' data-validation-error-msg='Please select bundle type'>
                                                                <option selected="true" disabled="true" value="0">
                                                                    -- please select an option --
                                                                </option>
                                                                <option value="Coins Pack">
                                                                    Coins Pack
                                                                </option>
                                                                <option value="Gems Pack">
                                                                    Gems Pack
                                                                </option>
                                                                <option value="Megabyte">
                                                                    Megabyte
                                                                </option>
                                                                
                                                                @foreach(App\Models\BoostPack::all() as $boostPack)
                                                                <option value="{{ $boostPack->name }}">
                                                                    {{ $boostPack->name }}
                                                                </option>
                                                                @endforeach

                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 mb-4">
                                                            <label for="validationServerUsername">Amount</label>
                                                            <div class="input-group">
                                                                <input type="text" name="amount[]" class="form-control form-control-lg is-valid" placeholder="Amount of Component" aria-describedby="inputGroupPrepend3" data-validation='required number'  data-validation-allowing="range[1;10000000]" data-validation-help='Minimum 1' data-validation-error-msg='Amount is required and numeric only'>
                                                            </div>
                                                        </div>
                                                    </div> 
                                                    <div id="addElement"></div>
                                                    <div class="form-row">
                                                        <div class="col-sm-9 mb-4"> </div>

                                                        <div class="col-sm-3 text-right mb-4">
                                                            <i class="fa fa-plus-circle " style="font-size:30px;color:green;" id="addButton"></i>
                                                            <i class="fa fa-minus-circle" style="font-size:30px;color:red;" id="removeButton"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        
                                        <div class="col-md-12">
                                            <h4>Discount Details</h4>
                                            <div class="tile">
                                                <div class="tile-body">
                                                    
                                                    <div class="form-row mb-4">
                                                        <div class="col-md-5">
                                                            <label for="validationServerUsername">Discount</label>
                                                            <div class="input-group">
                                                                <input type="text" name="discount" class="form-control form-control-lg is-valid" placeholder="Discount Percentage" aria-describedby="inputGroupPrepend3"   data-validation='required number' data-validation-allowing='float range[0;100]' data-validation-error-msg='Discount field is required'>
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">%</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1"></div>
                                                        <div class="col-md-2 col-4">    
                                                            <div class="form-check form-check-inline mt-5">
                                                                <input name="discount_type[]" class="form-check-input" type="checkbox" value="taka">
                                                                <label class="form-check-label" for="inlineCheckbox1">@ taka</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2 col-4">
                                                            <div class="form-check form-check-inline mt-5">
                                                                <input name="discount_type[]" class="form-check-input" type="checkbox" value="gems">
                                                                <label class="form-check-label" for="inlineCheckbox1">@ gems</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2 col-4">
                                                            <div class="form-check form-check-inline mt-5">
                                                                <input name="discount_type[]" class="form-check-input" type="checkbox" value="coins">
                                                                <label class="form-check-label" for="inlineCheckbox1">@ coins</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                  

                                    <br>

                                    <div class="form-row">
                                        <div class="col-sm-12">
                                            <button type="submit" class="btn btn-lg btn-block btn-primary">Create</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                @endif

            </div>

            <!--- Edit Modal --->
        {{-- 
            <div class="modal fade" id="editModal{{$bundlePack->id}}" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h3> Edit Bundle Pack </h3>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <div class="modal-body">
                            
                            <form method="post" action= "{{ route('admin.updated_bundle_pack_submit', $bundlePack->id) }}" enctype="multipart/form-data">

                                @csrf
                                @method('PUT')

                                <div class="form-row mb-4">
                                    <div class="col-md-4">
                                        <label for="validationServerUsername">Bundle Name</label>
                                        <div class="input-group">
                                            <input type="text" name="name" class="form-control form-control-lg is-valid" value="{{ $bundlePack->name }}" aria-describedby="inputGroupPrepend3">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="validationServer01">Type</label>
                                        <select class="form-control form-control-lg is-valid" name="type" readonly>
                                            <option value="Bundle">Bundle Pack</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="validationServerUsername">Description</label>
                                        <div class="input-group">
                                            <input type="text" name="description" class="form-control form-control-lg is-valid" value="{{ $bundlePack->description }}" aria-describedby="inputGroupPrepend3">
                                        </div>
                                    </div>
                                </div>

                                @foreach($bundlePack->bundleComponents as $component)

                                <div class="form-row mb-4 newAdded">
                                    <div class="col-md-6">
                                        <label for="validationServer01">Bundle Component Type</label>
                                        <select class="form-control form-control-lg is-valid" name="elements[]" required="true">
                                            <option selected="true" disabled="true" value="0">
                                                -- please select an option --
                                            </option>
                                            <option value="Coins Pack" @if($component->component_type=='Coins Pack') selected="true" @endif>
                                                Coins Pack
                                            </option>
                                            <option value="Gems Pack" @if($component->component_type=='Gems Pack') selected="true" @endif>
                                                Gems Pack
                                            </option>

                                            <option value="Megabyte" @if($component->component_type=='Megabyte') selected="true" @endif>
                                                Megabyte
                                            </option>

                                            @foreach(App\Models\BoostPack::all() as $boostPack)
                                                <option value="{{ $boostPack->name }}"  @if($boostPack->name == $component->component_type) selected="true" @endif>
                                                    {{ $boostPack->name }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="validationServerUsername">Amount</label>
                                        <div class="input-group">
                                            <input type="number" name="amount[]" class="form-control form-control-lg is-valid" value="{{ $component->amount }}" aria-describedby="inputGroupPrepend3" required="true" min='1'>
                                        </div>
                                    </div>
                                </div>

                                @endforeach

                                <div id="addElement"></div>

                                <div class="form-row mb-4">
                                    <div class="col-sm-9"> </div>

                                    <div class="col-sm-3 text-right">
                                        <i class="fa fa-plus-circle " style="font-size:30px;color:green;" id="addButton"></i>
                                        <i class="fa fa-minus-circle" style="font-size:30px;color:red;" id="removeButton"></i>
                                    </div>
                                </div>
                                
                                <div class="form-row mb-4">
                                    <div class="col-md-4">
                                        <label for="validationServerUsername">Taka Discount</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">@ taka</span>
                                            </div>
                                            <input type="number" name="discount_taka" class="form-control form-control-lg is-valid" value="{{ $bundlePack->discount_taka }}" step="any" required="true" min="0" max="100">
                                            <div class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="validationServer01">
                                            Gems Discount
                                        </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">@ gems</span>
                                            </div>
                                            <input type="number" name="discount_gems" class="form-control form-control-lg is-valid"  value="{{ $bundlePack->discount_gems }}" step="any" required="true" min="0" max="100">
                                            <div class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="validationServer01">Coins Discount</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">@ coins</span>
                                            </div>
                                            <input type="number" name="discount_coins" class="form-control form-control-lg is-valid"  value="{{ $bundlePack->discount_coins }}" step="any" required="true" min="0" max="100">
                                            <div class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                              
                                <div class="form-row mb-4">
                                    <div class="col-md-4">
                                        <label for="validationServerUsername">Price (taka)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">@ taka</span>
                                            </div>
                                            <input type="number" name="price_taka" class="form-control form-control-lg is-valid" value="{{ $bundlePack->price_taka }}" aria-describedby="inputGroupPrepend3" required="true">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="validationServerUsername">Price (gems)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">@ gems</span>
                                            </div>
                                            <input type="number" name="price_gems" class="form-control form-control-lg is-valid" value="{{ $bundlePack->price_gems }}" aria-describedby="inputGroupPrepend3" required="true">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="validationServer01">Price (coins)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">@ coins</span>
                                            </div>
                                            <input type="number" name="price_coins" class="form-control form-control-lg is-valid"  value="{{ $bundlePack->price_coins }}" required="true">
                                        </div>
                                    </div>
                                </div>

                                <br>

                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-lg btn-block btn-primary">Update</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div> 
                    --}}
        </div>
    </div>
@stop

@push('scripts')

    <script> 

        $(document).ready(function(){

            @if(auth()->user()->can('create'))

            $("#addButton").click(function(){
                var html = "<div class='form-row newAdded'>"+
                                "<div class='col-md-6 mb-4'>"+
                                    "<label for='validationServer01'>Bundle Component Type</label>"+
                                    "<select class='form-control form-control-lg is-valid' name='elements[]' data-validation='required' data-validation-error-msg='Please select bundle type'>"+
                                        "<option value='0' selected disabled>"+
                                            "--please select an option--"+
                                        "</option>"+
                                        "<option value='Coins Pack'>"+
                                            "Coins Pack"+
                                        "</option>"+
                                        "<option value='Gems Pack'>"+
                                            "Gems Pack"+
                                        "</option>"+

                                        "<option value='Megabyte'>"+
                                            "Megabyte"+
                                        "</option>"+

                                        "@foreach(App\Models\BoostPack::all() as $boostPack)"+
                                        "<option value='{{ $boostPack->name }}'>"+
                                            "{{ $boostPack->name }}"+
                                        "</option>"+
                                        "@endforeach"+

                                    "</select>"+
                                "</div>"+
                                "<div class='col-md-6 mb-4'>"+
                                    "<label for='validationServerUsername'>Amount</label>"+
                                    "<div class='input-group'>"+
                                        "<input type='text' name='amount[]' class='form-control form-control-lg is-valid' placeholder='Amount of Component' aria-describedby='inputGroupPrepend3'  data-validation='required number'  data-validation-allowing='range[1;10000000]' data-validation-help='Minimum 1' data-validation-error-msg='Amount is required and numeric only'>"+
                                    "</div>"+
                                "</div>"+
                            "</div>";

                $("#addElement").append(html);
            }); 

            $("#removeButton").click(function(){
                $(".newAdded:last").remove();
            });

            @endif


            var globalVariable = '';
            
            $('#bundleTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.view_enabled_bundle_packs') }}',
                columns: [
                    { data: 'name', name: 'name' },
                    {
                        data: function (data){
                            return data.price_taka + " (taka) / <br>" + data.price_coins + " (taka) / <br>" + data.price_gems + " (gems) / <br>";
                        }, 
                        name: 'price_taka' 
                    },
                    { 
                        data: function (data){
                            return Math.max(data.discount_taka, data.discount_coins, data.discount_gems);
                        }, 
                        name: 'discount_taka'
                    },
                    {
                        data: function (data){

                            var bundleComponents = '';

                            for(var i=0; i < data.bundle_components.length; i++){

                                bundleComponents = bundleComponents.concat(data.bundle_components[i].component_type + "(" + data.bundle_components[i].amount + ") <br/>");
                            }

                            return bundleComponents;
                        }, 
                        name: 'name',
                        orderable: false
                    },
                    { data: 'action', name: 'action' , orderable: false}
                ],
                drawCallback: function( settings ){
                    var api = this.api();
                    var json = api.ajax.json();

                    globalVariable = json.data;
                }
            }); 

            $('#bundleTable').on( 'draw.dt', function () {

                @if(auth()->user()->can('read'))

                $(".fa-eye").click(function() {

                    // console.log(globalVariable);
                    
                    var clickedRow = $(this).closest("tr");
                    var clickedRowId = clickedRow.attr('id');
                    var expectedObject = globalVariable.find( x => x.id === clickedRowId );

                    $( "#viewModal p:eq(0)" ).html( expectedObject.name );
                    $( "#viewModal p:eq(1)" ).html( expectedObject.description );
                    
                    $( "#viewModal p:eq(2)" ).empty();
                    for(var i=0; i < expectedObject.bundle_components.length; i++){
                        
                        $( "#viewModal p:eq(2)" ).append(expectedObject.bundle_components[i].component_type +' ('+ expectedObject.bundle_components[i].amount +'), <br/>');
                    }

                    $( "#viewModal p:eq(3)" ).html( expectedObject.price_taka +' taka /<br/>'+ expectedObject.price_gems +' gems /<br/>'+ expectedObject.price_coins +' coins');
                    
                    $( "#viewModal span:eq(0)" ).html( Math.max(expectedObject.discount_taka, expectedObject.discount_gems, expectedObject.discount_coins) + ' %' );

                    if (expectedObject.discount_taka) {
                        $( "#viewModal span:eq(1)" ).html( 'taka,' );
                    }
                    else{
                        $( "#viewModal span:eq(1)" ).empty(); 
                    }

                    if (expectedObject.discount_gems) {
                        $( "#viewModal span:eq(2)" ).html( 'gems,' );
                    }
                    else{
                        $( "#viewModal span:eq(2)" ).empty(); 
                    }

                    if (expectedObject.discount_coins) {
                        $( "#viewModal span:eq(3)" ).html( 'coins' );
                    }
                    else{
                        $( "#viewModal span:eq(3)" ).empty(); 
                    }

                    $('#viewModal').modal('toggle');
                });

                @endif

                @if(auth()->user()->can('delete'))
                
                $(".fa-trash").click(function() {

                    var clickedRow = $(this).closest("tr");
                    var clickedRowId = clickedRow.attr('id');
                    var expectedObject = globalVariable.find( x => x.id === clickedRowId );

                    var home = "{{ URL::to('/') }}";

                    $("#deleteModal form").attr("action", home + '/admin/bundle-pack/' +  expectedObject.id );

                    $('#deleteModal').modal('toggle');
                });

                @endif
            });

        });    
    </script>

@endpush