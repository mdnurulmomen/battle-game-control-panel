
@extends('admin.master_layout.app')
@section('contents')

    <div class="card mb-4">
        <div class="card-body">
            
            <div class="row">
                <div class="col-6">
                    <h3 class="float-left">Disabled Subscription Packages List </h3>
                </div>
                <div class="col-6">
                    
                    @if(auth()->user()->can('read'))

                    <a  href="{{route('admin.view_enabled_subscription_packages')}}"  class="btn btn-outline-success float-right btn-sm" type="button">
                        <i class="fa fa-list" aria-hidden="true"></i>
                        Enabled Packages
                    </a>

                    @endif

                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-12 table-responsive">

                    <table class="table table-hover table-striped table-bordered text-center" cellspacing="0" width="100%">

                        <thead class="thead-dark">
                            <tr>
                                <th>Package Serial</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Cost(gems)</th>

                                @if(auth()->user()->can('update'))
                            
                                <th>Action</th>
                            
                                @endif
                            
                            </tr>
                        </thead>
                        
                        <tbody>

                        @if($subscriptionPackages->isEmpty())
                            <tr class="danger">
                                <td class="text-danger" colspan='5'>No Data Found</td>
                            </tr>
                        @endif
                        
                        @foreach($subscriptionPackages as $package)
                            <tr>
                                <td>{{ $package->id }}</td>
                                <td>{{ $package->name }}</td>
                                <td>{{ $package->subscriptionPackageType->name }}</td>
                                <td>{{ $package->price_gem ?? 0 }}</td>

                                @if(auth()->user()->can('update'))

                                <td>

                                    <button class="btn btn-outline-danger"  data-toggle="modal" data-target="#undoModal{{$package->id}}" title="Undo">
                                        <i class="fa fa-fw fa-undo" style="transform: scale(1.5);"></i>
                                    </button>
                                        
                                </td>

                                @endif

                            </tr>

                        @if(auth()->user()->can('update'))
                        <!-- Undo Modal -->                       
                        <div class="modal fade" id="undoModal{{$package->id}}" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Confirmation</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <form method="POST" action="{{ route('admin.undo_subscription_package', $package->id) }}">
                                        
                                        @method('PATCH')
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

                        @endif

                        @endforeach
                                
                        </tbody>
                    </table>

                    <div class="float-right">
                        {{ $subscriptionPackages->onEachSide(5)->links() }}
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop