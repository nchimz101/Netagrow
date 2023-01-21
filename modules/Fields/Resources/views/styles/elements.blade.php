<style>
    .calculation-box {
        height: 75px;
        width: 150px;
        position: absolute;
        bottom: 40px;
        left: 10px;
        background-color: rgba(255, 255, 255, 0.9);
        padding: 15px;
        text-align: center;
    }
     
    p {
        font-family: 'Open Sans';
        margin: 0;
        font-size: 13px;
    }  
    
    .marker {
        background-image: url('/images/icons/markers/mapbox-marker-icon-blue.svg');
        background-size: cover;
        width: 25px;
        height: 35px;
        border-radius: 50%;
        cursor: pointer;
    }

    .markerRed {
        background-image: url('/images/icons/markers/mapbox-marker-icon-red.svg');
    }

    .mapboxgl-popup {
        max-width: 200px;
    }
    .mapboxgl-popup-content {
        text-align: center;
        font-family: 'Open Sans', sans-serif;
    }
</style>