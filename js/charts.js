/**
 * F55 Men Relaxation & Massage Center
 * Chart Renderers using Chart.js for Executive Dashboard & Bookkeeping Analytics
 */

window.spaCharts = {
  revenueChartInstance: null,
  serviceChartInstance: null,
  bookkeepingChartInstance: null,
  expenseChartInstance: null,

  // 1. PENDAPATAN 7 HARI TERAKHIR (Matches Image 2)
  initRevenueChart(canvasId, filter = 'week') {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    if (this.revenueChartInstance) {
      this.revenueChartInstance.destroy();
    }

    const currencyMode = window.spaStore ? window.spaStore.getCurrencyMode() : 'AED';

    let labels = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
    let dataAed = [600, 720, 880, 760, 1000, 1280, 1120];
    let dataIdr = [1500000, 1800000, 2200000, 1900000, 2500000, 3200000, 2800000];

    if (filter === 'today') {
      labels = ['10:00', '12:00', '14:00', '16:00', '18:00', '20:00', '22:00'];
      dataAed = [150, 250, 200, 350, 200, 150, 50];
      dataIdr = [375000, 625000, 500000, 875000, 500000, 375000, 125000];
    } else if (filter === 'month') {
      labels = ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'];
      dataAed = [5200, 6100, 7400, 6800];
      dataIdr = [13000000, 15250000, 18500000, 17000000];
    }

    const currentData = currencyMode === 'AED' ? dataAed : dataIdr;

    // Glowing Gradient Fill
    const gradient = ctx.createLinearGradient(0, 0, 0, 320);
    gradient.addColorStop(0, 'rgba(0, 186, 124, 0.45)');
    gradient.addColorStop(0.5, 'rgba(212, 175, 55, 0.15)');
    gradient.addColorStop(1, 'rgba(0, 186, 124, 0.0)');

    this.revenueChartInstance = new Chart(ctx, {
      type: 'line',
      data: {
        labels,
        datasets: [{
          label: currencyMode === 'AED' ? 'Pendapatan (AED)' : 'Pendapatan (Rp)',
          data: currentData,
          borderColor: '#00ba7c',
          borderWidth: 3.5,
          backgroundColor: gradient,
          fill: true,
          tension: 0.45,
          pointBackgroundColor: '#00ba7c',
          pointBorderColor: '#ffffff',
          pointBorderWidth: 2.5,
          pointRadius: 6,
          pointHoverRadius: 8,
          pointHoverBackgroundColor: '#d4af37',
          pointHoverBorderColor: '#ffffff',
          pointHoverBorderWidth: 3
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
          mode: 'index',
          intersect: false
        },
        plugins: {
          legend: { display: false },
          tooltip: {
            backgroundColor: 'rgba(12, 14, 19, 0.95)',
            borderColor: '#d4af37',
            borderWidth: 1,
            titleFont: { family: 'Plus Jakarta Sans', size: 13, weight: 'bold' },
            bodyFont: { family: 'Plus Jakarta Sans', size: 14, weight: 'extrabold' },
            titleColor: '#faedd0',
            bodyColor: '#34d399',
            padding: 14,
            cornerRadius: 12,
            displayColors: false,
            callbacks: {
              label: function(context) {
                if (currencyMode === 'AED') {
                  return ` Omset: ${context.raw} AED`;
                }
                return ` Omset: ${window.spaStore.formatRupiah(context.raw)}`;
              }
            }
          }
        },
        scales: {
          x: {
            grid: { color: 'rgba(212, 175, 55, 0.06)' },
            ticks: { font: { family: 'Plus Jakarta Sans', size: 12, weight: '600' }, color: '#a39c8e' }
          },
          y: {
            grid: { color: 'rgba(212, 175, 55, 0.08)' },
            ticks: {
              font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' },
              color: '#a39c8e',
              callback: function(value) {
                if (currencyMode === 'AED') {
                  return value + ' AED';
                }
                return 'Rp' + (value / 1000) + 'k';
              }
            }
          }
        }
      }
    });
  },

  // 2. LAYANAN TERPOPULER (Matches Image 2 Donut Chart)
  initServicePerformanceChart(canvasId) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    if (this.serviceChartInstance) {
      this.serviceChartInstance.destroy();
    }

    this.serviceChartInstance = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: [
          'Balinese Massage',
          'Massage with Ice Pool',
          'Thai Massage',
          'Sports Massage',
          'Cupping & Hot Stone'
        ],
        datasets: [{
          data: [35, 28, 20, 15, 12],
          backgroundColor: [
            '#008460', // Dark teal
            '#00ba7c', // Vibrant emerald
            '#33d69f', // Light mint
            '#d97706', // Amber gold
            '#f59e0b'  // Yellow gold
          ],
          borderWidth: 3,
          borderColor: '#13161f',
          hoverBorderColor: '#d4af37',
          hoverOffset: 6
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' },
              color: '#d4cebe',
              usePointStyle: true,
              pointStyle: 'circle',
              padding: 16
            }
          },
          tooltip: {
            backgroundColor: 'rgba(12, 14, 19, 0.95)',
            borderColor: '#d4af37',
            borderWidth: 1,
            titleColor: '#faedd0',
            bodyColor: '#ffffff',
            padding: 12,
            cornerRadius: 12,
            callbacks: {
              label: function(context) {
                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                const pct = Math.round((context.raw / total) * 100);
                return ` ${context.label}: ${context.raw} booking (${pct}%)`;
              }
            }
          }
        },
        cutout: '72%'
      }
    });
  },

  // 3. GRAFIK PEMBUKUAN TOTAL KESELURUHAN (Income vs Expense vs Profit)
  initBookkeepingChart(canvasId) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    if (this.bookkeepingChartInstance) {
      this.bookkeepingChartInstance.destroy();
    }

    const currencyMode = window.spaStore ? window.spaStore.getCurrencyMode() : 'AED';

    const labels = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
    const incomeAed = [600, 720, 880, 760, 1000, 1280, 1120];
    const expenseAed = [180, 200, 240, 190, 280, 350, 310];
    const profitAed = incomeAed.map((inc, i) => inc - expenseAed[i]);

    const incomeIdr = incomeAed.map(v => v * 2500);
    const expenseIdr = expenseAed.map(v => v * 2500);
    const profitIdr = profitAed.map(v => v * 2500);

    const dataIncome = currencyMode === 'AED' ? incomeAed : incomeIdr;
    const dataExpense = currencyMode === 'AED' ? expenseAed : expenseIdr;
    const dataProfit = currencyMode === 'AED' ? profitAed : profitIdr;

    this.bookkeepingChartInstance = new Chart(ctx, {
      type: 'bar',
      data: {
        labels,
        datasets: [
          {
            type: 'bar',
            label: 'Total Pendapatan',
            data: dataIncome,
            backgroundColor: '#00ba7c',
            borderRadius: 6,
            barPercentage: 0.65
          },
          {
            type: 'bar',
            label: 'Total Pengeluaran',
            data: dataExpense,
            backgroundColor: '#e11d48',
            borderRadius: 6,
            barPercentage: 0.65
          },
          {
            type: 'line',
            label: 'Laba Bersih',
            data: dataProfit,
            borderColor: '#d4af37',
            borderWidth: 3,
            pointBackgroundColor: '#d4af37',
            pointBorderColor: '#ffffff',
            pointBorderWidth: 2,
            pointRadius: 5,
            tension: 0.35,
            fill: false
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'top',
            labels: {
              font: { family: 'Plus Jakarta Sans', size: 12, weight: '700' },
              color: '#faedd0',
              usePointStyle: true,
              padding: 16
            }
          },
          tooltip: {
            backgroundColor: 'rgba(12, 14, 19, 0.95)',
            borderColor: '#d4af37',
            borderWidth: 1,
            titleColor: '#faedd0',
            bodyColor: '#ffffff',
            padding: 12,
            cornerRadius: 12,
            callbacks: {
              label: function(context) {
                if (currencyMode === 'AED') {
                  return ` ${context.dataset.label}: ${context.raw} AED`;
                }
                return ` ${context.dataset.label}: ${window.spaStore.formatRupiah(context.raw)}`;
              }
            }
          }
        },
        scales: {
          x: {
            grid: { color: 'rgba(212, 175, 55, 0.06)' },
            ticks: { font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' }, color: '#a39c8e' }
          },
          y: {
            grid: { color: 'rgba(212, 175, 55, 0.08)' },
            ticks: {
              font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' },
              color: '#a39c8e',
              callback: function(value) {
                if (currencyMode === 'AED') {
                  return value + ' AED';
                }
                return 'Rp' + (value / 1000) + 'k';
              }
            }
          }
        }
      }
    });
  },

  // 4. ALOKASI KATEGORI PENGELUARAN (Expense Category Donut Chart)
  initExpenseCategoryChart(canvasId) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    if (this.expenseChartInstance) {
      this.expenseChartInstance.destroy();
    }

    const summary = window.spaStore ? window.spaStore.getFinancialSummary() : null;
    const catTotals = summary ? summary.categoryTotals : {
      'Bahan & Minyak Spa': 140,
      'Ice Bath & Ice Pool': 60,
      'Laundry & Handuk Premium': 90,
      'Komisi Terapis Harian': 180,
      'Utilitas & Perawatan': 150,
      'Konsumsi & Minuman': 50
    };

    const labels = Object.keys(catTotals);
    const data = Object.values(catTotals);

    this.expenseChartInstance = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels,
        datasets: [{
          data,
          backgroundColor: [
            '#d4af37', // Gold
            '#0284c7', // Sky Blue (Ice)
            '#8b5cf6', // Violet
            '#10b981', // Emerald
            '#f97316', // Orange
            '#e11d48'  // Rose
          ],
          borderWidth: 2,
          borderColor: '#13161f'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' },
              color: '#d4cebe',
              usePointStyle: true,
              padding: 12
            }
          },
          tooltip: {
            backgroundColor: 'rgba(12, 14, 19, 0.95)',
            borderColor: '#d4af37',
            borderWidth: 1,
            titleColor: '#faedd0',
            bodyColor: '#ffffff',
            padding: 10,
            cornerRadius: 10,
            callbacks: {
              label: function(context) {
                return ` ${context.label}: ${context.raw} AED`;
              }
            }
          }
        },
        cutout: '68%'
      }
    });
  }
};
