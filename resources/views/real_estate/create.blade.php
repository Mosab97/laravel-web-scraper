@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="d-flex justify-content-between">
                        <div class="card-header">New Real Estates</div>
                        <a href="{{route('real_estate.index')}}" class="btn btn-info " style="margin: 5px">Back</a>
                    </div>

                    <form style="margin: 15px" enctype="multipart/form-data" action="{{route('real_estate.store') }}"
                          method="post">
                        {{ csrf_field() }}
                        @if(isset($real_estate))
                            <input type="hidden" name="real_estate_id" value="{{$real_estate->id}}">
                        @endif


                        <div class="form-group">
                            <label for="exampleInputEmail1">Title</label>
                            <input type="text"
                                   class="form-control"
                                   id="exampleInputEmail1"
                                   placeholder="Enter Title"
                                   name="title"
                                   value="{{isset($real_estate)? $real_estate->title : old('title')}}"
                            >
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Price</label>
                            <input type="number" class="form-control" id="exampleInputPassword1" placeholder="Price"
                                   name="price"
                                   value="{{isset($real_estate)? $real_estate->price : old('price')}}"
                            >
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlFile1">Real Estate Images</label>
                            <input type="file" multiple class="form-control-file" id="exampleFormControlFile1" name="images[]">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
