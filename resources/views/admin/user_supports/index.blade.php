@extends('admin.layouts.master')

@section('title', 'User Support')

@section('content')

    <div class="card shadow-sm">

        <!-- ALERT -->
        <div class="p-3">
            @include('admin.layouts.partials.alerts')
        </div>

        <!-- HEADER -->
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">User Support</h5>
        </div>

        <hr class="my-0">

        <!-- 🔝 STATS -->
        <div class="row px-3 py-3 g-3">

            <div class="col-md-4">
                <div
                    class="d-flex justify-content-between bg-white shadow-sm rounded-3 px-3 py-2 border-start border-4 border-primary">
                    <div>
                        <h6 class="mb-0 text-muted small">Total Tickets</h6>
                        <small class="text-muted">All requests</small>
                    </div>
                    <h4 class="fw-bold text-primary" id="totalCount">{{ $totalTickets }}</h4>
                </div>
            </div>

            <div class="col-md-4">
                <div
                    class="d-flex justify-content-between bg-white shadow-sm rounded-3 px-3 py-2 border-start border-4 border-warning">
                    <div>
                        <h6 class="mb-0 text-muted small">Pending</h6>
                        <small class="text-muted">Waiting response</small>
                    </div>
                    <h4 class="fw-bold text-warning" id="pendingCount">{{ $pendingTickets }}</h4>
                </div>
            </div>

            <div class="col-md-4">
                <div
                    class="d-flex justify-content-between bg-white shadow-sm rounded-3 px-3 py-2 border-start border-4 border-success">
                    <div>
                        <h6 class="mb-0 text-muted small">Resolved</h6>
                        <small class="text-muted">Completed</small>
                    </div>
                    <h4 class="fw-bold text-success" id="resolvedCount">{{ $resolvedTickets }}</h4>
                </div>
            </div>

        </div>

        <hr class="my-0">

        <!-- 🔍 SEARCH + FILTER -->
        <div class="px-3 pt-3">
            <form id="filterForm" method="GET" class="row g-2">

                <div class="col-md-5">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="form-control form-control-sm" placeholder="Search name, email, phone...">
                </div>

                <div class="col-md-3">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
                </div>

                <div class="col-md-2">
                    <button type="button" id="resetFilterBtn" class="btn btn-light btn-sm w-100">Reset</button>
                </div>

            </form>
        </div>

        <!-- 🔽 TABS -->
        <div class="px-3 py-3">

            <ul class="nav nav-tabs mb-3" id="supportTabs">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#pendingTab" id="tab-pendingTab">
                        Pending
                        <span class="badge bg-warning text-dark ms-1" id="pendingBadge">{{ $pendingSupports->total() ?? count($pendingSupports) }}</span>
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#resolvedTab" id="tab-resolvedTab">
                        Resolved
                        <span class="badge bg-success ms-1" id="resolvedBadge">{{ $resolvedSupports->total() ?? count($resolvedSupports) }}</span>
                    </button>
                </li>
            </ul>

            <div class="tab-content">

                <!-- 🔴 PENDING -->
                <div class="tab-pane fade show active" id="pendingTab">
                    <div class="table-responsive" style="max-height:70vh; overflow:auto;">
                        <div id="pendingContainer">
                            @include('admin.user_supports.partials.pending_tab')
                        </div>
                    </div>
                </div>

                <!-- 🟢 RESOLVED -->
                <div class="tab-pane fade" id="resolvedTab">
                    <div class="table-responsive" style="max-height:70vh; overflow:auto;">
                        <div id="resolvedContainer">
                            @include('admin.user_supports.partials.resolved_tab')
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    {{-- ── Admin Reply / Resolution Modal ─────────────────────────────── --}}
    <div class="modal fade" id="resolveModal" tabindex="-1" aria-labelledby="resolveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:16px;border:none;box-shadow:0 8px 40px rgba(115,103,240,0.18);">

                {{-- Header --}}
                <div class="modal-header border-0 pb-0 pt-4 px-4">
                    <div class="d-flex align-items-center gap-3">
                        <div
                            style="width:46px;height:46px;border-radius:50%;background:rgba(115,103,240,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="ri-customer-service-2-line" style="color:#7367f0;font-size:1.4rem;"></i>
                        </div>
                        <div>
                            <h5 class="modal-title mb-0 fw-semibold" id="resolveModalLabel">Resolve Ticket</h5>
                            <small class="text-muted">Add a response before marking as resolved</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                {{-- Body --}}
                <div class="modal-body px-4 py-3">

                    {{-- Reply textarea --}}
                    <div class="mb-3">
                        <label class="form-label fw-medium">
                            Your Response
                            <span class="text-muted fw-normal">(optional)</span>
                        </label>
                        <textarea id="adminReplyText" rows="4" class="form-control" placeholder="Type your response to the user…"
                            style="resize:none;border-color:#e0ddf7;border-radius:8px;"></textarea>
                    </div>

                    {{-- File upload --}}
                    <div class="mb-2">
                        <label class="form-label fw-medium">
                            Attach Files
                            <span class="text-muted fw-normal">(images / videos — optional)</span>
                        </label>

                        <div id="fileDropZone"
                            style="border:2px dashed #c5bff5;border-radius:10px;padding:22px 16px;text-align:center;cursor:pointer;background:#faf9ff;transition:border-color .2s,background .2s;">
                            <i class="ri-upload-cloud-2-line"
                                style="font-size:2.2rem;color:#7367f0;display:block;margin-bottom:6px;"></i>
                            <p class="mb-1 text-muted" style="font-size:.875rem;">
                                Drag &amp; drop files here, or
                                <span id="browseLink" style="color:#7367f0;font-weight:500;cursor:pointer;">browse</span>
                            </p>
                            <p class="mb-0 text-muted" style="font-size:.75rem;">
                                JPG, PNG, GIF, MP4, MOV, AVI, WEBM &mdash; max 20 MB each
                            </p>
                            <input type="file" id="adminFilesInput" multiple
                                accept="image/jpeg,image/png,image/gif,video/mp4,video/quicktime,video/avi,video/webm"
                                style="display:none;">
                        </div>

                        {{-- Thumbnail previews --}}
                        <div id="filePreviewList" class="mt-2 d-flex flex-wrap gap-2"></div>
                    </div>

                </div>

                {{-- Footer --}}
                <div class="modal-footer border-0 px-4 pb-4 pt-1 gap-2">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="submitResolveBtn" class="btn btn-primary d-flex align-items-center gap-2">
                        <i class="ri-checkbox-circle-line"></i> Submit &amp; Resolve
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- JS --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        (function () {
            'use strict';

            var currentTicketId = null;
            var selectedFiles   = [];
            var resolveModal;
            var filterBaseUrl   = '{{ url()->current() }}';

            /* ── Helpers ─────────────────────────────────────────────────── */
            function activeTabId() {
                var active = document.querySelector('#supportTabs .nav-link.active');
                return active ? active.getAttribute('data-bs-target').replace('#', '') : 'pendingTab';
            }

            function restoreTabFromUrl() {
                var params = new URLSearchParams(window.location.search);
                var tab    = params.get('tab');
                if (tab) {
                    var el = document.querySelector('[data-bs-target="#' + tab + '"]');
                    if (el) { bootstrap.Tab.getOrCreateInstance(el).show(); }
                }
            }

            /* ── Single-container pagination fetch ───────────────────────── */
            function fetchTabPage(containerId, href) {
                var container = document.getElementById(containerId);
                if (!container) return;

                var fetchUrl = new URL(href);
                fetchUrl.searchParams.set('ajax', '1');

                container.style.opacity = '0.5';

                fetch(fetchUrl.toString(), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (containerId === 'pendingContainer') {
                        container.innerHTML = data.pendingHtml;
                    } else {
                        container.innerHTML = data.resolvedHtml;
                    }
                    container.style.opacity = '1';

                    // Sync URL (strip ajax, keep tab)
                    var newUrl = new URL(fetchUrl.toString());
                    newUrl.searchParams.delete('ajax');
                    newUrl.searchParams.set('tab', activeTabId());
                    history.replaceState(null, '', newUrl.toString());
                })
                .catch(function () {
                    container.style.opacity = '1';
                });
            }

            /* ── AJAX filter ─────────────────────────────────────────────── */
            function applyFilter(params) {
                var url            = new URL(filterBaseUrl);
                var selectedStatus = '';

                params.forEach(function (val, key) {
                    if (val !== '') { url.searchParams.set(key, val); }
                    if (key === 'status') { selectedStatus = val; }
                });
                url.searchParams.set('ajax', '1');

                // Determine which tab should be active after the filter is applied:
                //   status=resolved → switch to Resolved tab
                //   status=pending  → switch to Pending tab
                //   status=''       → stay on whichever tab is currently active
                var targetTabId = activeTabId();
                if (selectedStatus === 'resolved') { targetTabId = 'resolvedTab'; }
                if (selectedStatus === 'pending')  { targetTabId = 'pendingTab';  }

                // Fade both containers
                document.getElementById('pendingContainer').style.opacity  = '0.5';
                document.getElementById('resolvedContainer').style.opacity = '0.5';

                fetch(url.toString(), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    document.getElementById('pendingContainer').innerHTML  = data.pendingHtml;
                    document.getElementById('resolvedContainer').innerHTML = data.resolvedHtml;

                    document.getElementById('pendingContainer').style.opacity  = '1';
                    document.getElementById('resolvedContainer').style.opacity = '1';

                    // Update stat badges
                    document.getElementById('pendingBadge').textContent  = data.pendingTickets;
                    document.getElementById('resolvedBadge').textContent = data.resolvedTickets;
                    document.getElementById('totalCount').textContent    = data.totalTickets;
                    document.getElementById('pendingCount').textContent  = data.pendingTickets;
                    document.getElementById('resolvedCount').textContent = data.resolvedTickets;

                    // Switch to the target tab (no-op if it is already active)
                    var tabEl = document.querySelector('[data-bs-target="#' + targetTabId + '"]');
                    if (tabEl) { bootstrap.Tab.getOrCreateInstance(tabEl).show(); }

                    // Persist state in URL so a page refresh keeps the right tab
                    var newUrl = new URL(url.toString());
                    newUrl.searchParams.delete('ajax');
                    newUrl.searchParams.set('tab', targetTabId);
                    history.replaceState(null, '', newUrl.toString());
                })
                .catch(function () {
                    document.getElementById('pendingContainer').style.opacity  = '1';
                    document.getElementById('resolvedContainer').style.opacity = '1';
                });
            }

            /* ── Bootstrap / DOM init ────────────────────────────────────── */
            document.addEventListener('DOMContentLoaded', function () {
                resolveModal = new bootstrap.Modal(document.getElementById('resolveModal'));

                // Restore active tab from URL on first load
                restoreTabFromUrl();

                // Keep URL in sync when tab is manually switched
                document.querySelectorAll('#supportTabs [data-bs-toggle="tab"]').forEach(function (btn) {
                    btn.addEventListener('shown.bs.tab', function (e) {
                        var tabId  = e.target.getAttribute('data-bs-target').replace('#', '');
                        var newUrl = new URL(window.location.href);
                        newUrl.searchParams.set('tab', tabId);
                        history.replaceState(null, '', newUrl.toString());
                    });
                });

                /* ── Filter form AJAX submit ─────────────────────────────── */
                document.getElementById('filterForm').addEventListener('submit', function (e) {
                    e.preventDefault();
                    applyFilter(new FormData(this));
                });

                /* ── Reset button ────────────────────────────────────────── */
                document.getElementById('resetFilterBtn').addEventListener('click', function () {
                    document.getElementById('filterForm').reset();
                    applyFilter(new FormData(document.getElementById('filterForm')));
                });

                /* ── AJAX pagination for Pending tab ─────────────────────── */
                document.getElementById('pendingContainer').addEventListener('click', function (e) {
                    var link = e.target.closest('a.page-link');
                    if (!link || !link.href) return;
                    e.preventDefault();
                    fetchTabPage('pendingContainer', link.href);
                });

                /* ── AJAX pagination for Resolved tab ────────────────────── */
                document.getElementById('resolvedContainer').addEventListener('click', function (e) {
                    var link = e.target.closest('a.page-link');
                    if (!link || !link.href) return;
                    e.preventDefault();
                    fetchTabPage('resolvedContainer', link.href);
                });

                /* ── Drop-zone wiring ────────────────────────────────────── */
                var dropZone  = document.getElementById('fileDropZone');
                var fileInput = document.getElementById('adminFilesInput');

                document.getElementById('browseLink').addEventListener('click', function (e) {
                    e.stopPropagation();
                    fileInput.click();
                });
                dropZone.addEventListener('click', function () { fileInput.click(); });

                ['dragenter', 'dragover'].forEach(function (ev) {
                    dropZone.addEventListener(ev, function (e) {
                        e.preventDefault();
                        dropZone.style.borderColor = '#7367f0';
                        dropZone.style.background  = '#f0eeff';
                    });
                });
                ['dragleave', 'drop'].forEach(function (ev) {
                    dropZone.addEventListener(ev, function (e) {
                        e.preventDefault();
                        dropZone.style.borderColor = '#c5bff5';
                        dropZone.style.background  = '#faf9ff';
                    });
                });
                dropZone.addEventListener('drop', function (e) { addFiles(e.dataTransfer.files); });
                fileInput.addEventListener('change', function () {
                    addFiles(fileInput.files);
                    fileInput.value = '';
                });

                /* ── Submit handler (resolve modal) ──────────────────────── */
                document.getElementById('submitResolveBtn').addEventListener('click', function () {
                    var btn = this;
                    btn.disabled  = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Saving…';

                    var formData = new FormData();
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('_method', 'PUT');
                    formData.append('admin_reply', document.getElementById('adminReplyText').value);

                    selectedFiles.filter(Boolean).forEach(function (f) {
                        formData.append('admin_files[]', f);
                    });

                    $.ajax({
                        url         : '/admin/user_supports/' + currentTicketId,
                        type        : 'POST',
                        data        : formData,
                        processData : false,
                        contentType : false,
                        success     : function (res) {
                            resolveModal.hide();
                            if (res.success) {
                                Swal.fire('Resolved!', res.message, 'success')
                                    .then(function () { location.reload(); });
                            } else {
                                Swal.fire('Error', res.message, 'error');
                            }
                        },
                        error    : function () {
                            Swal.fire('Error', 'Something went wrong. Please try again.', 'error');
                        },
                        complete : function () {
                            btn.disabled  = false;
                            btn.innerHTML = '<i class="ri-checkbox-circle-line"></i> Submit &amp; Resolve';
                        }
                    });
                });
            });

            /* ── Accumulate files & render thumbnails ────────────────────── */
            function addFiles(fileList) {
                Array.from(fileList).forEach(function (f) {
                    var idx = selectedFiles.push(f) - 1;
                    renderThumb(f, idx);
                });
            }

            function renderThumb(file, idx) {
                var list  = document.getElementById('filePreviewList');
                var isVid = file.type.startsWith('video/');

                var wrap = document.createElement('div');
                wrap.style.cssText =
                    'position:relative;width:78px;height:78px;border-radius:8px;overflow:hidden;border:1.5px solid #e0ddf7;flex-shrink:0;';

                if (isVid) {
                    var vid = document.createElement('video');
                    vid.src = URL.createObjectURL(file);
                    vid.style.cssText = 'width:100%;height:100%;object-fit:cover;';
                    wrap.appendChild(vid);
                } else {
                    var img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.style.cssText = 'width:100%;height:100%;object-fit:cover;';
                    wrap.appendChild(img);
                }

                var rm = document.createElement('button');
                rm.type      = 'button';
                rm.innerHTML = '&times;';
                rm.style.cssText = 'position:absolute;top:3px;right:3px;width:20px;height:20px;' +
                    'border-radius:50%;border:none;background:rgba(0,0,0,0.55);' +
                    'color:#fff;font-size:.75rem;line-height:1;padding:0;cursor:pointer;' +
                    'display:flex;align-items:center;justify-content:center;';
                rm.addEventListener('click', function (e) {
                    e.stopPropagation();
                    selectedFiles[idx] = null;
                    wrap.remove();
                });

                wrap.appendChild(rm);
                list.appendChild(wrap);
            }

            /* ── Step 1: Pending button → SweetAlert confirmation ────────── */
            $(document).on('click', '.resolveBtn', function () {
                currentTicketId = $(this).data('id');

                Swal.fire({
                    title             : 'Are you sure?',
                    text              : 'Mark this ticket as resolved?',
                    icon              : 'warning',
                    showCancelButton  : true,
                    confirmButtonText : 'Yes'
                }).then(function (result) {
                    if (result.isConfirmed) {
                        document.getElementById('adminReplyText').value      = '';
                        document.getElementById('filePreviewList').innerHTML = '';
                        selectedFiles = [];
                        resolveModal.show();
                    }
                });
            });

        })();
    </script>

@endsection
