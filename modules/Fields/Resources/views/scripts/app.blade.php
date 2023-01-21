<script>

    Vue.prototype.moment = moment

    var weatherWidget=null;
    var foercastWidget=null;
    var notesWidget=null;
    $('#weatherWidget').hide();
    $('#forecast').hide();
    $('#notesWidget').hide();

    var PAGE="<?php echo $setup['type']; ?>"; //weather, notes, fields
    var ID="<?php echo $setup['id']; ?>"; //weather, notes, fields
    var startingLat="<?php echo $setup['lat']; ?>";
    var startingLng="<?php echo $setup['lng']; ?>";

    //Vue Weather
    weatherWidget = new Vue({
        el: '#weatherWidget',
        data: {
            id:null,
            currentWeather:0,
            temp:"",
            temp_max:"",
            temp_min:"",
            feels_like:"",
            humidity:"",
            pressure:"",
            clouds:"",
            icon:"",
            description:"",
            wind_speed:"",
            wind_deg:"",
            rain:null,
            snow:null,
        },
        methods: {
            detailWeatherURL:function(){
                return "/fields/list?page=weather&id="+this.id;
            }
        }
    })

    //Vue Forecast
    forecastWidget = new Vue({
        el: '#forecast',
        data: {
            forecast:[],
            units:"C",
            unitsType:"metric"
        },
        methods: {
            icon:function(item){
                return "/images/icons/weather_icons/"+item.weather[0].icon+".png"
            },
            degToCompass(num) {
                var val = Math.floor((num / 22.5) + 0.5);
                var arr = ["N", "NNE", "NE", "ENE", "E", "ESE", "SE", "SSE", "S", "SSW", "SW", "WSW", "W", "WNW", "NW", "NNW"];
                return arr[(val % 16)];
            },
            speed(){
                if(this.unitsType=="metric"){
                    return "m/s"
                }else{
                    return "miles/h"
                }
            }
        }
    })

    //Vue Notes
    notesWidget = new Vue({
        el: '#notesWidget',
        data: {
            id:0,
            title:"",
            type:"",
            image:"",
            link:"",
            description:"",
            status:"",
            by:"",
            assignedto:"",
            is_public:"0",
            type_icon:"",
            uuid:""
        },
        methods: {
        }
    })

    var startingCoordinate=[startingLng, startingLat];
    var items=<?php echo $setup['itemsJson']; ?>

   
    mapboxgl.accessToken = "{{ config('fields.token')}}";
    
    //Set the map
    var map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/satellite-v9', // style URLss
        center: items.length>0&&items[items.length-1].coordinates?JSON.parse(items[items.length-1].coordinates)[0][0]:startingCoordinate, // starting position [lng, lat]
        zoom: 14, // starting zoom
        // pitch: 60,
    });

    //Actions based on differnt pages
   
    //Weather
    if(PAGE=="weather"){
        $('#theMapHolder').hide();
        $('#notesWidget').hide();
    } 

    //Notes
    if(PAGE=="notes"){
        $('#notesWidget').hide();
    }

    //On load mapp, add poligons and add markers
    map.on('load', function () {
        if(items.length>0){

            if(PAGE=="notes"){
                //Notes - Markers
                items.forEach((item,index) => {
                    //For the marker
                    const el = document.createElement('div');
                    el.className = 'marker markerRed';
                    //el.style.backgroundImage=`url(https://cdn-icons-png.flaticon.com/512/727/727598.png)`;
                    //'url('+item.type_icon+')';
                    //el.style.backgroundImage="https://cdn-icons-png.flaticon.com/512/727/727598.png";
                    
                    // make a marker for each feature and add it to the map
                    new mapboxgl.Marker(el)
                        .setLngLat([item.lng,item.lat])
                        .setPopup(
                        new mapboxgl.Popup({ offset: 25 }) // add popups
                        .setHTML(
                        `<h3>${item.title}</h3><p>${item.description}</p>`
                        )
                    )
                    .addTo(map);

                    el.addEventListener('click', () => 
                    { 
                        //alert(item.id);
                        showNote(index)
                    }
                    ); 

                    //For the field
                    var coords= JSON.parse(item.field.coordinates);
                    console.log(coords);

                    map.addSource(item.id+"id", {
                        'type': 'geojson',
                        'data': {
                            'type': 'Feature',
                            'geometry': {
                            'type': 'Polygon',
                            // These coordinates outline Maine.
                            'coordinates': coords
                            }
                        }
                    });

                    // Add a new layer to visualize the polygon.
                    map.addLayer({
                        'id': item.id+"id",
                        'type': 'fill',
                        'source': item.id+"id", // reference the data source
                        'layout': {},
                        'paint': {
                            'fill-color': '#ff0000', // blue color fill
                            'fill-opacity': 0.2
                        }
                    });

                    // Add a black outline around the polygon.
                    map.addLayer({
                        'id': item.id+"_outline_id",
                        'type': 'line',
                        'source': item.id+"id",
                        'layout': {},
                        'paint': {
                            'line-color': '#ff0',
                            'line-width': 3
                        }
                    });





                    });

            }else{
                //Fields
                items.forEach(item => {
                    var coords= JSON.parse(item.coordinates);
                    console.log(coords);

                    map.addSource(item.id+"id", {
                        'type': 'geojson',
                        'data': {
                            'type': 'Feature',
                            'geometry': {
                            'type': 'Polygon',
                            // These coordinates outline Maine.
                            'coordinates': coords
                            }
                        }
                    });

                    // Add a new layer to visualize the polygon.
                    map.addLayer({
                        'id': item.id+"id",
                        'type': 'fill',
                        'source': item.id+"id", // reference the data source
                        'layout': {},
                        'paint': {
                            'fill-color': '#ff0000', // blue color fill
                            'fill-opacity': 0.2
                        }
                    });

                    // Add a black outline around the polygon.
                    map.addLayer({
                        'id': item.id+"_outline_id",
                        'type': 'line',
                        'source': item.id+"id",
                        'layout': {},
                        'paint': {
                            'line-color': '#ff0',
                            'line-width': 3
                        }
                    });

                    map.on('click', item.id+"id", (e) => {
                        ID=item.id;
                        showItem(item.id,true)
                    });





                });
            }
        }
    });


    /* PLUGINS */

    //DRAW
    const draw = new MapboxDraw({
        displayControlsDefault: false,
        // Select which mapbox-gl-draw control buttons to add to the map.
        controls: {
        polygon: true,
        trash: true
        },
        // Set mapbox-gl-draw to draw by default.
        // The user does not have to click the polygon control button first.
        defaultMode: 'draw_polygon'
    });
    map.on('draw.create', updateArea);
    map.on('draw.delete', updateArea);
    map.on('draw.update', updateArea);
        
    function updateArea(e) {
        const data = draw.getAll();
        const answer = document.getElementById('calculated-area');
        if (data.features.length > 0) {
            showForm();
            // Add a data source containing GeoJSON data.
            const area = turf.area(data);
            const rounded_area = Math.round(area * 100) / 100;
            $('#area').val(rounded_area);
    
            $('#coordinates').val(JSON.stringify(data.features[0].geometry.coordinates));
        }
    }

    /* GEOCODER */
    const coordinatesGeocoder = function (query) {
        // Match anything which looks like
        // decimal degrees coordinate pair.
        const matches = query.match(
        /^[ ]*(?:Lat: )?(-?\d+\.?\d*)[, ]+(?:Lng: )?(-?\d+\.?\d*)[ ]*$/i
        );
        if (!matches) {
        return null;
        }
        
        function coordinateFeature(lng, lat) {
            return {
                center: [lng, lat],
                geometry: {
                    type: 'Point',
                    coordinates: [lng, lat]
                },
                place_name: 'Lat: ' + lat + ' Lng: ' + lng,
                place_type: ['coordinate'],
                properties: {},
                type: 'Feature'
            };
        }
        
        const coord1 = Number(matches[1]);
        const coord2 = Number(matches[2]);
        const geocodes = [];
        
        if (coord1 < -90 || coord1 > 90) {
            // must be lng, lat
            geocodes.push(coordinateFeature(coord1, coord2));
        }
        
        if (coord2 < -90 || coord2 > 90) {
            // must be lat, lng
            geocodes.push(coordinateFeature(coord2, coord1));
        }
        
        if (geocodes.length === 0) {
            // else could be either lng, lat or lat, lng
            geocodes.push(coordinateFeature(coord1, coord2));
            geocodes.push(coordinateFeature(coord2, coord1));
        }
        
        return geocodes;
    };
    // Add the control to the map.
    map.addControl(
        new MapboxGeocoder({
            accessToken: mapboxgl.accessToken,
            localGeocoder: coordinatesGeocoder,
            zoom: 4,
            placeholder: 'Search...',
            mapboxgl: mapboxgl,
            reverseGeocode: true
        })
    );

     // Add geolocate control to the map.
     map.addControl(
        new mapboxgl.GeolocateControl({
            positionOptions: {
                enableHighAccuracy: true
            },
            // When active the map will receive updates to the device's location as it changes.
            trackUserLocation: true,
            // Draw an arrow next to the location dot to indicate which direction the device is heading.
            showUserHeading: true
        })
    );

    /* ZOOM and FullScreen */
    // Add zoom and rotation controls to the map.
    map.addControl(new mapboxgl.NavigationControl());
    map.addControl(new mapboxgl.FullscreenControl());


    /* FUNCTIONS */

    //Displays note
    function showNote(index){
        var theNote=items[index];
        console.log(theNote);
        $('#notesWidget').show();
        setNoteFromObject(theNote);
        $('#theMapHolder').show();

        //Add the marker
        map.setCenter([theNote.lng,theNote.lat]);
        //map.fireEvent('click', [theNote.lng,theNote.lat]);
        map.fire("click", {lngLat: [theNote.lng,theNote.lat]});
    }

    //Converts fromm index to id
    function toIdBased(items){
        var idBased=[];
        items.forEach(element => {
            idBased[element.id]=element;
        });
        return idBased;
    }

    //Displayes field
    function showItem(id,asIndex=false){
        var idBased=asIndex?toIdBased(items):items;
        var theCords=JSON.parse(idBased[id].coordinates);
        map.setCenter(theCords[0][0]);
        if(PAGE!="notes"){
            weatherWidget.id=JSON.parse(idBased[id].id);
            getWeather(theCords[0][0][1],theCords[0][0][0]);
        }
        
        if(PAGE=="weather"){
            getForecast(theCords[0][0][1],theCords[0][0][0]);
        }
        
        if(PAGE=="fields"||PAGE=="notes"){
            getNotes(idBased[id].id);
        }
        
    }

    //Gets a note
    function getNotes(id){
        $('#notesWidget').hide();
        axios.get('/fields/notes/widget/'+id).then(function (response) {
            setNote(response);
        }).catch(function (error) {
            
        });
    }

    //Show Preselected values
    if(ID){
        showItem(ID,true);
    }

    //Set  a note
    function setNote(raw_response){
        if(raw_response.data.status=="ok"){
                //Show widget
                $('#notesWidget').show();

                response=raw_response['data']['result'];
                console.log(response);
                setNoteFromObject(response)
        }
    }

    function setNoteFromObject(response){
        notesWidget.id=response.id;
            notesWidget.title=response.title;
            notesWidget.type=response.notetype.title;
            notesWidget.image=response.image;
            notesWidget.link="/note/"+response.id;
            notesWidget.description=response.description;
            notesWidget.status=response.notestatus.title;
            notesWidget.by=response.by.name;
            notesWidget.assignedto=response.for.name;
            notesWidget.is_public=response.is_public;
            notesWidget.type_icon=response.notetype.image;
            notesWidget.uuid=response.uuid;
    }

    function getWeather(lat,lng){
        console.log("Get weather for lat"+lat+" lng:"+lng);
        $('#weatherWidget').hide();
        axios.get('/weather/weather/'+lat+"/"+lng).then(function (response) {
            setWeather(response);
        }).catch(function (error) {
            response=raw_response['data']['result'];
            console.log(response);
        });
    }

    function getForecast(lat,lng){
        console.log("Get forecast weather for lat"+lat+" lng:"+lng);
        $('#forecast').hide();
        axios.get('/weather/forecast/'+lat+"/"+lng).then(function (response) {
            setForecast(response);
            //console.log(response);
        }).catch(function (error) {
            response=raw_response['data']['result'];
            console.log(response);
        });
    }

    function degToCompass(num) {
        var val = Math.floor((num / 22.5) + 0.5);
        var arr = ["N", "NNE", "NE", "ENE", "E", "ESE", "SE", "SSE", "S", "SSW", "SW", "WSW", "W", "WNW", "NW", "NNW"];
        return arr[(val % 16)];
    }

    function setForecast(raw_response) {
        if(raw_response.data.status=="ok"){
                //Show widget
                $('#forecast').show();
                response=raw_response['data']['result'];
                console.log(response);
                forecastWidget.forecast=response;
                forecastWidget.unitsType=raw_response['data']['units'];
        }
    }

    function setWeather(raw_response){
    
        if(raw_response.data.status=="ok"){
            //Show widget
            $('#weatherWidget').show();
            response=raw_response['data']['result'];
            console.log(response);
            //Set temperature
            weatherWidget.temp=Math.round(response.main.temp);
            weatherWidget.temp_max=Math.round(response.main.temp_max);
            weatherWidget.temp_min=Math.round(response.main.temp_min);
            weatherWidget.feels_like=Math.round(response.main.feels_like);
            weatherWidget.humidity=response.main.humidity;
            weatherWidget.pressure=response.main.pressure;
            weatherWidget.clouds=response.clouds.all;
            weatherWidget.icon="/images/icons/weather_icons/"+response.weather[0].icon+".png";
            weatherWidget.description=response.weather[0].description;
            weatherWidget.wind_speed=response.wind.speed;
            weatherWidget.wind_deg=response.wind.deg;
            weatherWidget.rain=response.rain?response.rain['3h']:null;
            weatherWidget.snow=response.snow?response.snow['3h']:null;

            if(raw_response.data.units=="metric"){
            weatherWidget.temp+="°C";
            weatherWidget.temp_max+="°C";
            weatherWidget.temp_min+="°C";
            weatherWidget.feels_like+="°C";
            weatherWidget.wind_speed+=" m/s"
            }

            if(raw_response.data.units=="imperial"){
            weatherWidget.temp+="°F";
            weatherWidget.temp_max+="°F";
            weatherWidget.temp_min+="°F";
            weatherWidget.feels_like+="°F";
            weatherWidget.wind_speed+=" miles/hour."
            }

            weatherWidget.wind_speed+=" "+degToCompass(response.wind.deg);
            
        }

        
    
    }

    function enterAddingNoteMode(){
        window.location.href = "{{ route('notes.index') }}";
    }

    function enterAddingMode(){
        console.log("Go into adding mode");

        //1 - add the draw elements
        map.addControl(draw);

        //Hide add field
        $('#mainAction').hide();
        $('#list').hide();
        $('#info').show();

        //Hide weather info
        $('#weatherWidget').hide();

    }

    function showForm(){
        $('#info').hide();
        $('#form').show();
        map.removeControl(draw);
    }

    function stopAddingMode    (){
        console.log("Go out of adding mode");
        //1 - add the draw elements
        map.removeControl(draw);
    }

    function goFullWeather(){
    
        // if already full screen; exit
        // else go fullscreen
        if (
            document.fullscreenElement ||
            document.webkitFullscreenElement ||
            document.mozFullScreenElement ||
            document.msFullscreenElement
        ) {
            if (document.exitFullscreen) {
            document.exitFullscreen();
            } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
            }
        } else {
            element = $('#theWeather').get(0);
            if (element.requestFullscreen) {
            element.requestFullscreen();
            } else if (element.mozRequestFullScreen) {
            element.mozRequestFullScreen();
            } else if (element.webkitRequestFullscreen) {
            element.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
            } else if (element.msRequestFullscreen) {
            element.msRequestFullscreen();
            }
        }

    }
</script>