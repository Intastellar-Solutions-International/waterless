const markersData = [
    {
        lat: 39.5038383085198,
        lng: -3.489984938574852,
        countryName: "Spain",
        popup: `<h2>Spain</h2>
                <ul>
                    <li>Estadio San Mames, Bilbao</li>
                    <li>Camp Nou, Barcelona</li>
                </ul>
                <a href="https://waterless.dk/urinal-installations-in-spain" target="_blank">Learn more</a>`,
    },
    {
        countryName: "Heathrow - Airport",
        lat: 51.49936794976044,
        lng: -0.20351797866790156,
        popup: `<h2>UK</h2>
            <p>Heathrow Airport</p>
        `,
    },
    {
        countryName: "Denmark",
        lat: 56.427314407570236,
        lng: 9.38850736840877,
        popup: `<h2>Denmark</h2>
            <ul>
                <li>Aalborg Airport</li>
                <li>Parken Copenhagen</li>
                <li>MCH Arena</li>
            </ul>        
        `,
    },
    {
        countryName: "Germany",
        lat: 51.1657,
        lng: 10.4515,
        popup: `<h2>Germany</h2>
            <ul>
                <li>Stadion an der Alten - Berlin</li>
            </ul>
        `,
    },
    {
        countryName: "USA - Los Angeles",
        lat: 34.082876011958774,
        lng: -118.19188452853405,
        popup: `<h2>California</h2>
            <ul>
                <li>Staples Center, Los Angeles</li>
                <li>Hollywood Bowl</li>
                <li>Rose Bowl Stadium</li>
                <li>Los Angeles Coliseum</li>
            </ul>
        `,
    },
    {
        countryName: "USA - Virginia",
        lat: 37.38575377951244,
        lng: -79.04130069152093,
        popup: `<h2>Virginia</h2>
            <ul>
                <li>Wolftrap Performing Arts Center</li>
            </ul>
        `,
    },
    {
        countryName: "Chile",
        lat: -26.819013122846254,
        lng: -69.6975493640295,
        popup: `<h2>Chile</h2>
            <ul>
                <li>Estadio Nacional</li>
            </ul>
        `,
    },
    {
        countryName: "South Africa",
        lat: -30.5595,
        lng: 22.9375,
        popup: `<h2>South Africa</h2>
                <ul>
                    <li>Durban</li>
                    <li>Port Elizabeth</li>
                    <li>Botswana</li>
                    <li>Johannesburg</li>
                </ul>
        `,
    },
    {
        countryName: "Middle East",
        lat: 24.86604585480851,
        lng: 43.60335014151479,
        popup: `<h2>Middle East</h2>
                <p>We have installed urinals in the Middle East, providing a 100% odor-free experience.</p>
                <a href="https://waterless.dk/urinal-installations-in-middle-east" target="_blank">Learn more</a>`,
    },
    {
        countryName: "Australia",
        lat: -23.95040972510461,
        lng: 133.09108257620306,
        popup: `<h2>Australia</h2>
                <p>We have installed urinals in Australia, providing a 100% odor-free experience.</p>
                <a href="https://waterless.dk/urinal-installations-in-australia" target="_blank">Learn more</a>`,
    },
    {
        countryName: "New Zealand",
        lat: -43.32697854040854,
        lng: 171.55533475969162,
        popup: `<h2>New Zealand</h2>
                <p>We have installed urinals in New Zealand, providing a 100% odor-free experience.</p>
                <a href="https://waterless.dk/urinal-installations-in-new-zealand" target="_blank">Learn more</a>`,
    },
    {
        countryName: "Philippines",
        lat: 12.8797,
        lng: 121.7740,
        popup: `<h2>Philippines</h2>
                <p>We have installed urinals in the Philippines, providing a 100% odor-free experience.</p>
                <a href="https://waterless.dk/urinal-installations-in-philippines" target="_blank">Learn more</a>`,
    },
    {
        countryName: "India",
        lat: 24.33158069129953,
        lng: 78.00452253227078,
        popup: `<h2>India</h2>
                <p>We have installed urinals in India, providing a 100% odor-free experience.</p>
                <a href="https://waterless.dk/urinal-installations-in-india" target="_blank">Learn more</a>`,
    }
];

let mapStyle = 'light_all'; // Default map style
let marker = '/assets/map/icon.png';
// Check if the user has a preference for dark mode
if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
    mapStyle = 'dark_all'; // Set to dark mode if preferred
    marker = '/assets/map/icon.png';
}
// Check if the user has a preference for light mode
if (window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches) {
    mapStyle = 'light_all'; // Set to light mode if preferred
    marker = '/assets/map/icon.png';
}

let customIcon = L.icon({
    iconUrl: marker, // update this with your icon file path
    iconSize: [38, 50], // Width and height of the icon in pixels
    iconAnchor: [11, 25], // Pixel coordinates where the icon points (typically its bottom center)
    popupAnchor: [0, -50] // Coordinates relative to icon to position the popup
});

// Listen for changes in the user's preference
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
    if (event.matches) {
        mapStyle = 'dark_all'; // Set to dark mode if preferred
        marker = '/assets/map/icon.png';
    } else {
        mapStyle = 'light_all'; // Set to light mode if preferred
        marker = '/assets/map/icon.png';
    }
    // Update the map layer
    map.eachLayer(layer => {
        if (layer instanceof L.TileLayer) {
            layer.setUrl('https://server.arcgisonline.com/ArcGIS/rest/services/Canvas/World_Light_Gray_Base/MapServer/tile/{z}/{y}/{x}');
        }
    });
});

const popupOptions = {
    /* className: 'full-width-right-popup', */
    autoPan: true,
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
    dragging: true,
    zoomControl: true,
    zoomSnap: 0,
    zoomDelta: 1,
    zoomAnimation: true,
    touchZoom: false,
    doubleClickZoom: false,
    scrollWheelZoom: true,
    boxZoom: false,
    keyboard: false,
    tap: false,
    attributionControl: false,
    preferCanvas: true
});

map.fitBounds(bounds, {
    padding: isMobile ? [20, 20] : [100, 100],
});

// Load OpenStreetMap tiles
L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Canvas/World_Light_Gray_Base/MapServer/tile/{z}/{y}/{x}', {
    zoomControl: true
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