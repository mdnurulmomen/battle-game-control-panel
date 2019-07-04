
@extends('admin.master_layout.app')
@section('contents')

    <div class="card mb-4">
        <div class="card-body">
            
            <div class="row">
                <div class="col-6">
                    <h3 class="float-left"> Enabled Subscription Packages List </h3>
                </div>

                <div class="col-6">
                    <a  href="{{route('admin.view_disabled_subscription_packages')}}"  class="btn btn-outline-danger float-right" type="button">
                        Disabled Packages
                    </a>

                    <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#addModal">
                        New Package
                    </button>
                </div>

            </div>

            <hr>

            <div class="row">

                <!--- View Modal --->
                <div class="modal fade" id="viewModal" role="dialog">
                    <div class="modal-dialog  modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4> Subscription-pack Details </h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>

                            <div class="modal-body">  
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <label for="validationServerUsername">Name</label>
                                    </div>
                                        
                                    <div class="col-md-6">
                                        <p></p>
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <label for="validationServerUsername">Type</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p></p>
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <label for="validationServer01">Price </label>
                                    </div>
                                    <div class="col-md-6">
                                        <p></p>
                                    </div>
                                </div>

                                <div class="form-row mb-5">
                                    <div class="col-md-6">
                                        <label for="validationServer01">Time Offered </label>
                                    </div>
                                    <div class="col-md-6">
                                        <p></p>
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

                <!--- Create Modal --->
                <div class="modal fade" id="addModal" role="dialog">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h3> Create Package </h3>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>

                            <div class="modal-body">
                                
                                <form method="post" action= "{{ route('admin.created_subscription_package_submit') }}" enctype="multipart/form-data">
                                                    
                                    @csrf

                                    <div class="form-row">
                                        <div class="col-md-6 mb-4">
                                            <label for="validationServer01">Package Type</label>

                                            <select class="form-control form-control-lg is-invalid" name="subcription_package_type_id" required="true">
                                                
                                                @foreach(App\Models\SubscriptionPackageType::all() as $packageType)
                                                <option value="{{ $packageType->id }}">
                                                    {{ $packageType->name }}
                                                </option>
                                                @endforeach

                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-4">
                                            <label for="validationServer01">Package Name </label>
                                            <div class="input-group">
                                                <input type="text" name="name" class="form-control form-control-lg is-valid"  placeholder="Package Name">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-row">
                                        <div class="col-md-6 mb-4 offered_time">
                                            <label for="validationServer01">Offered Hour </label>
                                            <div class="input-group">
                                                <input step="1" type="number" name="offered_time" class="form-control form-control-lg is-valid" placeholder="Free Hours" min="0">
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-4 d-none offered_game">
                                            <label for="validationServer01">Offered Game </label>
                                            <div class="input-group">
                                                <input step="1" type="number" name="offered_game" class="form-control form-control-lg is-valid"  placeholder="Free Match" min="0">
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-4">
                                            <label for="validationServer01">Price (gems)</label>
                                            <div class="input-group">
                                                <input step="1" type="number" name="price_gem" class="form-control form-control-lg is-invalid"  placeholder="Equivalent Gem Price" min="0">
                                            </div>
                                        </div>                                
                                    </div>

                                    <br>

                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <button type="submit" class="btn btn-lg btn-block btn-primary">
                                                Create
                                            </button>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Delete Modal -->                       
                <div class="modal fade" id="deleteModal" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Confirmation</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>

                            <form method="POST" action="">

                                @method('DELETE')
                                @csrf
                                
                                <div class="modal-body">
                                    <p>Are You Sure ??</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Yes</button>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
                

                <div class="modal fade" id="editModal" role="dialog">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h3> Edit Package </h3>
                                <button type="button" class="close" data-dismiss="modal">
                                    &times;
                                </button>
                            </div>

                            <div class="modal-body">
                                
                                <form method="post" action= "" enctype="multipart/form-data">
                                    
                                    @csrf
                                    @method('PUT')

                                    <div class="form-row">
                                        <div class="col-md-6 mb-4">
                                            <label for="validationServer01">Package Type</label>

                                            <select class="form-control form-control-lg is-invalid" name="subcription_package_type_id" required="true">
                                                
                                                @foreach(App\Models\SubscriptionPackageType::all() as $packageType)
                                                <option value="{{ $packageType->id }}"  selected="true">
                                                    {{ $packageType->name }}
                                                </option>
                                                @endforeach

                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-4">
                                            <label for="validationServer01">Package Name </label>
                                            <div class="input-group">
                                                <input type="text" name="name" class="form-control form-control-lg is-valid"  value="">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-row">
                                        <div class="col-md-6 mb-4 offered_time">
                                            <label for="validationServer01">Offered Hour </label>
                                            <div class="input-group">
                                                <input step="1" type="number" name="offered_time" class="form-control form-control-lg is-valid"  value="" min="0">
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-4 offered_game  d-none">
                                            <label for="validationServer01">Offered Game </label>
                                            <div class="input-group">
                                                <input step="1" type="number" name="offered_game" class="form-control form-control-lg is-valid"  value="" min="0">
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-4">
                                            <label for="validationServer01">Price (gems) </label>
                                            <div class="input-group">
                                                <input step="1" type="number" name="price_gem" class="form-control form-control-lg is-invalid" value="" min="0">
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

            </div>

            <div class="row">
                <div class="col-12 table-responsive">

                    <table class="table table-hover table-striped table-bordered text-center" cellspacing="0" width="100%" id="packageTable">

                        <thead class="thead-dark">
                            <tr>
                                <th>Package Serial</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Cost(gems)</th>
                                <th class="actions">Actions</th>
                            </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
    

