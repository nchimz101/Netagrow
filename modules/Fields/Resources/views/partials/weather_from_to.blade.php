
    <div class="ds-flex align-items-center">
        @{{moment.unix(item.dt).format('ddd Do')}}<br />
        @{{moment.unix(item.dt).format('HH:mm')}}<br />
        <img class="mr--2" style="width:50px" :src="icon(item)" srcs="http://localhost/images/icons/weather_icons/02d.png" /><br />
        <strong>@{{item.main.temp}} °@{{units}}</strong><br /><br />
        @{{item.weather[0].main}}<br />
        @{{item.weather[0].description}}
        <hr />
        {{ __('Pressure')}} : @{{ item.main.pressure }} hPa<br />
        {{ __('Humidity')}} : @{{ item.main.humidity }}%<br />
        {{ __('Wind')}} : @{{ item.wind.speed }} @{{ speed() }} @{{ degToCompass(item.wind.deg) }} <br />
        {{ __('Cloud cover')}} : @{{ item.clouds.all }}%<br />
        <hr />
        <span  v-if="item.snow">
            {{ __('Snow in 3h')}} : @{{ item.snow['3h'] }}<br />
        </span>
        <span  v-if="item.rain">
            {{ __('Rain in 3h')}} : @{{ item.rain['3h'] }}<br />
        </span>
        
        
    </div>
