import mapboxgl from 'mapbox-gl';
function map() {
    mapboxgl.accessToken = 'pk.eyJ1Ijoid29vbnRvcG9saXMiLCJhIjoiY2tnYXoxMGVkMDgzMjJ2bTVqeTRrY2FuaiJ9.hg2PeuwIn4SPCMEFLt7mbw';
    var map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v11',
        center: [-72.88, 45.62], // Long, Lat
        zoom: 5,
        minZoom: 3
    });
    map.on('load', function() {
        map.addSource('10m-bathymetry-81bsvj', {
            type: 'vector',
            url: 'mapbox://mapbox.9tm8dx88'
        });
        map.addLayer(
            {
                'id': '10m-bathymetry-81bsvj',
                'type': 'fill',
                'source': '10m-bathymetry-81bsvj',
                'source-layer': '10m-bathymetry-81bsvj',
                'layout': {},
                'paint': {
                    'fill-outline-color': 'hsla(337, 82%, 62%, 0)',
                    // cubic bezier is a four point curve for smooth and precise styling
                    // adjust the points to change the rate and intensity of interpolation
                    'fill-color': [
                        'interpolate',
                        ['cubic-bezier', 0, 0.5, 1, 0.5],
                        ['get', 'DEPTH'],
                        200,
                        '#78bced',
                        9000,
                        '#15659f'
                    ]
                }
            },
            'land-structure-polygon'
        );
    });
}