@push('scripts')

    <script type="text/javascript">

        $( document ).ready(function() {

            $( "select" ).change(function() {

                var str = $("option:selected", this).text();
                
                // alert( str );

                if (str.includes("Hour")) {

                    // alert('Hour');
                    $(".offered_game").hide();
                    $(".offered_time").show();
                    $(".offered_time").removeClass('d-none');
                }
                else{
                    
                    // alert('Game');
                    $(".offered_time").hide();
                    $(".offered_game").show();
                    $(".offered_game").removeClass('d-none');
                }
              
            });

            var globalVariable = 0;

            $('#packageTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.view_enabled_subscription_packages') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    
                    { data: 'name', name: 'name' },

                    { data: 'subscription_package_type.name', name: 'subcription_package_type_id' },
                    
                    { data: 'price_gem', name: 'price_gem' },

                    { data: 'action', name: 'action', orderable: false }
                ],
                drawCallback: function( settings ) {
                    
                    var api = this.api();
                    // var data = api.rows( {page:'current'} ).data();
                    var jsonData = api.ajax.json();

                    globalVariable = jsonData.data;

                    console.log(globalVariable);
                }
            });

            $('#packageTable').on( 'draw.dt', function () {
                
                $(".fa-eye").click(function() {

                    var clickedRow = $(this).closest("tr");
                    var clickedRowId = clickedRow.attr('id');
                    var expectedObject = globalVariable.find( x => x.id === clickedRowId );

                    $( "#viewModal p:eq(0)" ).html( expectedObject.name );
                    $( "#viewModal p:eq(1)" ).html( expectedObject.subscription_package_type.name );
                    $( "#viewModal p:eq(2)" ).html( expectedObject.price_gem +' gems ');
                    $( "#viewModal p:eq(3)" ).html( expectedObject.offered_time +' Hours ');
                    

                    $('#viewModal').modal('toggle');
                });

                $(".fa-edit").click(function() {

                    var clickedRow = $(this).closest("tr");
                    var clickedRowId = clickedRow.attr('id');
                    var expectedObject = globalVariable.find( x => x.id === clickedRowId );

                    var home = "{{ URL::to('/') }}";

                    $("#editModal form").attr("action", home + '/admin/boost-packs/' +  expectedObject.id );

                    $( "#editModal input[name*='name']" ).val( expectedObject.name );
                    $( "#editModal input[name*='amount']" ).val( expectedObject.amount );
                    $( "#editModal input[name*='description']" ).val( expectedObject.description );
                    $( "#editModal input[name*='price_taka']" ).val( expectedObject.price_taka );
                    $( "#editModal input[name*='price_gems']" ).val( expectedObject.price_gems );
                    $( "#editModal input[name*='price_coins']" ).val( expectedObject.price_coins );

                    $( "#editModal input[name='discount']" ).val( Math.max(expectedObject.discount_taka, expectedObject.discount_gems, expectedObject.discount_coins) );


                    if (expectedObject.discount_taka) {
                        
                        $("#editModal input[name='discount_type[]']:eq(0)").prop('checked', true);
                    }
                    else{
                        $("#editModal input[name='discount_type[]']:eq(0)").prop("checked", false);
                    }

                    if (expectedObject.discount_gems) {

                        $("#editModal input[name='discount_type[]']:eq(1)").prop('checked', true);
                    }
                    else{
                        $("#editModal input[name='discount_type[]']:eq(1)").prop("checked", false);
                    }

                    if (expectedObject.discount_coins) {

                        $("#editModal input:checkbox[name='discount_type[]']:eq(2)").prop('checked', true);
                    }
                    else{
                        $("#editModal input:checkbox[name='discount_type[]']:eq(2)").prop("checked", false);
                    }

                    $('#editModal').modal('toggle');
                });

                $(".fa-trash").click(function() {

                    var clickedRow = $(this).closest("tr");
                    var clickedRowId = clickedRow.attr('id');
                    var expectedObject = globalVariable.find( x => x.id === clickedRowId );

                    var home = "{{ URL::to('/') }}";

                    $("#deleteModal form").attr("action", home + '/admin/subscription-package/' +  expectedObject.id );

                    $('#deleteModal').modal('toggle');
                });
            });  
        });

   </script>

@endpush