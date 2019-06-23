
@extends('admin.master_layout.app')
@section('contents')

        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <h3 class="float-left">Disabled Animations List </h3>
                    </div>

                    <div class="col-6">
                        <a  href="{{route('admin.view_enabled_animations')}}"  class="btn btn-outline-success float-right" type="button">
                            Enabled Animations
                        </a>
                    </div>
                </div>

                <hr>

                <div class="row ">
                    <div class="col-12 table-responsive">
                        
                        <table class="table table-hover table-striped table-bordered text-center" cellspacing="0" width="100%">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Animation Name</th>
                                    <th>Prices</th>
                                    <th>Discounts</th>
                                    <th class="actions">Actions</th>
                                </tr>

                            </thead>

                            <tbody>

                            @if($animations->isEmpty())
                                <tr class="danger">
                                    <td class="text-danger" colspan='5'>No Data Found</td>
                                </tr>
                            @endif

                            @foreach($animations as $animation)
                                <tr>
                                    <td>{{ $animation->name }}</td>

                                    <td>
                                        <p>{{ $animation->price_taka }} taka</p>
                                        <p>{{ $animation->price_gems }} gems</p>
                                        <p>{{ $animation->price_coins }} coins</p>
                                    </td>

                                    <td>
                                        <p>{{ $animation->discount_taka }}% (taka)</p>
                                        <p>{{ $animation->discount_gems }}% (gems)</p>
                                        <p>{{ $animation->discount_coins }}% (coins)</p>
                                    </td>

                                    <td>

                                        <button class="btn btn-outline-danger" data-toggle="modal" data-target="#undoModal{{$animation->id}}" title="Undo">
                                            <i class="fa fa-fw fa-undo" style="transform: scale(1.5); padding: 2px;"></i>
                                        </button>
                                        
                                    </td>
                                </tr>

                            
                                <!--Undo Modal -->
                                <div class="modal fade" id="undoModal{{$animation->id}}" role="dialog">
                                    <div class="modal-dialog">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Confirmation</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <form method="POST" action="{{ route('admin.undo_animation', $animation->id) }}">
                                                
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
                                    

                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>

                
                <div class="float-right">
                    {{ $animations->onEachSide(5)->links() }}
                </div>
            </div>

        </div>
@stop