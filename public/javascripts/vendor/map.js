mapboxgl.accessToken = 'pk.eyJ1Ijoid29vbnRvcG9saXMiLCJhIjoiY2tnYXoxMGVkMDgzMjJ2bTVqeTRrY2FuaiJ9.hg2PeuwIn4SPCMEFLt7mbw';
var map = new mapboxgl.Map({
    container: 'map', // container id
    style: 'mapbox://styles/mapbox/streets-v11', // style URL
    center: [-74.5, 40], // starting position [lng, lat]
    zoom: 9 // starting zoom
});