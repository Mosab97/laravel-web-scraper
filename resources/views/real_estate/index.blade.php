@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">


                    <div class="d-flex justify-content-between">
                        <div class="card-header">{{count($real_esats)}} Real Estates</div>

                    @if(count($real_esats) == 0)
                        <a href="{{route('real_estate.get_data')}}" class="btn btn-info " style="margin: 5px">get Real Estate</a>

                        @else
                        <a href="{{route('real_estate.create')}}" class="btn btn-info " style="margin: 5px">Add Real Estate</a>
                        @endif
                    </div>

                    @if(count($real_esats) > 0)
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">title</th>
                                <th scope="col">images count</th>
                                <th scope="col">price</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($real_esats as $index=>$item)
                                <tr>
                                    <th scope="row">{{$item->id}}</th>
                                    <td>{{$item->title}}</td>
                                    <td>{{$item->images()->count()}}</td>
                                    <td>{{$item->price}}</td>
                                    <td>
                                        <a href="{{route('real_estate.edit',$item->id)}}" class="btn btn-warning">edit</a>
{{--                                        <a href="javascript" >delete</a>--}}
                                        <a class="btn btn-danger"
                                           href="{{ route('real_estate.destroy',$item->id) }}"
                                           onclick="event.preventDefault();document.getElementById('delete-form').submit();">Delete</a>
                                        <form id="delete-form" action="{{ route('real_estate.destroy',$item->id) }}"
                                              method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $real_esats->links() }}
                    @else
                        no items found!!
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection
