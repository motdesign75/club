<div>
    {{-- Balkendiagramm: Eintritte / Austritte --}}
    <div class="relative mb-10" style="height: 350px;">
        <canvas id="memberBarChart" class="absolute top-0 left-0 w-full h-full"></canvas>
    </div>

    {{-- Liniendiagramm: Mitglieder gesamt über das Jahr --}}
    <div class="relative" style="height: 350px;">
        <canvas id="memberLineChart" class="absolute top-0 left-0 w-full h-full"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let barChartInstance = null;
        let lineChartInstance = null;

        function renderMemberCharts() {
            const barCanvas = document.getElementById('memberBarChart');
            const lineCanvas = document.getElementById('memberLineChart');

            if (!barCanvas || !lineCanvas) {
                console.warn("❌ Canvas-Element(e) nicht gefunden");
                return;
            }

            const barCtx = barCanvas.getContext('2d');
            const lineCtx = lineCanvas.getContext('2d');

            // Vorherige Charts zerstören (verhindert Überlagerung)
            if (barChartInstance) barChartInstance.destroy();
            if (lineChartInstance) lineChartInstance.destroy();

            // Chart 1: Ein- und Austritte pro Monat
            barChartInstance = new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: @json($months),
                    datasets: [
                        {
                            label: 'Eintritte',
                            data: @json($entries),
                            backgroundColor: 'rgba(34,197,94,0.5)',
                            borderColor: 'rgba(34,197,94,1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Austritte',
                            data: @json($exits),
                            backgroundColor: 'rgba(239,68,68,0.5)',
                            borderColor: 'rgba(239,68,68,1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top' }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0, stepSize: 1 },
                            grid: { color: '#e5e7eb' }
                        },
                        x: { grid: { display: false } }
                    }
                }
            });

            // Chart 2: Mitgliederbestand (kumuliert)
            lineChartInstance = new Chart(lineCtx, {
                type: 'line',
                data: {
                    labels: @json($months),
                    datasets: [{
                        label: 'Mitglieder gesamt',
                        data: @json($totalMembers),
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59,130,246,0.1)',
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top' }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 },
                            grid: { color: '#e5e7eb' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(renderMemberCharts, 300);
        });

        Livewire.hook('message.processed', () => {
            setTimeout(renderMemberCharts, 300);
        });
    </script>
</div>
