
@extends('admin.master_layout.app')
@section('contents')

        <div class="card mb-4">
            <div class="card-body">
                
                <div class="row">
                    <div class="col-6">
                        <h3 class="float-left">Enabled Boost Packs List </h3>
                    </div>
                    <div class="col-6">
                        <button type="button" class="btn btn-info float-right" data-toggle="modal" data-target="#addModal">
                            New Boost Pack
                        </button>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-12 table-responsive">

                        <table class="table table-hover table-striped table-bordered text-center" cellspacing="0" width="100%">
                            <thead class="thead-dark">
                            <tr>
                                <th>Boost Pack Name</th>
                                <th>Amount</th>
                                <th>Prices</th>
                                <th>Offers</th>
                                <th class="actions">Actions</th>
                            </tr>
                            </thead>
                            <tbody>

                            @if($boostPacks->isEmpty())
                                <tr class="danger">
                                    <td class="text-danger" colspan='5'>No Data Found</td>
                                </tr>
                            @endif
                            
                            @foreach($boostPacks as $boostPack)
                                <tr>
                                    <td>{{ $boostPack->name }}</td>
                                    <td>{{ $boostPack->amount }}</td>
                                    <td>
                                        <p>{{ $boostPack->price_taka }} taka</p>
                                        <p>{{ $boostPack->price_gems }} gems</p>
                                        <p>{{ $boostPack->price_coins }} coins</p>
                                    </td>
                                    
                                    <td>
                                        <p>{{ $boostPack->discount_taka }}% (taka)</p>
                                        <p>{{ $boostPack->discount_gems }}% (gems)</p>
                                        <p>{{ $boostPack->discount_coins }}% (coins)</p>
                                    </td>
                                    
                                    <td>
                                    
                                        <button class="btn btn-outline-success" data-toggle="modal" data-target="#editModal{{$boostPack->id}}" title="Edit">
                                            <i class="fa fa-fw fa-edit" style="transform: scale(1.5);"></i>
                                        </button>

                                        <button class="btn btn-outline-danger" data-toggle="modal" data-target="#deleteModal{{$boostPack->id}}" title="Delete">
                                            <i class="fa fa-fw fa-trash" style="transform: scale(1.5);"></i>
                                        </button>

                                    </td>
                                </tr>

                                <!--- Edit Modal --->
                                <div class="modal fade" id="editModal{{$boostPack->id}}" role="dialog">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <h3> Edit boostPack </h3>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>

                                            <div class="modal-body">
                                                
                                                <form method="post" action = "{{ route('admin.updated_boost_pack_submit', $boostPack->id) }}" enctype="multipart/form-data">
                                                    
                                                    @csrf
                                                    @Method('put')

                                                    <div class="form-row">
                                                        <div class="col-md-3 mb-4">
                                                            <label for="validationServerUsername">Name</label>
                                                            <div class="input-group">
                                                                <input type="text" name="name" class="form-control form-control-lg is-invalid" value="{{ $boostPack->name }}" aria-describedby="inputGroupPrepend3" required="true">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3 mb-4">
                                                            <label for="validationServer01">Type</label>
                                                            <select class="form-control form-control-lg is-valid" name="type" readonly>
                                                                <option value="Boost Pack">Boost Pack</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-3 mb-4">
                                                            <label for="validationServerUsername">Amount</label>
                                                            <div class="input-group">
                                                                <input type="number" name="amount" class="form-control form-control-lg is-invalid" value="{{ $boostPack->amount }}" aria-describedby="inputGroupPrepend3" required="true" min='1' step="1.0">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3 mb-4">
                                                            <label for="validationServerUsername">Description</label>
                                                            <div class="input-group">
                                                                <input type="text" name="description" class="form-control form-control-lg is-valid" value="{{ $boostPack->description }}" aria-describedby="inputGroupPrepend3">
                                                            </div>
                                                        </div>
                                                    </div>
                                                  
                                                    <div class="form-row">
                                                        <div class="col-md-4 mb-4">
                                                            <label for="validationServer01">Price (taka)</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">@ taka</span>
                                                                </div>
                                                                <input type="number" name="price_taka" class="form-control form-control-lg is-invalid"  value="{{ $boostPack->price_taka }}" required="true" step="any">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-4">
                                                            <label for="validationServer01">Price (gems)</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">@ gems</span>
                                                                </div>
                                                                <input type="number" name="price_gems" class="form-control form-control-lg is-invalid"  value="{{ $boostPack->price_gems }}" required="true">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-4">
                                                            <label for="validationServer01">Price (coins)</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">@ coins</span>
                                                                </div>
                                                                <input type="number" name="price_coins" class="form-control form-control-lg is-invalid"  value="{{ $boostPack->price_coins }}" required="true">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-row mb-4">
                                                        <div class="col-md-5">
                                                            <label for="validationServerUsername">Discount</label>
                                                            <div class="input-group">
                                                                <input step="any" type="number" name="discount" class="form-control form-control-lg is-invalid" value="{{max($boostPack->discount_taka, $boostPack->discount_gems, $boostPack->discount_coins)}}" aria-describedby="inputGroupPrepend3" required="true" min="0" max="100">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">%</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1"></div>
                                                        <div class="col-md-2 col-4">    
                                                            <div class="form-check form-check-inline mt-5">
                                                                <input name="discount_type[]" class="form-check-input" type="checkbox" value="taka" @if($boostPack->discount_taka > 0) checked="true" @endif>
                                                                <label class="form-check-label" for="inlineCheckbox1">@ taka</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2 col-4">
                                                            <div class="form-check form-check-inline mt-5">
                                                                <input name="discount_type[]" class="form-check-input" type="checkbox" value="gems" @if($boostPack->discount_gems > 0) checked="true" @endif>
                                                                <label class="form-check-label" for="inlineCheckbox1">@ gems</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2 col-4">
                                                            <div class="form-check form-check-inline mt-5">
                                                                <input name="discount_type[]" class="form-check-input" type="checkbox" value="coins" @if($boostPack->discount_coins > 0) checked="true" @endif>
                                                                <label class="form-check-label" for="inlineCheckbox1">@ coins</label>
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

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal{{$boostPack->id}}" role="dialog">
                                    <div class="modal-dialog">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Confirmation</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <form method="POST" action="{{ route('admin.delete_boost_pack', $boostPack->id) }}">
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

                            @endforeach
                            </tbody>
                        </table>
                        <div class="float-right">
                            {{ $boostPacks->onEachSide(5)->links() }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="addModal" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h3> Create Boost Pack </h3>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <div class="modal-body">
                            
                            <form method="post" action= "{{ route('admin.created_boost_pack_submit') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="form-row">
                                    <div class="col-md-3 mb-4">
                                        <label for="validationServerUsername">Name</label>
                                        <div class="input-group">
                                            <input type="text" name="name" class="form-control form-control-lg is-invalid" placeholder="Unique Name(required)" aria-describedby="inputGroupPrepend3" required="true">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-4">
                                        <label for="validationServer01">Type</label>
                                        <select class="form-control form-control-lg is-valid" name="type" readonly>
                                            <option value="Boost Pack">Boost Pack</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-4">
                                        <label for="validationServerUsername">Amount</label>
                                        <div class="input-group">
                                            <input type="number" name="amount" class="form-control form-control-lg is-invalid" aria-describedby="inputGroupPrepend3" required="true" min='1' placeholder="Amount(required)">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-4">
                                        <label for="validationServerUsername">Description</label>
                                        <div class="input-group">
                                            <input type="text" name="description" class="form-control form-control-lg is-valid" placeholder="Short Description" aria-describedby="inputGroupPrepend3">
                                        </div>
                                    </div>
                                </div>
                              
                                <div class="form-row">
                                    <div class="col-md-4 mb-4">
                                        <label for="validationServerUsername">Price (taka)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">@ taka</span>
                                            </div>
                                            <input type="number" name="price_taka" class="form-control form-control-lg is-invalid" placeholder="(required)" aria-describedby="inputGroupPrepend3" required="true" step="any">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <label for="validationServerUsername">Price (gems)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">@ gems</span>
                                            </div>
                                            <input type="number" name="price_gems" class="form-control form-control-lg is-invalid" placeholder="(required)" aria-describedby="inputGroupPrepend3" required="true">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <label for="validationServer01">Price (coins)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">@ coins</span>
                                            </div>
                                            <input type="number" name="price_coins" class="form-control form-control-lg is-invalid"  placeholder="(required)" required="true">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row mb-4">
                                    <div class="col-md-5">
                                        <label for="validationServerUsername">Discount</label>
                                        <div class="input-group">
                                            <input type="number" name="discount" class="form-control form-control-lg is-invalid" placeholder="Discount Percentage" aria-describedby="inputGroupPrepend3" required="true" min="0" max="100" step="any">
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

        </div>
@stop