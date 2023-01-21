<div class="card shadow mt-3" id="forecast">
    <div class="card-header border-0">
        <div class="row align-items-center">
            <div class="col-8">
                <h3 class="mb-0">{{ __("5 day forecast")}}</h3>
            </div>
            <div class="col-4 text-right">
                <a class="mb-0  text-info" onclick="goFullWeather()">{{ __('Fullscreen')}} ></a>
            </div>
        </div>
    </div>
    <div class="card-body" id="theWeather">
        @include('fields::partials.forecast_data')
    </div>
</div>