
@extends('admin.master_layout.app')
@section('contents')

    <div class="card mb-4">
        <div class="card-body">
            
            <div class="row">
                <div class="col-6">
                    <h3 class="float-left">Enabled Mission Types List </h3>
                </div>


                <div class="col-6">

                    @if(auth()->user()->can('read'))

                    <a  href="{{route('admin.view_disabled_mission_types')}}"  class="btn btn-outline-danger float-right btn-sm mr-1 ml-1" type="button">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                        Disabled Types
                    </a>

                    @endif

                    {{-- 

                    @if(auth()->user()->can('create'))

                    <button type="button" class="btn btn-success float-right btn-sm" data-toggle="modal" data-target="#addMissionType">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        New Mission Type
                    </button>

                    @endif 

                    --}}
                    
                </div>

            </div>

            <hr>

            <div class="row">
                <div class="col-12 table-responsive">

                    <table class="table table-hover table-striped table-bordered text-center" cellspacing="0" width="100%">

                        <thead class="thead-dark">
                            <tr>
                                <th>Type Serial</th>
                                <th>Type Name</th>

                                @if(auth()->user()->can('update'))

                                <th class="actions">Actions</th>
                                
                                @endif

                            </tr>
                        </thead>
                        
                        <tbody>

                        @if($missionTypes->isEmpty())
                            <tr class="danger">
                                <td class="text-danger" colspan='5'>No Data Found</td>
                            </tr>
                        @endif

                        @foreach($missionTypes as $missionType)
                            <tr>
                                <td>{{ $missionType->id }}</td>
                                <td>{{ $missionType->mission_type_name }}</td>

                                @if(auth()->user()->can('update'))
                                
                                <td>

                                {{-- 
                                    <button class="btn btn-outline-success"  data-toggle="modal" data-target="#editMissionType{{$missionType->id}}">
                                        <i class="fa fa-fw fa-edit" style="transform: scale(1.5);"></i>
                                    </button> 
                                                --}}

                                    <button class="btn btn-outline-danger"  data-toggle="modal" data-target="#deleteMissionType{{$missionType->id}}" title="Delete">
                                        <i class="fa fa-fw fa-trash" style="transform: scale(1.5);"></i>
                                    </button>
                                        
                                </td>

                                @endif

                            </tr>

                        @if(auth()->user()->can('delete'))
                        <!-- Delete Modal -->                       
                        <div class="modal fade" id="deleteMissionType{{$missionType->id}}" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Confirmation</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <form method="POST" action="{{ route('admin.delete_mission_type', $missionType->id) }}">
                                        @method('DELETE')
                                        @csrf
                                        <div class="modal-body">
                                            <p>You are about to delete.</p> 
                                            <p class="text-muted">This may cause error to related missions.</p>
                                            
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

                    {{-- 
                        @if(auth()->user()->can('update'))
                        <!-- Edit Modal -->  
                        <div class="modal fade" id="editMissionType{{$missionType->id}}" role="dialog">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h3> Edit Mission Type</h3>
                                        <button type="button" class="close" data-dismiss="modal">
                                            &times;
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        
                                        <form method="post" action= "{{ route('admin.updated_mission_type_submit', $missionType->id) }}" enctype="multipart/form-data">
                                            
                                            @csrf
                                            @method('PUT')

                                            <div class="form-row">
                                                <div class="col-md-12 mb-4">
                                                    <label for="validationServer01">Mission Type Name </label>
                                                    <div class="input-group">
                                                        <input step="any" type="text" name="mission_type_name" class="form-control form-control-lg is-invalid"  value="{{ $missionType->mission_type_name }}" required="true">
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
                                --}}
                                

                        @endforeach

                        </tbody>
                    </table>

                    <div class="float-right">
                        {{ $missionTypes->onEachSide(5)->links() }}
                    </div>
                </div>
            </div>
        </div>

        
    {{-- 
        @if(auth()->user()->can('create'))

        <div class="modal fade" id="addMissionType" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <h3> Add Mission Type</h3>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                        
                        <form method="post" action= "{{ route('admin.created_mission_type_submit') }}" enctype="multipart/form-data">
                            
                            @csrf

                            <div class="form-row">
                                <div class="col-md-12 mb-4">
                                    <label for="validationServer01">Mission Type Name</label>
                                    <div class="input-group">
                                        <input step="any" type="text" name="mission_type_name" class="form-control form-control-lg is-invalid"  placeholder="Type Name" required="true">
                                    </div>
                                </div>
                            </div>

                            <br>

                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-lg btn-block btn-primary">Create Type</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

        @endif 
                --}}

    </div>
@stop