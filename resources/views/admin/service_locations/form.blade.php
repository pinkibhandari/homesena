@extends('admin.layouts.master')
@section('title', $location->id ? 'Edit Service Location' : 'Add Service Location')

@section('content')

<div class="card">

    <!-- Header -->
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            {{ $location->id ? 'Edit Service Location' : 'Create Service Location' }}
        </h5>

        <a href="{{ route('admin.service_locations.index') }}" class="btn btn-sm btn-light">
            <i class="ri-arrow-left-line me-1"></i> Back
        </a>
    </div>

    <div class="card-body">

        <form method="POST"
            action="{{ $location->id ? route('admin.service_locations.update', $location->id) : route('admin.service_locations.store') }}">

            @csrf
            @if ($location->id)
                @method('PUT')
            @endif

            <div class="row g-3">

                <!-- Address -->
                <div class="col-lg-6 col-md-6 col-12" style="position:relative;">
                    <label class="form-label">Address</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ri-map-pin-line"></i>
                        </span>
                        <textarea name="address" id="address-input"
                            class="form-control @error('address') is-invalid @enderror"
                            placeholder="Enter Address or pick from map"
                            rows="2"
                            autocomplete="off">{{ old('address', $location->address) }}</textarea>

                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Address autocomplete suggestions -->
                    <div id="address-suggestions"></div>
                </div>

                <!-- Latitude -->
                <div class="col-lg-3 col-md-6 col-12">
                    <label class="form-label">Latitude</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ri-global-line"></i>
                        </span>
                        <input type="text" name="latitude"
                            class="form-control @error('latitude') is-invalid @enderror"
                            placeholder="Latitude"
                            value="{{ old('latitude', $location->latitude) }}">

                        @error('latitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Longitude -->
                <div class="col-lg-3 col-md-6 col-12">
                    <label class="form-label">Longitude</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ri-global-line"></i>
                        </span>
                        <input type="text" name="longitude"
                            class="form-control @error('longitude') is-invalid @enderror"
                            placeholder="Longitude"
                            value="{{ old('longitude', $location->longitude) }}">

                        @error('longitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Status -->
                <div class="col-lg-4 col-md-6 col-12">
                    <label class="form-label">Status</label>

                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ri-shield-check-line"></i>
                        </span>

                        <select name="status"
                            class="form-select @error('status') is-invalid @enderror">

                            <option value="">Select status</option>

                            <option value="1"
                                {{ old('status', $location->status) == 1 ? 'selected' : '' }}>
                                ACTIVE
                            </option>

                            <option value="0"
                                {{ old('status', $location->status) == 0 ? 'selected' : '' }}>
                                INACTIVE
                            </option>

                        </select>

                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

            </div>

            <!-- ── Map Picker ─────────────────────────────────────────────── -->
            <div class="mt-4">
                <label class="form-label d-flex align-items-center gap-2">
                    <i class="ri-map-2-line" style="color:#7367f0;font-size:1.1rem;"></i>
                    <span>Pick Location on Map</span>
                    <small class="text-muted ms-1">(Click or drag the marker to auto-fill address &amp; coordinates)</small>
                </label>
                <div id="service-location-map"
                     style="height:400px;border-radius:8px;border:1.5px solid #e0ddf7;overflow:hidden;box-shadow:0 2px 12px rgba(115,103,240,0.08);">
                </div>
                <div id="map-geocode-status"
                     class="mt-2"
                     style="font-size:0.82rem;color:#7367f0;min-height:1.3em;">
                </div>
            </div>
            <!-- ──────────────────────────────────────────────────────────── -->

            <!-- Submit -->
            <div class="mt-4">
                <button class="btn btn-primary">
                    <i class="ri-save-line me-1"></i>
                    {{ $location->id ? 'Update' : 'Save' }}
                </button>
            </div>

        </form>

    </div>

</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    /* Keep Leaflet attribution readable */
    #service-location-map .leaflet-control-attribution {
        font-size: 0.7rem;
    }

    /* ── Address autocomplete dropdown ───────────────────────────── */
    #address-suggestions {
        display: none;
        position: absolute;
        top: calc(100% + 2px);
        left: 0;
        right: 0;
        background: #fff;
        border: 1.5px solid #e0ddf7;
        border-radius: 8px;
        box-shadow: 0 6px 24px rgba(115, 103, 240, 0.18);
        max-height: 260px;
        overflow-y: auto;
        z-index: 9999;
    }
    .addr-suggestion-item {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        padding: 10px 14px;
        cursor: pointer;
        font-size: 0.875rem;
        color: #444;
        border-bottom: 1px solid #f3f1ff;
        transition: background 0.15s, color 0.15s;
    }
    .addr-suggestion-item:last-child {
        border-bottom: none;
    }
    .addr-suggestion-item:hover {
        background: #f3f1ff;
        color: #7367f0;
    }
    .addr-suggestion-item i {
        flex-shrink: 0;
        margin-top: 2px;
        color: #7367f0;
    }
    .addr-suggestion-notice {
        padding: 10px 14px;
        font-size: 0.82rem;
        color: #7367f0;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(function () {
    'use strict';

    /* ── Initial coordinates ──────────────────────────────────────────── */
    var storedLat = '{{ old('latitude',  $location->latitude  ?? '') }}';
    var storedLng = '{{ old('longitude', $location->longitude ?? '') }}';
    var isEdit    = {{ $location->id ? 'true' : 'false' }};

    var initLat  = parseFloat(storedLat) || 20.5937;   // Default: India centre
    var initLng  = parseFloat(storedLng) || 78.9629;
    var initZoom = isEdit ? 14 : 5;

    /* ── Map initialisation ───────────────────────────────────────────── */
    var map = L.map('service-location-map').setView([initLat, initLng], initZoom);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a> contributors'
    }).addTo(map);

    /* ── Purple teardrop marker icon ──────────────────────────────────── */
    var purpleIcon = L.divIcon({
        className: '',
        html: '<div style="'
            + 'width:28px;height:28px;'
            + 'border-radius:50% 50% 50% 0;'
            + 'background:#7367f0;'
            + 'border:3px solid #fff;'
            + 'box-shadow:0 2px 10px rgba(115,103,240,0.55);'
            + 'transform:rotate(-45deg);'
            + '"></div>',
        iconSize:    [28, 28],
        iconAnchor:  [14, 28],
        popupAnchor: [0, -32]
    });

    var marker = L.marker([initLat, initLng], {
        icon: purpleIcon,
        draggable: true
    }).addTo(map);

    /* ── DOM references ───────────────────────────────────────────────── */
    var statusEl      = document.getElementById('map-geocode-status');
    var addrField     = document.getElementById('address-input');
    var latField      = document.querySelector('input[name="latitude"]');
    var lngField      = document.querySelector('input[name="longitude"]');
    var suggestionsEl = document.getElementById('address-suggestions');

    /* ── Helper: HTML / attribute escaping ───────────────────────────── */
    function escapeHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    /* ── Address autocomplete (forward geocoding) ────────────────────── */
    var acTimer   = null;
    var acLastQ   = '';

    function hideSuggestions() {
        suggestionsEl.style.display = 'none';
        suggestionsEl.innerHTML = '';
    }

    function applySuggestion(displayName, lat, lon) {
        addrField.value = displayName;
        latField.value  = parseFloat(lat).toFixed(7);
        lngField.value  = parseFloat(lon).toFixed(7);
        var latlng = L.latLng(parseFloat(lat), parseFloat(lon));
        marker.setLatLng(latlng);
        map.setView(latlng, 14);
        statusEl.innerHTML = '<i class="ri-checkbox-circle-line"></i> Location set from address suggestion.';
        hideSuggestions();
        acLastQ = displayName;
    }

    function renderSuggestions(results) {
        if (!results || results.length === 0) {
            suggestionsEl.innerHTML = '<div class="addr-suggestion-notice"><i class="ri-search-line"></i> No results found.</div>';
            return;
        }
        var html = '';
        results.forEach(function (r) {
            html += '<div class="addr-suggestion-item"'
                  + ' data-lat="' + escapeHtml(r.lat) + '"'
                  + ' data-lon="' + escapeHtml(r.lon) + '"'
                  + ' data-name="' + escapeHtml(r.display_name) + '">'
                  + '<i class="ri-map-pin-2-line"></i>'
                  + '<span>' + escapeHtml(r.display_name) + '</span>'
                  + '</div>';
        });
        suggestionsEl.innerHTML = html;

        suggestionsEl.querySelectorAll('.addr-suggestion-item').forEach(function (item) {
            item.addEventListener('mousedown', function (e) {
                /* mousedown fires before textarea blur, preventing flicker */
                e.preventDefault();
                applySuggestion(item.dataset.name, item.dataset.lat, item.dataset.lon);
            });
        });
    }

    function fetchSuggestions(query) {
        suggestionsEl.style.display = 'block';
        suggestionsEl.innerHTML = '<div class="addr-suggestion-notice"><i class="ri-loader-4-line"></i> Searching&hellip;</div>';
        var url = 'https://nominatim.openstreetmap.org/search?format=jsonv2&addressdetails=1&limit=6&q='
                + encodeURIComponent(query);
        fetch(url, { headers: { 'Accept-Language': 'en' } })
            .then(function (r) { return r.json(); })
            .then(renderSuggestions)
            .catch(function () {
                suggestionsEl.innerHTML = '<div class="addr-suggestion-notice"><i class="ri-wifi-off-line"></i> Search failed.</div>';
            });
    }

    addrField.addEventListener('input', function () {
        var q = addrField.value.trim();
        if (q.length < 3) { hideSuggestions(); return; }
        if (q === acLastQ) { return; }
        clearTimeout(acTimer);
        acTimer = setTimeout(function () { fetchSuggestions(q); }, 450);
    });

    addrField.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') { hideSuggestions(); }
    });

    addrField.addEventListener('blur', function () {
        /* Small delay lets mousedown on a suggestion fire first */
        setTimeout(hideSuggestions, 200);
    });

    document.addEventListener('click', function (e) {
        if (!suggestionsEl.contains(e.target) && e.target !== addrField) {
            hideSuggestions();
        }
    });

    /* ── Reverse-geocode via Nominatim ────────────────────────────────── */
    function reverseGeocode(lat, lng) {
        statusEl.innerHTML = '<i class="ri-loader-4-line"></i> Fetching address&hellip;';
        latField.value = lat.toFixed(7);
        lngField.value = lng.toFixed(7);

        var url = 'https://nominatim.openstreetmap.org/reverse?format=jsonv2'
                + '&lat=' + lat + '&lon=' + lng;

        fetch(url, { headers: { 'Accept-Language': 'en' } })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data && data.display_name) {
                    addrField.value    = data.display_name;
                    statusEl.innerHTML = '<i class="ri-checkbox-circle-line"></i> Address updated from map selection.';
                } else {
                    statusEl.innerHTML = '<i class="ri-error-warning-line"></i> Address not found for this point.';
                }
            })
            .catch(function () {
                statusEl.innerHTML = '<i class="ri-wifi-off-line"></i> Could not fetch address &mdash; coordinates saved.';
            });
    }

    /* ── Map click → move marker & geocode ───────────────────────────── */
    map.on('click', function (e) {
        marker.setLatLng(e.latlng);
        reverseGeocode(e.latlng.lat, e.latlng.lng);
    });

    /* ── Marker drag-end → geocode ────────────────────────────────────── */
    marker.on('dragend', function () {
        var pos = marker.getLatLng();
        reverseGeocode(pos.lat, pos.lng);
    });

    /* ── Initial hint text ────────────────────────────────────────────── */
    if (isEdit) {
        statusEl.innerHTML = '<i class="ri-information-line"></i> Map showing stored location. Click or drag the marker to change it.';
    } else {
        statusEl.innerHTML = '<i class="ri-cursor-line"></i> Click anywhere on the map to set the service location.';
    }
})();
</script>
@endpush