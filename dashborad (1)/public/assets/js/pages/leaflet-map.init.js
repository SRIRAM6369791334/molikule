var mymap = L.map("leaflet-map").setView([51.505, -0.09], 13);
L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    maxZoom: 18,
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    tileSize: 512,
    zoomOffset: -1
}).addTo(mymap);

var markermap = L.map("leaflet-map-marker").setView([51.505, -0.09], 13);
L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    maxZoom: 18,
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    tileSize: 512,
    zoomOffset: -1
}).addTo(markermap);
L.marker([51.5, -0.09]).addTo(markermap);
L.circle([51.508, -0.11], {
    color: "#34c38f",
    fillColor: "#34c38f",
    fillOpacity: 0.5,
    radius: 500
}).addTo(markermap);
L.polygon([[51.509, -0.08], [51.503, -0.06], [51.51, -0.047]], {
    color: "#1c84ee",
    fillColor: "#1c84ee"
}).addTo(markermap);

var popupmap = L.map("leaflet-map-popup").setView([51.505, -0.09], 13);
L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    maxZoom: 18,
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    tileSize: 512,
    zoomOffset: -1
}).addTo(popupmap);
L.marker([51.5, -0.09]).addTo(popupmap).bindPopup("<b>Hello world!</b><br />I am a popup.").openPopup();
L.circle([51.508, -0.11], 500, {
    color: "#ef6767",
    fillColor: "#ef6767",
    fillOpacity: 0.5
}).addTo(popupmap).bindPopup("I am a circle.");
L.polygon([[51.509, -0.08], [51.503, -0.06], [51.51, -0.047]], {
    color: "#1c84ee",
    fillColor: "#1c84ee"
}).addTo(popupmap).bindPopup("I am a polygon.");

var popup = L.popup();
var customiconsmap = L.map("leaflet-map-custom-icons").setView([51.5, -0.09], 13);
L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(customiconsmap);

var LeafIcon = L.Icon.extend({
    options: {
        iconSize: [45, 95],
        iconAnchor: [22, 94],
        popupAnchor: [-3, -76]
    }
});
var greenIcon = new LeafIcon({ iconUrl: "assets/images/logo-sm.svg" });
L.marker([51.5, -0.09], { icon: greenIcon }).addTo(customiconsmap);

var interactivemap = L.map("leaflet-map-interactive-map").setView([37.8, -96], 4);
function getColor(e) {
    return e > 1000 ? "#7FB9F5" : e > 500 ? "#1c84ee" : e > 200 ? "#318FF0" : e > 100 ? "#4499F1" : e > 50 ? "#55A2F2" : e > 20 ? "#64AAF3" : e > 10 ? "#72B2F4" : "#7FB9F5";
}
function style(e) {
    return {
        weight: 2,
        opacity: 1,
        color: "white",
        dashArray: "3",
        fillOpacity: 0.7,
        fillColor: getColor(e.properties.density)
    };
}
L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    maxZoom: 18,
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    tileSize: 512,
    zoomOffset: -1
}).addTo(interactivemap);

var geojson = L.geoJson(statesData, { style: style }).addTo(interactivemap);
var cities = L.layerGroup();
L.marker([39.61, -105.02]).bindPopup("This is Littleton, CO.").addTo(cities);
L.marker([39.74, -104.99]).bindPopup("This is Denver, CO.").addTo(cities);
L.marker([39.73, -104.8]).bindPopup("This is Aurora, CO.").addTo(cities);
L.marker([39.77, -105.23]).bindPopup("This is Golden, CO.").addTo(cities);

var mbAttr = '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors';
var mbUrl = "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png";
var grayscale = L.tileLayer(mbUrl, {
    tileSize: 512,
    zoomOffset: -1,
    attribution: mbAttr
});
var streets = L.tileLayer(mbUrl, {
    tileSize: 512,
    zoomOffset: -1,
    attribution: mbAttr
});

var layergroupcontrolmap = L.map("leaflet-map-group-control", {
    center: [39.73, -104.99],
    zoom: 10,
    layers: [streets, cities]
});
var baseLayers = { Grayscale: grayscale, Streets: streets };
var overlays = { Cities: cities };
L.control.layers(baseLayers, overlays).addTo(layergroupcontrolmap);