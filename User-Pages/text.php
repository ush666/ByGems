<main>
            <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
                <div class="container-xl px-4">
                    <div class="page-header-content pt-4">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-auto mt-4">
                                <h1 class="page-header-title">
                                    <div class="page-header-icon"><i data-feather="activity"></i></div>
                                    Reports
                                </h1>
                                <div class="page-header-subtitle">Your Trust, Our Commitment. Ensuring Quality Every
                                    Time.
                                </div>
                            </div>
                            <div class="text-end">
                                <a href="#" class="btn btn-report bold ps-5 pe-5" target="_blank">
                                    <i class="fas fa-print me-2"></i> View Reports
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <div class="container-xl px-8 mt-n10">
                <div class="row">
                    <div class="col-lg-6 col-xl-4 mb-4">
                        <div class=" bg-white-admin tab-shadow h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="me-3">
                                        <a class="text-brown medium stretched-link" href="{{ route('today') }}">Order
                                            Today</a>
                                        <div class="text-lg fw-bold" id="orderTodayCount">Loading...</div>
                                    </div>
                                    <i class="feather-xl" data-feather="clipboard"></i>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between small">
                                <a class="text-white"></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xl-4 mb-4">
                        <div class=" bg-white-admin tab-shadow h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="me-3">
                                        <a class="text-brown medium stretched-link" href="{{ route('request') }}">All
                                            order Request</a>
                                        <div class="text-lg fw-bold" id="pendingOrdersCount">Loading...</div>
                                    </div>
                                    <i class="feather-xl text-brown" data-feather="book-open"></i>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between small">
                                <a class="text-white"></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xl-4 mb-4">
                        <div class=" bg-white-admin tab-shadow text-white h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="me-3">
                                        <a class="text-brown medium stretched-link"
                                            href="{{ route('completed') }}">Completed Orders This Month</a>
                                        <div class="text-lg fw-bold" id="completedCount">Loading...</div>
                                    </div>
                                    <i class="feather-xl" data-feather="check-square"></i>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between small">
                                <a class="text-white"></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Charts -->
                <div class="row">
                    <!-- Earnings Breakdown -->
                    <div class="col-xl-6 mb-4">
                        <div class="card card-header-actions h-100">
                            <div class="card-header">
                                Earnings Breakdown
                                <div class="d-flex align-items-center">
                                    <button class="btn btn-sm btn-orange me-2 print-chart-btn"
                                        data-chart-id="myAreaChart">
                                        <i class="fas fa-print me-1"></i> Print
                                    </button>
                                    <div class="dropdown no-caret">
                                        <button class="btn btn-transparent-dark btn-icon dropdown-toggle"
                                            id="areaChartDropdownExample" type="button" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false"><i class="text-brown"
                                                data-feather="more-vertical"></i></button>
                                        <div class="dropdown-menu dropdown-menu-end animated--fade-in-up"
                                            aria-labelledby="areaChartDropdownExample">
                                            <a class="dropdown-item filter-chart" href="#" data-range="12m">Last
                                                12
                                                Months</a>
                                            <a class="dropdown-item filter-chart" href="#" data-range="30d">Last
                                                30
                                                Days</a>
                                            <a class="dropdown-item filter-chart" href="#" data-range="7d">Last 7
                                                Days</a>
                                            <a class="dropdown-item filter-chart" href="#"
                                                data-range="this_month">This Month</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item filter-chart" href="#"
                                                data-range="custom">Custom
                                                Range</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart-area"><canvas id="myAreaChart" width="100%"
                                        height="30"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sales by Product -->
                    <div class="col-xl-6 mb-4">
                        <div class="card card-header-actions h-100">
                            <div class="card-header">
                                Sales by Product
                                <div class="d-flex align-items-center">
                                    <button class="btn btn-sm btn-orange me-2 print-sales-btn" id="myBarCharts "
                                        data-chart-id="myBarChart">
                                        <i class="fas fa-print me-1"></i> Print
                                    </button>
                                    <div class="dropdown no-caret">
                                        <button class="btn btn-transparent-dark btn-icon dropdown-toggle"
                                            id="areaChartDropdownExample" type="button" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false"><i class="text-brown"
                                                data-feather="more-vertical"></i></button>
                                        <div class="dropdown-menu dropdown-menu-end animated--fade-in-up"
                                            aria-labelledby="areaChartDropdownExample">
                                            <a class="dropdown-item" href="#!">Last 12 Months</a>
                                            <a class="dropdown-item" href="#!">Last 30 Days</a>
                                            <a class="dropdown-item" href="#!">Last 7 Days</a>
                                            <a class="dropdown-item" href="#!">This Month</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#!">Custom Range</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart-bar"><canvas id="salesByProductChart" width="100%"
                                        height="30"></canvas></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Completed Orders -->
                <div class="row">
                    <div class="container-fluid mb-4">
                        <div class="card card-header-actions h-100">
                            <div class="card-header">
                                Completed Orders
                                <div class="dropdown no-caret">
                                    <button id="printTableBtn" class="btn btn-sm btn-orange">
                                        <i class="fas fa-print me-1"></i> Print
                                    </button>
                                    <button class="btn btn-transparent-dark btn-icon dropdown-toggle"
                                        id="areaChartDropdownExample" type="button" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false"><i class="text-brown"
                                            data-feather="more-vertical"></i></button>
                                    <div class="dropdown-menu dropdown-menu-end animated--fade-in-up"
                                        aria-labelledby="areaChartDropdownExample">
                                        <a class="dropdown-item" href="javascript:void(0);" id="last12Months">Last 12
                                            Months</a>
                                        <a class="dropdown-item" href="javascript:void(0);" id="last30Days">Last 30
                                            Days</a>
                                        <a class="dropdown-item" href="javascript:void(0);" id="last7Days">Last 7
                                            Days</a>
                                        <a class="dropdown-item" href="javascript:void(0);" id="thisMonth">This
                                            Month</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="javascript:void(0);" id="customRange">Custom
                                            Range</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Name</th>
                                            <th>Product Type</th>
                                            <th>Order Status</th>
                                            <th>Price</th>
                                            <th>Pickup Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Table rows will be added here dynamically -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>