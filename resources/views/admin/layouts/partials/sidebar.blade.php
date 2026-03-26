    <!-- Layout wrapper -->

    <!-- Menu -->

    <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
        <div class="app-brand demo">
            <a href="index.html" class="app-brand-link">
                <span class="app-brand-logo demo me-1">
                    <span class="text-primary">
                        <!-- <svg width="30" height="24" viewBox="0 0 250 196" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M12.3002 1.25469L56.655 28.6432C59.0349 30.1128 60.4839 32.711 60.4839 35.5089V160.63C60.4839 163.468 58.9941 166.097 56.5603 167.553L12.2055 194.107C8.3836 196.395 3.43136 195.15 1.14435 191.327C0.395485 190.075 0 188.643 0 187.184V8.12039C0 3.66447 3.61061 0.0522461 8.06452 0.0522461C9.56056 0.0522461 11.0271 0.468577 12.3002 1.25469Z"
                      fill="currentColor" />
                    <path
                      opacity="0.077704"
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M0 65.2656L60.4839 99.9629V133.979L0 65.2656Z"
                      fill="black" />
                    <path
                      opacity="0.077704"
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M0 65.2656L60.4839 99.0795V119.859L0 65.2656Z"
                      fill="black" />
                    <path
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M237.71 1.22393L193.355 28.5207C190.97 29.9889 189.516 32.5905 189.516 35.3927V160.631C189.516 163.469 191.006 166.098 193.44 167.555L237.794 194.108C241.616 196.396 246.569 195.151 248.856 191.328C249.605 190.076 250 188.644 250 187.185V8.09597C250 3.64006 246.389 0.027832 241.935 0.027832C240.444 0.027832 238.981 0.441882 237.71 1.22393Z"
                      fill="currentColor" />
                    <path
                      opacity="0.077704"
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M250 65.2656L189.516 99.8897V135.006L250 65.2656Z"
                      fill="black" />
                    <path
                      opacity="0.077704"
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M250 65.2656L189.516 99.0497V120.886L250 65.2656Z"
                      fill="black" />
                    <path
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M12.2787 1.18923L125 70.3075V136.87L0 65.2465V8.06814C0 3.61223 3.61061 0 8.06452 0C9.552 0 11.0105 0.411583 12.2787 1.18923Z"
                      fill="currentColor" />
                    <path
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M12.2787 1.18923L125 70.3075V136.87L0 65.2465V8.06814C0 3.61223 3.61061 0 8.06452 0C9.552 0 11.0105 0.411583 12.2787 1.18923Z"
                      fill="white"
                      fill-opacity="0.15" />
                    <path
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M237.721 1.18923L125 70.3075V136.87L250 65.2465V8.06814C250 3.61223 246.389 0 241.935 0C240.448 0 238.99 0.411583 237.721 1.18923Z"
                      fill="currentColor" />
                    <path
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M237.721 1.18923L125 70.3075V136.87L250 65.2465V8.06814C250 3.61223 246.389 0 241.935 0C240.448 0 238.99 0.411583 237.721 1.18923Z"
                      fill="white"
                      fill-opacity="0.3" />
                  </svg> -->
                    </span>
                </span>
                <span class="app-brand-text demo menu-text fw-semibold ms-2">HomeSena</span>
            </a>

            {{-- <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                <i class="menu-toggle-icon d-xl-inline-block align-middle"></i>
            </a> --}}
        </div>

        <div class="menu-inner-shadow"></div>

        <ul class="menu-inner py-1">
            <!-- Dashboards -->
            <li class="menu-item">
                <a href="{{ route('admin.dashboard') }}" class="menu-link">
                    <i class="menu-icon icon-base ri ri-home-smile-line"></i>
                    <div data-i18n="Dashboards">Dashboards</div>
                </a>
            </li>
            {{-- User --}}
            <li class="menu-item">
                <a href="{{ route('admin.users.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ri ri-user-line"></i>
                    <div data-i18n="User">Users</div>
                </a>
            </li>
            {{-- Experts --}}
            <li class="menu-item">
                <a href="{{ route('admin.experts.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ri ri-user-line"></i>
                    <div data-i18n="Expert">Experts</div>
                </a>
            </li>
            {{-- Services --}}
            <li class="menu-item">
                <a href="{{ route('admin.services.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ri ri-customer-service-2-line"></i>
                    <div data-i18n="Service">Services</div>
                </a>
            </li>
            {{-- Time slots --}}
            <li class="menu-item">
                <a href="{{ route('admin.time_slots.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ri ri-time-line"></i>
                    <div data-i18n="Time Slots">Time Slots</div>
                </a>
            </li>
            {{-- service durations --}}
            <li class="menu-item">
                <a href="{{ route('admin.service_variants.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ri ri-timer-line"></i>
                    <div data-i18n="Service Duration">Service Duration</div>
                </a>
            </li>
            {{-- Bookings --}}
            <!-- <li class="menu-item">
                <a href="{{ route('admin.bookings.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ri ri-calendar-check-line"></i>
                    <div data-i18n="Bookings">Bookings</div>
                </a>
            </li> -->


            {{-- Training Centers --}}
            <li class="menu-item">
                <a href="{{ route('admin.training_centers.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ri ri-building-line"></i>
                    <div data-i18n="Training Centers">Training Centers</div>
                </a>
            </li>
            {{-- Cms Pages --}}
            <li class="menu-item">
                <a href="{{ route('admin.cms_pages.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ri ri-file-text-line"></i>
                    <div data-i18n="CMS Pages">CMS Pages</div>
                </a>
            </li>
            {{-- Payments --}}
            <!-- <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon icon-base ri ri-secure-payment-line"></i>
                    <div data-i18n="Payments">Payments</div>
                </a> -->

                <!-- <ul class="menu-sub"> -->

                    <!-- Payment -->
                    <!-- <li class="menu-item">
                        <a href="{{ route('admin.payments.index') }}" class="menu-link">
                            <div data-i18n="Payment">Payment</div>
                        </a>
                    </li> -->

                    <!-- Payment Method -->
                    <!-- <li class="menu-item">
                        <a href="" class="menu-link">
                            <div data-i18n="Payment Method">Payment Method</div>
                        </a>
                    </li> -->

                <!-- </ul> -->
            <!-- </li> -->
            <!-- Layouts -->
            {{-- <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon icon-base ri ri-layout-2-line"></i>
                <div data-i18n="Layouts">Layouts</div>
              </a>

              <ul class="menu-sub">
                <li class="menu-item">
                  <a href="layouts-without-menu.html" class="menu-link">
                    <div data-i18n="Without menu">Without menu</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="layouts-without-navbar.html" class="menu-link">
                    <div data-i18n="Without navbar">Without navbar</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="layouts-container.html" class="menu-link">
                    <div data-i18n="Container">Container</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="layouts-fluid.html" class="menu-link">
                    <div data-i18n="Fluid">Fluid</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="layouts-blank.html" class="menu-link">
                    <div data-i18n="Blank">Blank</div>
                  </a>
                </li>
              </ul>
            </li> --}}

            <!-- Front Pages -->
            {{-- <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon icon-base ri ri-file-copy-line"></i>
                <div data-i18n="Front Pages">Front Pages</div>
                <div class="badge rounded-pill bg-label-primary fs-tiny ms-auto">Pro</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item">
                  <a
                    href="https://demos.themeselection.com/materio-bootstrap-html-admin-template/html/front-pages/landing-page.html"
                    class="menu-link"
                    target="_blank">
                    <div data-i18n="Landing">Landing</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a
                    href="https://demos.themeselection.com/materio-bootstrap-html-admin-template/html/front-pages/pricing-page.html"
                    class="menu-link"
                    target="_blank">
                    <div data-i18n="Pricing">Pricing</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a
                    href="https://demos.themeselection.com/materio-bootstrap-html-admin-template/html/front-pages/payment-page.html"
                    class="menu-link"
                    target="_blank">
                    <div data-i18n="Payment">Payment</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a
                    href="https://demos.themeselection.com/materio-bootstrap-html-admin-template/html/front-pages/checkout-page.html"
                    class="menu-link"
                    target="_blank">
                    <div data-i18n="Checkout">Checkout</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a
                    href="https://demos.themeselection.com/materio-bootstrap-html-admin-template/html/front-pages/help-center-landing.html"
                    class="menu-link"
                    target="_blank">
                    <div data-i18n="Help Center">Help Center</div>
                  </a>
                </li>
              </ul>
            </li> --}}

            <!-- Apps & Pages -->
            <!-- <li class="menu-header mt-7">
                <span class="menu-header-text">Apps &amp; Pages</span>
            </li> -->
            {{-- <li class="menu-item">
              <a
                href="https://demos.themeselection.com/materio-bootstrap-html-admin-template/html/vertical-menu-template/app-email.html"
                target="_blank"
                class="menu-link">
                <i class="menu-icon icon-base ri ri-mail-open-line"></i>
                <div data-i18n="Email">Email</div>
                <div class="badge rounded-pill bg-label-primary fs-tiny ms-auto">Pro</div>
              </a>
            </li>
            <li class="menu-item">
              <a
                href="https://demos.themeselection.com/materio-bootstrap-html-admin-template/html/vertical-menu-template/app-chat.html"
                target="_blank"
                class="menu-link">
                <i class="menu-icon icon-base ri ri-wechat-line"></i>
                <div data-i18n="Chat">Chat</div>
                <div class="badge rounded-pill bg-label-primary fs-tiny ms-auto">Pro</div>
              </a>
            </li>
            <li class="menu-item">
              <a
                href="https://demos.themeselection.com/materio-bootstrap-html-admin-template/html/vertical-menu-template/app-calendar.html"
                target="_blank"
                class="menu-link">
                <i class="menu-icon icon-base ri ri-calendar-line"></i>
                <div data-i18n="Calendar">Calendar</div>
                <div class="badge rounded-pill bg-label-primary fs-tiny ms-auto">Pro</div>
              </a>
            </li>
            <li class="menu-item">
              <a
                href="https://demos.themeselection.com/materio-bootstrap-html-admin-template/html/vertical-menu-template/app-kanban.html"
                target="_blank"
                class="menu-link">
                <i class="menu-icon icon-base ri ri-drag-drop-line"></i>
                <div data-i18n="Kanban">Kanban</div>
                <div class="badge rounded-pill bg-label-primary fs-tiny ms-auto">Pro</div>
              </a>
            </li> --}}
            <!-- Pages -->
            {{-- <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon icon-base ri ri-layout-left-line"></i>
                <div data-i18n="Account Settings">Account Settings</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item">
                  <a href="pages-account-settings-account.html" class="menu-link">
                    <div data-i18n="Account">Account</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="pages-account-settings-notifications.html" class="menu-link">
                    <div data-i18n="Notifications">Notifications</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="pages-account-settings-connections.html" class="menu-link">
                    <div data-i18n="Connections">Connections</div>
                  </a>
                </li>
              </ul>
            </li>
            <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon icon-base ri ri-shield-keyhole-line"></i>
                <div data-i18n="Authentications">Authentications</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item">
                  <a href="auth-login-basic.html" class="menu-link" target="_blank">
                    <div data-i18n="Basic">Login</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="auth-register-basic.html" class="menu-link" target="_blank">
                    <div data-i18n="Basic">Register</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="auth-forgot-password-basic.html" class="menu-link" target="_blank">
                    <div data-i18n="Basic">Forgot Password</div>
                  </a>
                </li>
              </ul>
            </li>
            <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon icon-base ri ri-box-3-line"></i>
                <div data-i18n="Misc">Misc</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item">
                  <a href="pages-misc-error.html" class="menu-link" target="_blank">
                    <div data-i18n="Error">Error</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="pages-misc-under-maintenance.html" class="menu-link" target="_blank">
                    <div data-i18n="Under Maintenance">Under Maintenance</div>
                  </a>
                </li>
              </ul>
            </li> --}}
            <!-- Components -->
            <!-- <li class="menu-header mt-7"><span class="menu-header-text">Components</span></li> -->
            <!-- Cards -->
            {{-- <li class="menu-item">
                <a href="cards-basic.html" class="menu-link">
                    <i class="menu-icon icon-base ri ri-bank-card-2-line"></i>
                    <div data-i18n="Basic">Cards</div>
                </a>
            </li> --}}
            <!-- User interface -->
            {{-- <li class="menu-item">
                <a href="javascript:void(0)" class="menu-link menu-toggle">
                    <i class="menu-icon icon-base ri ri-toggle-line"></i>
                    <div data-i18n="User interface">User interface</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="ui-accordion.html" class="menu-link">
                            <div data-i18n="Accordion">Accordion</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="ui-alerts.html" class="menu-link">
                            <div data-i18n="Alerts">Alerts</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="ui-badges.html" class="menu-link">
                            <div data-i18n="Badges">Badges</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="ui-buttons.html" class="menu-link">
                            <div data-i18n="Buttons">Buttons</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="ui-carousel.html" class="menu-link">
                            <div data-i18n="Carousel">Carousel</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="ui-collapse.html" class="menu-link">
                            <div data-i18n="Collapse">Collapse</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="ui-dropdowns.html" class="menu-link">
                            <div data-i18n="Dropdowns">Dropdowns</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="ui-footer.html" class="menu-link">
                            <div data-i18n="Footer">Footer</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="ui-list-groups.html" class="menu-link">
                            <div data-i18n="List Groups">List Groups</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="ui-modals.html" class="menu-link">
                            <div data-i18n="Modals">Modals</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="ui-navbar.html" class="menu-link">
                            <div data-i18n="Navbar">Navbar</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="ui-offcanvas.html" class="menu-link">
                            <div data-i18n="Offcanvas">Offcanvas</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="ui-pagination-breadcrumbs.html" class="menu-link">
                            <div data-i18n="Pagination & Breadcrumbs">Pagination &amp; Breadcrumbs</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="ui-progress.html" class="menu-link">
                            <div data-i18n="Progress">Progress</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="ui-spinners.html" class="menu-link">
                            <div data-i18n="Spinners">Spinners</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="ui-tabs-pills.html" class="menu-link">
                            <div data-i18n="Tabs & Pills">Tabs &amp; Pills</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="ui-toasts.html" class="menu-link">
                            <div data-i18n="Toasts">Toasts</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="ui-tooltips-popovers.html" class="menu-link">
                            <div data-i18n="Tooltips & Popovers">Tooltips &amp; Popovers</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="ui-typography.html" class="menu-link">
                            <div data-i18n="Typography">Typography</div>
                        </a>
                    </li>
                </ul>
            </li> --}}

            <!-- Extended components -->
            {{-- <li class="menu-item">
                <a href="javascript:void(0)" class="menu-link menu-toggle">
                    <i class="menu-icon icon-base ri ri-box-3-line"></i>
                    <div data-i18n="Extended UI">Extended UI</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="extended-ui-perfect-scrollbar.html" class="menu-link">
                            <div data-i18n="Perfect Scrollbar">Perfect Scrollbar</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="extended-ui-text-divider.html" class="menu-link">
                            <div data-i18n="Text Divider">Text Divider</div>
                        </a>
                    </li>
                </ul>
            </li> --}}

            <!-- Icons -->
            {{-- <li class="menu-item">
                <a href="icons-ri.html" class="menu-link">
                    <i class="menu-icon icon-base ri ri-remixicon-line"></i>
                    <div data-i18n="Icons">Icons</div>
                </a>
            </li> --}}

            <!-- Forms & Tables -->
            <!-- <li class="menu-header mt-7"><span class="menu-header-text">Forms &amp; Tables</span></li> -->
            <!-- Forms -->
            {{-- <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon icon-base ri ri-radio-button-line"></i>
                    <div data-i18n="Form Elements">Form Elements</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="forms-basic-inputs.html" class="menu-link">
                            <div data-i18n="Basic Inputs">Basic Inputs</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="forms-input-groups.html" class="menu-link">
                            <div data-i18n="Input groups">Input groups</div>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon icon-base ri ri-box-3-line"></i>
                    <div data-i18n="Form Layouts">Form Layouts</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="form-layouts-vertical.html" class="menu-link">
                            <div data-i18n="Vertical Form">Vertical Form</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="form-layouts-horizontal.html" class="menu-link">
                            <div data-i18n="Horizontal Form">Horizontal Form</div>
                        </a>
                    </li>
                </ul>
            </li> --}}
            <!-- Form Validation -->
            {{-- <li class="menu-item">
                <a href="https://demos.themeselection.com/materio-bootstrap-html-admin-template/html/vertical-menu-template/form-validation.html"
                    target="_blank" class="menu-link">
                    <i class="menu-icon icon-base ri ri-checkbox-multiple-line"></i>
                    <div data-i18n="Form Validation">Form Validation</div>
                    <div class="badge rounded-pill bg-label-primary fs-tiny ms-auto">Pro</div>
                </a>
            </li> --}}
            <!-- Tables -->
            {{-- <li class="menu-item active">
                <a href="tables-basic.html" class="menu-link">
                    <i class="menu-icon icon-base ri ri-table-alt-line"></i>
                    <div data-i18n="Tables">Tables</div>
                </a>
            </li> --}}
            <!-- Data Tables -->
            {{-- <li class="menu-item">
                <a href="https://demos.themeselection.com/materio-bootstrap-html-admin-template/html/vertical-menu-template/tables-datatables-basic.html"
                    target="_blank" class="menu-link">
                    <i class="menu-icon icon-base ri ri-grid-line"></i>
                    <div data-i18n="Datatables">Datatables</div>
                    <div class="badge rounded-pill bg-label-primary fs-tiny ms-auto">Pro</div>
                </a>
            </li> --}}
            <!-- Misc -->
            <!-- <li class="menu-header mt-7"><span class="menu-header-text">Misc</span></li> -->
            {{-- <li class="menu-item">
                <a href="https://github.com/themeselection/materio-bootstrap-html-admin-template-free/issues"
                    target="_blank" class="menu-link">
                    <i class="menu-icon icon-base ri ri-lifebuoy-line"></i>
                    <div data-i18n="Support">Support</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="https://demos.themeselection.com/materio-bootstrap-html-admin-template/documentation/"
                    target="_blank" class="menu-link">
                    <i class="menu-icon icon-base ri ri-article-line"></i>
                    <div data-i18n="Documentation">Documentation</div>
                </a>
            </li> --}}
        </ul>
    </aside>
    <!-- / Menu -->
