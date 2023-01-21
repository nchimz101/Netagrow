@extends('general.masterwithdetail', $setup);
@section('js')
    <script src='{{ asset('vendor') }}/mapbox/mapbox-gl.js'></script>
    <link href='{{ asset('vendor') }}/mapbox/mapbox-gl.css' rel='stylesheet' />
    
    <script src="{{ asset('vendor') }}/mapbox/mapbox-gl-geocoder.min.js"></script>
    <link rel="stylesheet" href="{{ asset('vendor') }}/mapbox/mapbox-gl-geocoder.css" type="text/css">
    
    @include('fields::styles.elements')
   
    <script src="{{ asset('vendor') }}/mapbox/turf.min.js"></script>
    <script src="{{ asset('vendor') }}/mapbox/mapbox-gl-draw.js"></script>
    <link rel="stylesheet" href="{{ asset('vendor') }}/mapbox/mapbox-gl-draw.css" type="text/css" />
  
    @include('fields::scripts.app')
@endsection
@section('cardbody')
    <div id="list">

        @foreach ($setup['items'] as $index=>$item)
         
            @if ($setup['type']=="notes")
                @include('fields::partials.lists.notes')
            @else
                @include('fields::partials.lists.fields')
            @endif
           
      
        @endforeach
        
    </div>
    <div id="info" style="display: none">
        <p>{{ __('Draw the area on the map') }}</p>
    </div>
    <div id="form" style="display: none">
        <form id="form-assing-driver" method="POST" action="{{ route('fields.store') }}">
            @csrf 
            <p>{{ __('Create the new field.')}}</p><br />
            @include('partials.input',['classselect'=>"mr--9",'id'=>'name','name'=>'Name','placeholder'=>'Field name','value'=>__("Field"), 'required'=>true])
            @include('partials.select', ['name'=>"Crops",'id'=>"crop_id",'placeholder'=>"Select crop",'data'=>$crops,'required'=>true, 'value'=>''])
            @include('partials.input',['id'=>'area','name'=>'Area','placeholder'=>'Area size','value'=>'','type'=>"hidden", 'required'=>true])
            @include('partials.input',['id'=>'coordinates','name'=>'Coordinates','placeholder'=>'Coordinates','value'=>'','type'=>"hidden", 'required'=>true])
            <div class="text-center">
                <button type="submit" class="btn btn-primary my-4">{{ __('Save') }}</button>
            </div>
        </form>
    </div>
   
@endsection
@section('details')
    <div class="card" id="theMapHolder"> 
        <div class="card-bodys" >
            <div id='map' style='height: 500px;' class="w-100"></div>
        </div>
    </div>

    
    @include('fields::partials.weather')
    @include('fields::partials.forecast')
    @include('fields::partials.notes_overview')
    

    <div class="card shadow mt-3 d-none">
        <div class="card-header border-0">
            <div class="row align-items-center">
                <div class="col-8">
                    <h3 class="mb-0">----</h3>
                </div>
            </div>
        </div>
    </div>

@endsection