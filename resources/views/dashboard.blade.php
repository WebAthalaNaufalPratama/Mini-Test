<x-app-layout>
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <!-- Dashboard Card for Categories -->
            <div class="mb-4 col-lg-6">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Total Kategori</h5>
                        <h1 id="totalCategories" class="display-4">0</h1>
                        <p class="card-text">Jumlah total kategori yang telah dibuat.</p>
                    </div>
                </div>
            </div>

            <!-- Dashboard Card for Books -->
            <div class="mb-4 col-lg-6">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Total Buku</h5>
                        <h1 id="totalBooks" class="display-4">0</h1>
                        <p class="card-text">Jumlah total buku yang telah dibuat.</p>
                    </div>
                </div>
            </div>

            <!-- Chart Card -->
            <div class="mb-4 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <canvas id="categoryBookChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('css')
        <link rel="stylesheet" href="{{ asset('path/to/datatables.css') }}">
    @endpush

    @push('script')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="{{ asset('path/to/jquery.js') }}"></script>
        <script>
            $(document).ready(function() {
                // Fetch the counts using AJAX
                $.ajax({
                    url: "{{ route('dashboard.counts') }}",
                    type: "GET",
                    success: function(data) {
                        // Update card values
                        $('#totalCategories').text(data.categories);
                        $('#totalBooks').text(data.books);

                        // Render the chart with the fetched data
                        var ctx = document.getElementById('categoryBookChart').getContext('2d');
                        var categoryBookChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: ['Total Kategori', 'Total Buku'],
                                datasets: [{
                                    label: 'Counts',
                                    data: [data.categories, data.books],
                                    backgroundColor: [
                                        'rgba(54, 162, 235, 0.2)',
                                        'rgba(255, 99, 132, 0.2)',
                                    ],
                                    borderColor: [
                                        'rgba(54, 162, 235, 1)',
                                        'rgba(255, 99, 132, 1)',
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                },
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: 'top',
                                    },
                                    title: {
                                        display: true,
                                        text: 'Jumlah dari Kategori dan Buku',
                                        font: {
                                            size: 20
                                        }
                                    }
                                }
                            }
                        });
                    },
                    error: function(xhr) {
                        alert("Error fetching data.");
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
