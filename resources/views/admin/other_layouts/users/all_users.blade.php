
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
                        <h3 class="float-left"> Login Users List </h3>
                    </div>


                    <div class="col-6">    

                        @if(auth()->user()->can('setting'))
                        
                        <button type="button" class="btn btn-success float-right btn-sm" data-toggle="modal" data-target="#addModal">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                            New User
                        </button>
                        
                        @endif
                    
                    </div>


                </div>

                <hr>

                <div class="row">


                    @if(auth()->user()->can('read'))
                    <!--- View Modal --->
                    <div class="modal fade" id="viewModal" role="dialog">
                        <div class="modal-dialog  modal-sm">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4> User Details </h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <div class="modal-body"> 


                                    <fieldset>
                                        <div class="form-row mb-2">
                                            <div class="col-md-12 text-center">
                                                <img src='' class='rounded-circle rounded img-fluid img-responsive' alt='No image' style='max-width: 90px;'>
                                            </div>
                                        </div>
                                    </fieldset>


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
                                            <label for="validationServerUsername">Username</label>
                                        </div>
                                        <div class="col-md-7">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-5">
                                            <label for="validationServer01">Status </label>
                                        </div>
                                        <div class="col-md-7">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-5">
                                            <label for="validationServer01">Email </label>
                                        </div>
                                        <div class="col-md-7">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-5">
                                            <label for="validationServer01">Phone </label>
                                        </div>
                                        <div class="col-md-7">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-5">
                                            <label for="validationServer01">Address </label>
                                        </div>
                                        <div class="col-md-7">
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

                    @endif


                    @if(auth()->user()->can('setting'))
                    <!--- Edit Modal --->
                    <div class="modal fade" id="editModal" role="dialog">
                        
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h3> Edit User </h3>
                                    <button type="button" class="close" data-dismiss="modal">
                                        &times;
                                    </button>
                                </div>

                                <div class="modal-body">
                                    
                                    <form method="post" action= "" enctype="multipart/form-data">

                                        @csrf
                                        @method('put')

                                        <div class="row">  
                                            <div class="col-6">
                                                <h4 class="tile-title">Basic Details</h4>
                                                <div class="tile">
                                                    <div class="tile-body">   
                                                        <div class="form-row">
                                                            <div class="col-md-12 mb-4">
                                                                <label for="validationServer01">First name</label>
                                                                <input type="text" name="firstname" class="form-control form-control-lg is-valid" value="">
                                                            </div>
                                                        </div>

                                                        <div class="form-row">
                                                            <div class="col-md-12 mb-4">
                                                                <label for="validationServer02">Last name</label>
                                                                <input type="text" name="lastname" class="form-control form-control-lg is-valid" value="">
                                                            </div>
                                                        </div>

                                                        <div class="form-row">
                                                            <div class="col-md-12 mb-4">
                                                                <label for="validationServer01">Phone</label>
                                                                <input type="tel" name="phone" class="form-control form-control-lg is-valid" value="" placeholder="Phone Number" data-validation="number length"  data-validation-optional="true" data-validation-length="max11" data-validation-error-msg="Please input correct phone number">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <h4 class="tile-title">Profile Details</h4>
                                                <div class="tile">
                                                    <div class="tile-body">

                                                        <div class="form-row">
                                                            <div class="col-md-12 mb-4">
                                                                <label for="validationServer01">Role</label>

                                                                <select class="form-control form-control-lg" name="role" data-validation="required" data-validation-error-msg="Please select role for user">
                                                                    <option value="">--Please select user role--</option>
                                                                    <option value="admin">Admin</option>
                                                                    <option value="moderator">Moderator</option>
                                                                    <option value="viewer">Viewer</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="form-row">
                                                            <div class="col-md-12 mb-4">
                                                                <label for="validationServer01">Picture</label>
                                                                <input type="file" name="picture" class="form-control form-control-lg " accept="image/*" data-validation="mime size" data-validation-allowing="jpg,jpeg,png" data-validation-max-size="5M">
                                                            </div>
                                                        </div>

                                                        <div class="form-row">
                                                            <div class="col-md-12 mb-4">
                                                                <label for="validationServer01">Status</label>

                                                                <select class="form-control form-control-lg" name="active" data-validation="required" data-validation-error-msg="Please define user status">
                                                                    <option value="">--Please define user status--</option>
                                                                    <option value="1">Activated</option>
                                                                    <option value="0">Deactivated</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                    </div>
                    
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">

                                            <div class="col-6">
                                                <h4>Login Details</h4>
                                                <div class="tile">
                                                    <div class="tile-body">
                                                        <div class="form-row">
                                                            <div class="col-md-12 mb-4">
                                                                <label for="validationServer01">Email</label>
                                                                <input type="text" name="email" class="form-control form-control-lg is-valid" value="" data-validation="email required" data-validation-error-msg="Please input valid email">
                                                            </div>
                                                        </div>    

                                                        <div class="form-row">
                                                            <div class="col-md-12 mb-4">
                                                                <label for="validationServerUsername">Username</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text">@</span>
                                                                    </div>
                                                                    <input type="text" name="username" class="form-control form-control-lg is-valid"  value="" placeholder="Username"  aria-describedby="inputGroupPrepend3" data-validation="alphanumeric length" data-validation-length="5-20" data-validation-allowing="-_" data-validation-error-msg="No space or special character in minimum 5 characters username">

                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-row">
                                                            <div class="col-md-12 mb-4">
                                                                <label for="validationServer01">Password</label>
                                                                <input type="password" name="password" class="form-control form-control-lg is-valid" readonly="true">
                                                            </div>
                                                        </div> 

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <h4>Address Details</h4>
                                                <div class="tile">
                                                    <div class="tile-body">
                                                        <div class="form-row">
                                                            <div class="col-md-12 mb-4">
                                                                <label for="validationServer02">Address</label>
                                                                <input type="text" name="address" class="form-control form-control-lg is-valid"    value="">
                                                            </div>
                                                        </div>    

                                                        <div class="form-row">
                                                            <div class="col-md-12 mb-4">
                                                                
                                                                <label for="validationServer03">City</label>
                                                                <input type="text" name="city" class="form-control form-control-lg is-valid"   value="">
                                                                
                                                            </div>
                                                        </div>

                                                        <div class="form-row">
                                                            <div class="col-md-12 mb-4">
                                                                <label for="validationServer05">Country</label>
                                                                <input type="text" name="country" class="form-control form-control-lg is-valid" value="">
                                                            </div>
                                                        </div>
                                                    </div>
                    
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

                    @endif

                    @if(auth()->user()->can('setting'))
                    <!--Delete Modal -->
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
                                        <p>You are about to delete an user.</p> 
                                        <p class="text-muted">This action cannot be undone.</p>
                                        
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


                    @if(auth()->user()->can('setting'))

                    <div class="modal fade" id="addModal" role="dialog">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h3> Create User </h3>
                                    <button type="button" class="close" data-dismiss="modal">
                                        &times;
                                    </button>
                                </div>

                                <div class="modal-body">
                                    
                                    <form method="POST" action = "{{ route('admin.created_user_submit') }}" enctype="multipart/form-data">

                                        @csrf

                                        <div class="row">  
                                            <div class="col-6">
                                                <h4 class="tile-title">Basic Details</h4>
                                                <div class="tile">
                                                    <div class="tile-body">   
                                                        <div class="form-row">
                                                            <div class="col-md-12 mb-4">
                                                                <label for="validationServer01">First name</label>
                                                                <input type="text" name="firstname" class="form-control form-control-lg is-valid"  placeholder="First Name">
                                                            </div>
                                                        </div>

                                                        <div class="form-row">
                                                            <div class="col-md-12 mb-4">
                                                                <label for="validationServer02">Last name</label>
                                                                <input type="text" name="lastname" class="form-control form-control-lg is-valid"  placeholder="Last Name">
                                                            </div>
                                                        </div>

                                                        <div class="form-row">
                                                            <div class="col-md-12 mb-4">
                                                                <label for="validationServer01">Phone</label>
                                                                <input type="tel" name="phone" class="form-control form-control-lg is-valid"  placeholder="Phone Number" data-validation="number length"  data-validation-optional="true" data-validation-length="max11" data-validation-error-msg="Please input correct phone number">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <h4 class="tile-title">Profile Details</h4>
                                                <div class="tile">
                                                    <div class="tile-body">

                                                        <div class="form-row">
                                                            <div class="col-md-12 mb-4">
                                                                <label for="validationServer01">Role</label>

                                                                <select class="form-control form-control-lg" name="role" data-validation="required" data-validation-error-msg="Please select role for user">
                                                                    <option value="">--Please select user role--</option>
                                                                    <option value="admin">Admin</option>
                                                                    <option value="moderator">Moderator</option>
                                                                    <option value="viewer">Viewer</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="form-row">
                                                            <div class="col-md-12 mb-4">
                                                                <label for="validationServer01">Picture</label>
                                                                <input type="file" name="picture" class="form-control form-control-lg " accept="image/*" data-validation="mime size" data-validation-allowing="jpg,jpeg,png" data-validation-max-size="5M">
                                                            </div>
                                                        </div>

                                                        <div class="form-row">
                                                            <div class="col-md-12 mb-4">
                                                                <label for="validationServer01">Status</label>

                                                                <select class="form-control form-control-lg" name="active" data-validation="required" data-validation-error-msg="Please define user status">
                                                                    <option value="">--Please define user status--</option>
                                                                    <option value="1">Activated</option>
                                                                    <option value="0">Deactivated</option>
                                                                </select>

                                                            </div>
                                                        </div>

                                                    </div>
                    
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">

                                            <div class="col-6">
                                                <h4>Login Details</h4>
                                                <div class="tile">
                                                    <div class="tile-body">
                                                        <div class="form-row">
                                                            <div class="col-md-12 mb-4">
                                                                <label for="validationServer01">Email</label>
                                                                <input type="text" name="email" class="form-control form-control-lg is-valid" placeholder="Email" data-validation="email required" data-validation-error-msg="Please input valid email">
                                                            </div>
                                                        </div>    

                                                        <div class="form-row">
                                                            <div class="col-md-12 mb-4">
                                                                <label for="validationServerUsername">Username</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text">@</span>
                                                                    </div>
                                                                    <input type="text" name="username" class="form-control form-control-lg is-valid" placeholder="Username"  aria-describedby="inputGroupPrepend3" data-validation="alphanumeric length" data-validation-length="5-20" data-validation-allowing="-_" data-validation-error-msg="No space or special character in minimum 5 characters username">

                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-row">
                                                            <div class="col-md-12 mb-4">
                                                                <label for="validationServer01">Password</label>
                                                                <input type="password" name="password" class="form-control form-control-lg is-valid" placeholder="Chosse a Suitable Password" data-validation="strength length" data-validation-strength="2"data-validation-length="8-20" data-validation-error-msg='Please use at one special character in 8 digit(min)'>
                                                            </div>
                                                        </div>
                                                    </div>
                    
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <h4>Address Details</h4>
                                                <div class="tile">
                                                    <div class="tile-body">
                                                        <div class="form-row">
                                                            <div class="col-md-12 mb-4">
                                                                <label for="validationServer02">Address</label>
                                                                <input type="text" name="address" class="form-control form-control-lg is-valid"  placeholder="Address">
                                                            </div>
                                                        </div>    

                                                        <div class="form-row">
                                                            <div class="col-md-12 mb-4">
                                                                
                                                                <label for="validationServer03">City</label>
                                                                <input type="text" name="city" class="form-control form-control-lg is-valid" placeholder="City">
                                                                
                                                            </div>
                                                        </div>

                                                        <div class="form-row">
                                                            <div class="col-md-12 mb-4">
                                                                <label for="validationServer05">Country</label>
                                                                <input type="text" name="country" class="form-control form-control-lg is-valid" placeholder="Country Name">
                                                            </div>
                                                        </div>
                                                    </div>
                    
                                                </div>
                                            </div>

                                        </div>

                                        <br>
                                        
                                        <div class="form-group row">
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

                <div class="row">
                    <div class="col-12 table-responsive">

                        <table class="table table-hover table-striped table-bordered text-center" cellspacing="0" width="100%" id="users-table">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Username </th>
                                    <th>Email</th>
                                    <th>Phone</th>
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
    <script>
        $(function() {

            var globalVariable ='';

            $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.view_users') }}',
                columns: [
                    { data: 'username', name: 'username' },
                    { data: 'email', name: 'email' },
                    { data: 'phone', name: 'phone' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                drawCallback:function(settings, json){

                    var api = this.api();
                    var json = api.ajax.json();
                    globalVariable = json.data;
                    // console.log(globalVariable);
                }
            });


            $('#users-table').on( 'draw.dt', function () {

                @if(auth()->user()->can('read'))

                $(".fa-eye").click(function() {

                    var clickedRow = $(this).closest("tr");
                    var clickedRowId = clickedRow.attr('id');
                    var expectedObject = globalVariable.find( x => x.id === clickedRowId );

                    var imageUrl = '{{ URL::asset('assets/admin/images/profile/') }}';

                    $("#viewModal img").attr("src", imageUrl +"/"+ expectedObject.picture);

                    $( "#viewModal p:eq(0)" ).html( expectedObject.firstname +' '+ expectedObject.lastname );
                    $( "#viewModal p:eq(1)" ).html( expectedObject.username );
                    
                    if (expectedObject.active === '1')
                        $( "#viewModal p:eq(2)" ).html( 'active' );
                    
                    else
                        $( "#viewModal p:eq(2)" ).html( 'Deactive' );

                    $( "#viewModal p:eq(3)" ).html( expectedObject.email );
                    $( "#viewModal p:eq(4)" ).html( expectedObject.phone );
                    $( "#viewModal p:eq(5)" ).html( expectedObject.address +",  "+ expectedObject.city +", "+ expectedObject.country);

                    $('#viewModal').modal('toggle');
                });

                @endif

                @if(auth()->user()->can('setting'))

                $(".fa-edit").click(function() {

                    var clickedRow = $(this).closest("tr");
                    var clickedRowId = clickedRow.attr('id');
                    var expectedObject = globalVariable.find( x => x.id === clickedRowId );

                    // console.log(expectedObject);

                    var home = "{{ URL::to('/') }}";

                    $("#editModal form").attr("action", home + '/admin/users/' +  expectedObject.id );

                    $( "#editModal input[name*='firstname']" ).val( expectedObject.firstname );
                    $( "#editModal input[name*='lastname']" ).val( expectedObject.lastname );
                    $( "#editModal input[name*='phone']" ).val( expectedObject.phone );

                    // console.log(expectedObject.role);

                    $("#editModal select[name='role']").val(expectedObject.role);
                    $("#editModal select[name='active']").val(expectedObject.active);

                    $( "#editModal input[name*='email']" ).val( expectedObject.email );
                    $( "#editModal input[name*='username']" ).val( expectedObject.username );
                    
                    $( "#editModal input[name*='address']" ).val( expectedObject.address );
                    $( "#editModal input[name*='city']" ).val( expectedObject.city );
                    $( "#editModal input[name*='country']" ).val( expectedObject.country );
                    

                    $('#editModal').modal('toggle');
                });

                @endif

                @if(auth()->user()->can('setting'))

                $(".fa-trash").click(function() {

                    var clickedRow = $(this).closest("tr");
                    var clickedRowId = clickedRow.attr('id');
                    var expectedObject = globalVariable.find( x => x.id === clickedRowId );

                    var home = "{{ URL::to('/') }}";

                    $("#deleteModal form").attr("action", home + '/admin/users/' +  expectedObject.id );

                    if (expectedObject.id == {{auth()->user()->id}}) {

                        $( "#deleteModal .modal-body p" ).first().html( 'You are about to delete <b>Your Account</b>.' );
                    }else {
                        $( "#deleteModal .modal-body p" ).first().html( 'You are about to delete an user.' );
                    }

                    $('#deleteModal').modal('toggle');
                });

                @endif
                
            });


        });
    </script>
@endpush