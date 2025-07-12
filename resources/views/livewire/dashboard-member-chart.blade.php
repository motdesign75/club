<div>
    <canvas id="memberChart" height="100"></canvas>

    <script>
        document.addEventListener('livewire:load', function () {
            const ctx = document.getElementById('memberChart').getContext('2d');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($months),
                    datasets: [
                        {
                            label: 'Eintritte',
                            data: @json($entries),
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16,185,129,0.2)',
                            tension: 0.4
                        },
                        {
                            label: 'Austritte',
                            data: @json($exits),
                            borderColor: '#ef4444',
                            backgroundColor: 'rgba(239,68,68,0.2)',
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' },
                        title: { display: true, text: 'Mitgliederbewegung im Jahr ' + new Date().getFullYear() }
                    }
                }
            });
        });
    </script>
</div>
