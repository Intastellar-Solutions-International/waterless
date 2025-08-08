const markersData = [{
    lat: 54.920808392162755,
    lng: 9.817759394645691,
    iconUrl: '/assets/map/icon.png',
    popup: `
            <h3>ASA Software - HQ</h3>
            <p>Jyllandsgade 30<br>
            6400 Sønderborg<br>
            Denmark</p>
            <p>Phone: <a href="tel:+4574449095">+45 74 44 90 95</a>
            <br>Email: <a href="mailto:sales@asasoftware.aero">sales@asasoftware.aero</a></p>
        `
},
{
    lat: 39.5038383085198,
    lng: -3.489984938574852,
    countryName: "Spain",
},
{
    countrName: "Scotland",
    lat: 56.4907,
    lng: -4.2026,
},
{
    countryName: "Ireland",
    lat: 53.29053416095598,
    lng: -7.807923227013672,
},
{
    lat: 55.58982477578499,
    lng: 12.132360136254405,
    countryName: "Denmark",
},
{
    lat: 61.85711932564392,
    lng: 16.09027347586446,
    countryName: "Sweden",
},
{
    lat: 62.831148669368496,
    lng: 10.59026542447477,
    countryName: "Norway"
},
{
    lat: -29.83543904071067,
    lng: 23.6291372107004,
    countryName: "South Africa",
},
{
    lat: 43.95907719517588,
    lng: 11.843816828150803,
    countrName: "Italy"
},
{
    lat: 47.074472792709386,
    lng: 14.757893268875126,
    countryName: "Austria",
},
{
    lat: 62.66635336804152,
    lng: 26.720943854850013,
    countryName: "Finland",
},
{
    countryName: "Germany",
    lat: 51.1657,
    lng: 10.4515,
},
{
    countryName: 'Netherlands',
    lat: 52.01112893960311,
    lng: 5.382389607322466,
},
{
    countrName: "Nigeria",
    lat: 9.082,
    lng: 8.6753,
},
{
    countryName: "Malasia",
    lat: 4.2105,
    lng: 101.9758,
},
{
    countryName: "France",
    lat: 46.6034,
    lng: 1.8883,
},
{
    countryName: "Portugal",
    lat: 39.3999,
    lng: -8.2245,
},
{
    countryName: "Bosnia and Herzegovina",
    lat: 43.8486,
    lng: 17.6791,
},
{
    countryName: "Switzerland",
    lat: 46.8182,
    lng: 8.2275,
},
{
    countryName: "Cyprus",
    lat: 35.1264,
    lng: 33.4299,
},
{
    countryName: "Uganda",
    lat: 1.3733,
    lng: 32.2903,
},
{
    countryName: "Kenya",
    lat: -1.286389,
    lng: 36.817223,
},
{
    countryName: "Slovenia",
    lat: 46.1512,
    lng: 14.9955,
},
{
    countryName: "Malta",
    lat: 35.9375,
    lng: 14.3754,
},
{
    countryName: "Israel",
    lat: 31.0461,
    lng: 34.8516,
},
{
    countryName: "Poland",
    lat: 51.9194,
    lng: 19.1451,
}
];

let mapStyle = 'light_all'; // Default map style
let marker = '/assets/map/icon-default-2.svg';
// Check if the user has a preference for dark mode
if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
    mapStyle = 'dark_all'; // Set to dark mode if preferred
    marker = '/assets/map/icon-default-2.svg';
}
// Check if the user has a preference for light mode
if (window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches) {
    mapStyle = 'light_all'; // Set to light mode if preferred
    marker = '/assets/map/icon-default-2.svg';
}

let customIcon = L.icon({
    iconUrl: marker, // update this with your icon file path
    iconSize: [25, 25], // Width and height of the icon in pixels
    iconAnchor: [11, 25], // Pixel coordinates where the icon points (typically its bottom center)
    popupAnchor: [0, -50] // Coordinates relative to icon to position the popup
});

// Listen for changes in the user's preference
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
    if (event.matches) {
        mapStyle = 'dark_all'; // Set to dark mode if preferred
        marker = '/assets/map/icon-default.png';
    } else {
        mapStyle = 'light_all'; // Set to light mode if preferred
        marker = '/assets/map/icon-default.png';
    }
    // Update the map layer
    map.eachLayer(layer => {
        if (layer instanceof L.TileLayer) {
            layer.setUrl('https://server.arcgisonline.com/ArcGIS/rest/services/Canvas/World_Dark_Gray_Base/MapServer/tile/{z}/{y}/{x}');
        }
    });
});

const popupOptions = {
    className: 'full-width-right-popup',
    autoPan: false,
};
let activeMarker = null;

/* 
    Level 5 = Europe
    Level 7 = Country
    Level 12 = Kommune
    Level 3 = World
    Level 20 = Building
*/

const markerLatLngs = markersData.map(m => [m.lat, m.lng]);
const bounds = L.latLngBounds(markerLatLngs);

const isMobile = window.innerWidth < 600;

const defaultZoom = 10; // Default zoom level for the map
// Create the map and set the view to the first marker's coordinates
const map = L.map('map', {
    dragging: false,
    zoomControl: false,
    zoomSnap: 0,
    zoomDelta: 3,
    zoomAnimation: false,
    touchZoom: false,
    doubleClickZoom: false,
    scrollWheelZoom: false,
    boxZoom: false,
    keyboard: false,
    tap: false,
    attributionControl: false,
    preferCanvas: true
});

map.fitBounds(bounds, {
    padding: isMobile ? [20, 20] : [190, 190],
    maxZoom: isMobile ? 3 : 10 // Prevent zooming in too far on mobile
});

// Load OpenStreetMap tiles
L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Canvas/World_Dark_Gray_Base/MapServer/tile/{z}/{y}/{x}', {
    zoomControl: false
}).addTo(map);

// Disable scroll for zoom
map.scrollWheelZoom.disable();

// Iterate over the markers array and add each marker to the map
markersData.forEach(marker => {
    const iconUsed = marker.iconUrl ? L.icon({
        iconUrl: marker.iconUrl,
        iconSize: [38, 50],
        iconAnchor: [19, 40],
        popupAnchor: [0, -50]
    }) : customIcon;

    if (marker.popup) {
        L.marker([marker.lat, marker.lng], {
            icon: iconUsed
        }).addTo(map)
            .bindPopup(marker.popup, popupOptions)
            .on('popupopen', function (e) {
                const popupEl = e.popup.getElement();
                // Remove inline properties that Leaflet applies
                popupEl.style.left = '';
                popupEl.style.removeProperty('margin-left');
                popupEl.style.transform = 'translate3d(0,0,0)';
                // Optionally adjust a fixed offset if needed:
                popupEl.style.top = '0';
                popupEl.style.right = '0';
            })
    } else {
        L.marker([marker.lat, marker.lng], {
            icon: iconUsed
        }).addTo(map);
    }
});