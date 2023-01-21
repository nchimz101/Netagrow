<div class="card shadow" id="weatherWidget">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-8">
                <h3 class="mb-0">{{ __('Weather')}}</h3>
            </div>
            @if (!isset($_GET['page']))
                <div class="col-4 text-right">
                    <a class="mb-0  text-info" :href="detailWeatherURL()">{{ __('Forecast')}} ></a>
                </div>
            @endif
        </div>
    </div>
    <div class="card-body">
        <div class="row ">
            <div class="col-4">
                <div class="row align-items-center">
                    <div class="col-6">
                        <img class="w-100"  :src="icon"  />
                    </div>
                    <div class="col-6">
                        <h1 style="font-size: 3rem;">@{{ temp }}</h1>
                    </div>
                </div>
                <div class="container">
                    <h2>
                        <small class="text-muted">{{ __('Feels like')}} @{{ feels_like }} </small>
                    </h2>
                </div>
            </div>

            <div class="col-3">
                <div class="mt-3"> 
                    <small class="text-muted">{{ __('Temp max')}}</small>
                    <h3 class="mb-0">@{{ temp_max }}</h3>
                </div>
                <div class="mt-3">
                    <small class="text-muted">{{ __('Temp min')}}</small>
                    <h3 class="mb-0">@{{ temp_min }}</h3>
                </div>
            </div>
            
            <div class="col-2">
                <div class="mt-3"> 
                    <small class="text-muted">{{ __('Wind')}}</small>
                    <h3 class="mb-0">@{{ wind_speed }}</h3>
                </div>
                <div class="mt-3">
                    <small class="text-muted">{{ __('Pressure')}}</small>
                    <h3 class="mb-0">@{{ pressure }} hPa</h3>
                </div>
            </div>
            <div class="col-2">
                <div class="mt-3"> 
                    <small class="text-muted">{{ __('Hummidity')}}</small>
                    <h3 class="mb-0">@{{ humidity }}%</h3>
                </div>
                <div class="mt-3">
                    <small class="text-muted">{{ __('Cloud cover')}}</small>
                    <h3 class="mb-0">@{{ clouds }}%</h3>
                </div>
            </div>
           
        </div>
    </div>
</div>