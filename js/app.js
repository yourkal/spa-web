/**
 * F55 Men Relaxation & Massage Center
 * Main Application Controller, Router & UI Renderer
 */

document.addEventListener('DOMContentLoaded', () => {
  // Application State
  window.appState = {
    currentRoute: 'dashboard',
    bookingDraft: {
      serviceId: 'SRV-001',
      durationMinutes: 60,
      date: new Date().toISOString().split('T')[0],
      time: '14:00',
      therapistId: 'THP-001',
      paymentMethod: 'Credit Card / Apple Pay'
    },
    cardDurations: {},
    selectedCustomerId: 'BIO-001',
    selectedServiceId: 'SRV-001',
    selectedBookingId: 'F55-20261014-001',
    biodataSearchQuery: '',
    expenseFilterCategory: 'ALL'
  };

  // Toast Notification System
  window.showToast = function (message, type = 'success') {
    const container = document.getElementById('toast-container');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = `flex items-center gap-3 px-4 py-3 rounded-2xl shadow-2xl border text-sm font-medium animate-slide-up bg-[#13161f] border-[#d4af37]/40 text-[#f5f0e6] transition-all duration-300 pointer-events-auto`;

    let iconSvg = `<svg class="w-5 h-5 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>`;

    if (type === 'error') {
      toast.className += ` border-rose-500/50`;
      iconSvg = `<svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>`;
    } else if (type === 'warning') {
      toast.className += ` border-amber-500/50`;
      iconSvg = `<svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>`;
    }

    toast.innerHTML = `
      <div class="p-1 rounded-xl bg-[#1c202c]">${iconSvg}</div>
      <div class="text-[#f5f0e6] text-xs font-semibold">${message}</div>
      <button onclick="this.parentElement.remove()" class="ml-auto text-[#a39c8e] hover:text-white p-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
      </button>
    `;

    container.appendChild(toast);

    setTimeout(() => {
      toast.style.opacity = '0';
      toast.style.transform = 'translateY(-10px)';
      setTimeout(() => toast.remove(), 300);
    }, 4000);
  };

  // Router
  function navigateTo(route) {
    window.appState.currentRoute = route;
    window.location.hash = route;
    renderCurrentRoute();
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }
  window.navigateTo = navigateTo;

  function renderCurrentRoute() {
    if (window.toggleAdminSidebar) window.toggleAdminSidebar(false);

    const hash = window.location.hash.replace('#', '') || 'dashboard';
    window.appState.currentRoute = hash;

    // Check role restriction for Bos
    const role = window.spaStore ? window.spaStore.getActiveRole() : 'admin';
    if (window.updateRoleUI) window.updateRoleUI(role);

    // If Bos tries to access mutation routes like sessions edit or therapist edit, allow view-only dashboard
    if (role === 'bos' && (hash === 'admin-sessions' || hash === 'admin-therapists' || hash === 'admin-settings')) {
      window.showToast('👑 Role Bos: Mode pemantauan dashboard & keuangan saja.', 'warning');
      window.location.hash = 'admin-dashboard';
      window.appState.currentRoute = 'admin-dashboard';
    }

    // Highlight Navigation Links
    document.querySelectorAll('[data-nav-target]').forEach(link => {
      const target = link.getAttribute('data-nav-target');
      if (target === window.appState.currentRoute) {
        link.classList.add('text-[#faedd0]', 'bg-[#1e2230]', 'border', 'border-[#d4af37]/40');
        link.classList.remove('text-[#a39c8e]');
      } else {
        link.classList.remove('text-[#faedd0]', 'bg-[#1e2230]', 'border', 'border-[#d4af37]/40');
        link.classList.add('text-[#a39c8e]');
      }
    });

    const customerLayout = document.getElementById('customer-layout');
    const adminLayout = document.getElementById('admin-layout');
    const authLayout = document.getElementById('auth-layout');

    if (hash === 'login' || hash === 'register' || hash === 'admin-login') {
      if (customerLayout) customerLayout.classList.add('hidden');
      if (adminLayout) adminLayout.classList.add('hidden');
      if (authLayout) authLayout.classList.remove('hidden');
      renderAuthView(hash);
      return;
    }

    if (authLayout) authLayout.classList.add('hidden');

    if (hash.startsWith('admin')) {
      if (customerLayout) customerLayout.classList.add('hidden');
      if (adminLayout) adminLayout.classList.remove('hidden');
      renderAdminView(hash);
    } else {
      if (adminLayout) adminLayout.classList.add('hidden');
      if (customerLayout) customerLayout.classList.remove('hidden');
      renderCustomerView(hash);
    }

    if (window.lucide) window.lucide.createIcons();
  }
  window.renderCurrentRoute = renderCurrentRoute;

  // --- CUSTOMER VIEWS ROUTER ---
  function renderCustomerView(route) {
    const container = document.getElementById('customer-view-container');
    if (!container) return;

    if (route === 'dashboard') {
      container.innerHTML = renderCustomerDashboardHTML();
    } else if (route === 'services') {
      container.innerHTML = renderServicesCatalogHTML();
    } else if (route === 'services-detail') {
      container.innerHTML = renderServiceDetailHTML(window.appState.selectedServiceId);
    } else if (route === 'booking') {
      container.innerHTML = renderBookingWizardHTML();
      setupBookingWizardListeners();
    } else if (route === 'bookings') {
      container.innerHTML = renderCustomerBookingsHTML();
    } else if (route === 'biodata') {
      container.innerHTML = renderCustomerBiodataHTML();
    } else if (route === 'profile') {
      container.innerHTML = renderCustomerProfileHTML();
    } else {
      container.innerHTML = renderCustomerDashboardHTML();
    }

    if (window.lucide) window.lucide.createIcons();
  }

  // --- ADMIN & BOS VIEWS ROUTER ---
  function renderAdminView(route) {
    const container = document.getElementById('admin-view-container');
    if (!container) return;

    if (route === 'admin-dashboard') {
      container.innerHTML = renderAdminDashboardHTML();
      setTimeout(() => {
        if (window.spaCharts) {
          window.spaCharts.initRevenueChart('adminRevenueChart', 'week');
          window.spaCharts.initServicePerformanceChart('adminServiceChart');
        }
      }, 50);
    } else if (route === 'admin-bookings') {
      container.innerHTML = renderAdminBookingsHTML();
    } else if (route === 'admin-sessions') {
      container.innerHTML = renderAdminSessionsHTML();
    } else if (route === 'admin-biodata') {
      container.innerHTML = renderAdminBiodataHTML();
    } else if (route === 'admin-expenses') {
      container.innerHTML = renderAdminExpensesHTML();
    } else if (route === 'admin-therapists') {
      container.innerHTML = renderAdminTherapistsHTML();
    } else if (route === 'admin-services') {
      container.innerHTML = renderAdminServicesHTML();
    } else if (route === 'admin-transactions') {
      container.innerHTML = renderAdminTransactionsHTML();
      setTimeout(() => {
        if (window.spaCharts) {
          window.spaCharts.initBookkeepingChart('bookkeepingChart');
          window.spaCharts.initExpenseCategoryChart('expenseCategoryChart');
        }
      }, 50);
    } else if (route === 'admin-reports') {
      container.innerHTML = renderAdminReportsHTML();
      setTimeout(() => {
        if (window.spaCharts) {
          window.spaCharts.initRevenueChart('reportRevenueChart', 'month');
          window.spaCharts.initServicePerformanceChart('reportServiceChart');
        }
      }, 50);
    } else if (route === 'admin-settings') {
      container.innerHTML = renderAdminSettingsHTML();
    } else {
      container.innerHTML = renderAdminDashboardHTML();
      setTimeout(() => {
        if (window.spaCharts) {
          window.spaCharts.initRevenueChart('adminRevenueChart', 'week');
          window.spaCharts.initServicePerformanceChart('adminServiceChart');
        }
      }, 50);
    }

    if (window.lucide) window.lucide.createIcons();
  }

  // --- AUTH VIEWS ROUTER ---
  function renderAuthView(route) {
    const container = document.getElementById('auth-view-container');
    if (!container) return;

    if (route === 'register') {
      container.innerHTML = renderRegisterHTML();
    } else if (route === 'admin-login') {
      container.innerHTML = renderAdminLoginHTML();
    } else {
      container.innerHTML = renderLoginHTML();
    }

    if (window.lucide) window.lucide.createIcons();
  }

  // =========================================================================
  // VIEW GENERATORS: CUSTOMER VIEWS
  // =========================================================================

  function renderCustomerDashboardHTML() {
    const user = window.spaStore.state.currentUser;
    const activeSessions = window.spaStore.getActiveSessions();
    const myBookings = window.spaStore.getBookings();
    const activeBooking = myBookings.find(b => b.status === 'in_progress' || b.status === 'confirmed');

    return `
      <div class="space-y-8 animate-fade-in">
        <!-- Hero Luxury Banner Inspired by Poster -->
        <div class="relative rounded-3xl overflow-hidden border border-[#d4af37]/30 bg-gradient-to-r from-[#11131a] via-[#161a25] to-[#11131a] p-6 sm:p-10 shadow-2xl">
          <!-- Background Gold Ornaments -->
          <div class="absolute -right-16 -bottom-16 w-80 h-80 rounded-full bg-[#d4af37]/10 blur-3xl pointer-events-none"></div>
          <div class="absolute top-0 right-10 text-[180px] font-black text-[#d4af37]/5 select-none pointer-events-none font-serif-luxury">F55</div>

          <div class="relative z-10 space-y-4 max-w-2xl">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#1e2230] border border-[#d4af37]/40 text-[#fceda8] text-xs font-bold tracking-widest uppercase">
              <i data-lucide="sparkles" class="w-3.5 h-3.5 text-[#d4af37]"></i>
              <span>Experience Relaxation • Restore Balance</span>
            </div>

            <h1 class="text-3xl sm:text-5xl font-extrabold text-[#faedd0] tracking-tight font-serif-luxury leading-tight">
              Selamat Datang di <span class="gold-gradient-text">F55</span>
            </h1>

            <p class="text-[#a39c8e] text-xs sm:text-sm leading-relaxed">
              Pusat perawatan pijat & relaksasi khusus pria terkemuka di Dubai. Nikmati sensasi Balinese, Thai, Shiatsu, dan terapi dingin Ice Bath & Ice Pool yang menyegarkan kembali tubuh dan pikiran Anda.
            </p>

            <div class="pt-2 flex flex-wrap items-center gap-3">
              <button onclick="navigateTo('booking')" class="px-6 py-3.5 gold-gradient-bg text-black font-extrabold text-xs rounded-2xl shadow-xl hover:scale-105 transition-all flex items-center gap-2">
                <span>Reservasi Sekarang</span>
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
              </button>
              <button onclick="navigateTo('services')" class="px-5 py-3.5 gold-outline-btn font-bold text-xs rounded-2xl transition-all flex items-center gap-2">
                <i data-lucide="book-open" class="w-4 h-4 text-[#d4af37]"></i>
                <span>Lihat 10 Menu Massage</span>
              </button>
            </div>
          </div>
        </div>

        <!-- Metric Highlights -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div class="bg-[#13161f] p-5 rounded-2xl border border-[#d4af37]/25 space-y-2">
            <span class="text-[11px] font-bold text-[#a39c8e] uppercase tracking-wider block">Lokasi Spa</span>
            <p class="text-sm font-extrabold text-[#faedd0] flex items-center gap-1.5">
              <i data-lucide="map-pin" class="w-4 h-4 text-[#d4af37]"></i> Dubai, UAE
            </p>
          </div>
          <div class="bg-[#13161f] p-5 rounded-2xl border border-[#d4af37]/25 space-y-2">
            <span class="text-[11px] font-bold text-[#a39c8e] uppercase tracking-wider block">Jam Operasional</span>
            <p class="text-sm font-extrabold text-[#faedd0] flex items-center gap-1.5">
              <i data-lucide="clock" class="w-4 h-4 text-[#d4af37]"></i> 10:00 - 00:00 (Daily)
            </p>
          </div>
          <div class="bg-[#13161f] p-5 rounded-2xl border border-[#d4af37]/25 space-y-2">
            <span class="text-[11px] font-bold text-[#a39c8e] uppercase tracking-wider block">Kontak WhatsApp</span>
            <a href="https://wa.me/971501234567" target="_blank" class="text-sm font-extrabold text-[#34d399] hover:underline flex items-center gap-1.5">
              <i data-lucide="phone" class="w-4 h-4"></i> +971 50 123 4567
            </a>
          </div>
          <div class="bg-[#13161f] p-5 rounded-2xl border border-[#d4af37]/25 space-y-2">
            <span class="text-[11px] font-bold text-[#a39c8e] uppercase tracking-wider block">Layanan Spesial</span>
            <p class="text-sm font-extrabold text-[#fceda8] flex items-center gap-1.5">
              <i data-lucide="droplet" class="w-4 h-4 text-cyan-400"></i> Ice Bath & Cupping
            </p>
          </div>
        </div>

        <!-- 10 Featured Services from Poster -->
        <div class="space-y-4">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-xl font-bold text-[#faedd0] font-serif-luxury">Official Massage Menu</h2>
              <p class="text-xs text-[#a39c8e]">Perawatan relaksasi lengkap sesuai brosur resmi F55 Center</p>
            </div>
            <button onclick="navigateTo('services')" class="text-xs text-[#d4af37] hover:underline font-bold flex items-center gap-1">
              Lihat Semua 10 Layanan <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </button>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            ${window.spaStore.getServices().slice(0, 3).map(service => `
              <div class="bg-[#13161f] rounded-2xl border border-[#d4af37]/30 overflow-hidden poster-card flex flex-col justify-between">
                <div>
                  <div class="relative h-44 overflow-hidden">
                    <img src="${service.image}" alt="${service.name}" class="w-full h-full object-cover">
                    <div class="absolute top-3 left-3 px-2.5 py-1 bg-[#090a0d]/80 backdrop-blur-md rounded-lg border border-[#d4af37]/40 text-[#fceda8] text-[10px] font-bold font-serif-luxury">
                      ${service.menuNumber}
                    </div>
                  </div>
                  <div class="p-5 space-y-2">
                    <span class="text-[10px] font-bold px-2 py-0.5 bg-[#1d212f] text-[#d4af37] border border-[#d4af37]/20 rounded-md uppercase tracking-wider">${service.category}</span>
                    <h3 class="font-bold text-[#faedd0] text-base font-serif-luxury">${service.name}</h3>
                    <p class="text-xs text-[#a39c8e] line-clamp-2 leading-relaxed">${service.description}</p>
                  </div>
                </div>
                <div class="p-5 pt-0 border-t border-[#d4af37]/15 mt-3 flex items-center justify-between">
                  <div>
                    <span class="text-[10px] text-[#a39c8e] block">60 Min Treatment</span>
                    <span class="font-extrabold text-[#fceda8] text-base font-serif-luxury">${window.spaStore.formatPrice(service.priceAed, service.price)}</span>
                  </div>
                  <button onclick="selectServiceForBooking('${service.id}')" class="px-4 py-2 gold-gradient-bg text-black font-extrabold text-xs rounded-xl shadow-md hover:scale-105 transition-all">
                    Booking
                  </button>
                </div>
              </div>
            `).join('')}
          </div>
        </div>
      </div>
    `;
  }

  // --- CATALOG VIEW ---
  function renderServicesCatalogHTML() {
    const services = window.spaStore.getServices();

    return `
      <div class="space-y-8 animate-fade-in">
        <!-- Poster Style Header -->
        <div class="text-center max-w-2xl mx-auto space-y-3">
          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#181b24] border border-[#d4af37]/40 text-[#fceda8] text-[10px] uppercase font-bold tracking-widest">
            F55 MEN RELAXATION & MASSAGE CENTER
          </div>
          <h1 class="text-3xl sm:text-4xl font-extrabold text-[#faedd0] font-serif-luxury tracking-wide">
            MASSAGE MENU
          </h1>
          <div class="poster-divider my-2">
            <span class="poster-ornament"></span>
          </div>
          <p class="text-xs sm:text-sm text-[#a39c8e]">
            EXPERIENCE RELAXATION. RESTORE BALANCE. RENEW YOUR BODY & MIND.
          </p>
        </div>

        <!-- 10 Services List from Poster -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          ${services.map(s => `
            <div class="bg-[#13161f] p-5 sm:p-6 rounded-2xl border border-[#d4af37]/30 poster-card space-y-4">
              <div class="flex gap-4">
                <img src="${s.image}" class="w-24 h-24 sm:w-28 sm:h-28 rounded-xl object-cover border border-[#d4af37]/40 shrink-0">
                <div class="space-y-1.5 flex-1 min-w-0">
                  <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-[#d4af37] font-serif-luxury tracking-wider">${s.menuNumber}. ${s.name.toUpperCase()}</span>
                  </div>
                  <p class="text-xs text-[#a39c8e] line-clamp-3 leading-relaxed">${s.description}</p>
                </div>
              </div>

              <!-- Price Box matching poster -->
              <div class="flex items-center justify-between pt-3 border-t border-[#d4af37]/20">
                <div class="flex items-center gap-4 text-xs font-bold">
                  <div>
                    <span class="text-[10px] text-[#a39c8e] block">${s.duration} MIN</span>
                    <span class="text-sm font-extrabold text-[#fceda8] font-serif-luxury">${window.spaStore.formatPrice(s.priceAed, s.price)}</span>
                  </div>
                  ${s.price90Aed ? `
                    <div class="border-l border-[#d4af37]/30 pl-4">
                      <span class="text-[10px] text-[#a39c8e] block">90 MIN</span>
                      <span class="text-sm font-extrabold text-[#fceda8] font-serif-luxury">${window.spaStore.formatPrice(s.price90Aed, s.price * 1.33)}</span>
                    </div>
                  ` : ''}
                </div>

                <button onclick="selectServiceForBooking('${s.id}')"
                  class="px-4 py-2 gold-gradient-bg text-black font-extrabold text-xs rounded-xl shadow-md hover:scale-105 transition-all">
                  Pilih Layanan
                </button>
              </div>
            </div>
          `).join('')}
        </div>
      </div>
    `;
  }

  // --- CUSTOMER BIODATA PAGE ---
  function renderCustomerBiodataHTML() {
    return renderBiodataSharedView(false);
  }

  // --- ADMIN BIODATA PAGE (Halaman Biodata: isinya nama dan nomor hp) ---
  function renderAdminBiodataHTML() {
    return renderBiodataSharedView(true);
  }

  function renderBiodataSharedView(isAdmin) {
    const list = window.spaStore.getBiodata();
    const query = (window.appState.biodataSearchQuery || '').toLowerCase().trim();
    const filtered = query
      ? list.filter(b => b.name.toLowerCase().includes(query) || b.phone.includes(query))
      : list;

    return `
      <div class="space-y-6 animate-fade-in">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div>
            <h1 class="text-2xl font-bold text-[#faedd0] font-serif-luxury">Halaman Biodata Pelanggan</h1>
            <p class="text-xs text-[#a39c8e]">Pencatatan data kontak tamu spa: Nama Lengkap dan Nomor HP (WhatsApp)</p>
          </div>
          <button onclick="openAddBiodataModal()"
            class="px-4 py-2.5 gold-gradient-bg text-black font-extrabold text-xs rounded-xl shadow-lg hover:scale-105 transition-all flex items-center gap-2">
            <i data-lucide="user-plus" class="w-4 h-4 text-black"></i>
            <span>+ Tambah Biodata Baru</span>
          </button>
        </div>

        <!-- Metric Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div class="bg-[#13161f] p-4 rounded-2xl border border-[#d4af37]/25 space-y-1">
            <span class="text-[10px] text-[#a39c8e] uppercase font-bold tracking-wider block">Total Kontak</span>
            <span class="text-2xl font-black text-[#faedd0] font-serif-luxury">${list.length} Orang</span>
          </div>
          <div class="bg-[#13161f] p-4 rounded-2xl border border-[#d4af37]/25 space-y-1">
            <span class="text-[10px] text-[#a39c8e] uppercase font-bold tracking-wider block">Member VIP</span>
            <span class="text-2xl font-black text-[#d4af37] font-serif-luxury">${list.filter(b => b.status === 'VIP Member').length} Member</span>
          </div>
          <div class="bg-[#13161f] p-4 rounded-2xl border border-[#d4af37]/25 space-y-1">
            <span class="text-[10px] text-[#a39c8e] uppercase font-bold tracking-wider block">WhatsApp Aktif</span>
            <span class="text-2xl font-black text-[#34d399] font-serif-luxury">${list.length} Terhubung</span>
          </div>
          <div class="bg-[#13161f] p-4 rounded-2xl border border-[#d4af37]/25 space-y-1">
            <span class="text-[10px] text-[#a39c8e] uppercase font-bold tracking-wider block">Tamu Baru</span>
            <span class="text-2xl font-black text-cyan-400 font-serif-luxury">+3 Minggu Ini</span>
          </div>
        </div>

        <!-- Search Bar -->
        <div class="bg-[#13161f] p-4 rounded-2xl border border-[#d4af37]/25 flex flex-col sm:flex-row items-center justify-between gap-3">
          <div class="relative w-full sm:w-96">
            <i data-lucide="search" class="w-4 h-4 text-[#a39c8e] absolute left-3 top-3"></i>
            <input type="text"
              placeholder="Cari berdasarkan Nama atau Nomor HP..."
              value="${window.appState.biodataSearchQuery || ''}"
              oninput="handleBiodataSearch(this.value)"
              class="w-full pl-9 pr-4 py-2 bg-[#1a1d29] border border-[#d4af37]/30 rounded-xl text-xs text-[#faedd0] focus:border-[#d4af37] outline-none">
          </div>
          <div class="text-xs text-[#a39c8e]">
            Menampilkan <strong class="text-[#faedd0]">${filtered.length}</strong> data biodata
          </div>
        </div>

        <!-- Biodata Table -->
        <div class="bg-[#13161f] rounded-2xl border border-[#d4af37]/30 shadow-xl overflow-x-auto">
          <table class="w-full text-left text-xs">
            <thead>
              <tr class="bg-[#171b26] text-[#d4af37] uppercase text-[10px] font-bold border-b border-[#d4af37]/20">
                <th class="p-4">Nama Lengkap</th>
                <th class="p-4">Nomor HP / WhatsApp</th>
                <th class="p-4">Status Tamu</th>
                <th class="p-4">Catatan / Preferensi Spa</th>
                <th class="p-4 text-center">Chat WhatsApp</th>
                <th class="p-4 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-[#d4af37]/15">
              ${filtered.length === 0 ? `
                <tr><td colspan="6" class="p-8 text-center text-[#a39c8e]">Tidak ditemukan biodata yang cocok.</td></tr>
              ` : filtered.map(b => `
                <tr class="hover:bg-[#1a1d29]/60 transition-colors">
                  <td class="p-4 font-bold text-[#faedd0] flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full bg-[#1e2230] border border-[#d4af37]/40 text-[#fceda8] flex items-center justify-center font-bold text-xs">
                      ${b.name.charAt(0)}
                    </div>
                    <div>
                      <span class="block">${b.name}</span>
                      <span class="text-[10px] text-[#a39c8e] font-normal">${b.email || '-'}</span>
                    </div>
                  </td>
                  <td class="p-4 font-mono font-bold text-[#fceda8]">${b.phone}</td>
                  <td class="p-4">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold ${b.status === 'VIP Member' ? 'badge-vip' : 'badge-ready'}">
                      ${b.status}
                    </span>
                  </td>
                  <td class="p-4 text-[#a39c8e] max-w-xs truncate">${b.notes}</td>
                  <td class="p-4 text-center">
                    <a href="https://wa.me/${b.phone.replace(/[^0-9]/g, '')}" target="_blank"
                      class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-400 border border-emerald-500/40 rounded-xl text-[11px] font-bold transition-all">
                      <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                      <span>Chat WA</span>
                    </a>
                  </td>
                  <td class="p-4 text-right space-x-1">
                    <button onclick="deleteBiodataEntry('${b.id}')" class="px-2.5 py-1 text-rose-400 hover:bg-rose-500/20 rounded-lg text-[10px] font-bold border border-rose-500/30">
                      Hapus
                    </button>
                  </td>
                </tr>
              `).join('')}
            </tbody>
          </table>
        </div>
      </div>
    `;
  }

  // --- FITUR PENGELUARAN (Expense Tracking Page) ---
  function renderAdminExpensesHTML() {
    const expenses = window.spaStore.getExpenses();
    const summary = window.spaStore.getFinancialSummary();

    return `
      <div class="space-y-6 animate-fade-in">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div>
            <h1 class="text-2xl font-bold text-[#faedd0] font-serif-luxury">Fitur Pengeluaran Spa</h1>
            <p class="text-xs text-[#a39c8e]">Pencatatan pengeluaran operasional, bahan spa, komisi terapis, dan es ice bath</p>
          </div>
          <button onclick="openAddExpenseModal()"
            class="px-4 py-2.5 gold-gradient-bg text-black font-extrabold text-xs rounded-xl shadow-lg hover:scale-105 transition-all flex items-center gap-2">
            <i data-lucide="plus-circle" class="w-4 h-4 text-black"></i>
            <span>+ Catat Pengeluaran Baru</span>
          </button>
        </div>

        <!-- Summary KPI Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div class="bg-[#13161f] p-5 rounded-2xl border border-[#d4af37]/25 space-y-1">
            <span class="text-[10px] text-[#a39c8e] uppercase font-bold tracking-wider block">Total Pengeluaran</span>
            <span class="text-2xl font-black text-rose-400 font-serif-luxury">${window.spaStore.formatPrice(summary.totalExpensesAed, summary.totalExpensesIdr)}</span>
          </div>
          <div class="bg-[#13161f] p-5 rounded-2xl border border-[#d4af37]/25 space-y-1">
            <span class="text-[10px] text-[#a39c8e] uppercase font-bold tracking-wider block">Pengeluaran Hari Ini</span>
            <span class="text-2xl font-black text-amber-400 font-serif-luxury">${window.spaStore.formatPrice(200, 500000)}</span>
          </div>
          <div class="bg-[#13161f] p-5 rounded-2xl border border-[#d4af37]/25 space-y-1">
            <span class="text-[10px] text-[#a39c8e] uppercase font-bold tracking-wider block">Total Item Biaya</span>
            <span class="text-2xl font-black text-[#faedd0] font-serif-luxury">${expenses.length} Transaksi</span>
          </div>
          <div class="bg-[#13161f] p-5 rounded-2xl border border-[#d4af37]/25 space-y-1">
            <span class="text-[10px] text-[#a39c8e] uppercase font-bold tracking-wider block">Status Arus Kas</span>
            <span class="text-2xl font-black text-[#34d399] font-serif-luxury">Terkendali</span>
          </div>
        </div>

        <!-- Expenses Table -->
        <div class="bg-[#13161f] rounded-2xl border border-[#d4af37]/30 shadow-xl overflow-x-auto">
          <table class="w-full text-left text-xs">
            <thead>
              <tr class="bg-[#171b26] text-[#d4af37] uppercase text-[10px] font-bold border-b border-[#d4af37]/20">
                <th class="p-4">Tanggal</th>
                <th class="p-4">Kategori Biaya</th>
                <th class="p-4">Keterangan / Deskripsi</th>
                <th class="p-4">Metode Bayar</th>
                <th class="p-4">Dicatat Oleh</th>
                <th class="p-4 font-mono">Nominal</th>
                <th class="p-4 text-center">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-[#d4af37]/15">
              ${expenses.map(e => `
                <tr class="hover:bg-[#1a1d29]/60 transition-colors">
                  <td class="p-4 font-mono text-[#a39c8e]">${e.date}</td>
                  <td class="p-4">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#1d212f] text-[#fceda8] border border-[#d4af37]/30">
                      ${e.category}
                    </span>
                  </td>
                  <td class="p-4 font-semibold text-[#faedd0]">${e.description}</td>
                  <td class="p-4 text-[#a39c8e]">${e.paymentMethod}</td>
                  <td class="p-4 text-[#a39c8e]">${e.recordedBy}</td>
                  <td class="p-4 font-bold text-rose-400 font-serif-luxury">
                    ${window.spaStore.formatPrice(e.amountAed, e.amountIdr)}
                  </td>
                  <td class="p-4 text-center">
                    <button onclick="deleteExpenseEntry('${e.id}')" class="px-2.5 py-1 text-rose-400 hover:bg-rose-500/20 rounded-lg text-[10px] font-bold border border-rose-500/30">
                      Hapus
                    </button>
                  </td>
                </tr>
              `).join('')}
            </tbody>
          </table>
        </div>
      </div>
    `;
  }

  // --- PEMBUKUAN & GRAFIK TOTAL KESELURUHAN (Matches User Requirement 3) ---
  function renderAdminTransactionsHTML() {
    const summary = window.spaStore.getFinancialSummary();
    const bookings = window.spaStore.getBookings();

    return `
      <div class="space-y-8 animate-fade-in">
        <!-- Title & Export -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div>
            <h1 class="text-2xl font-bold text-[#faedd0] font-serif-luxury">Pembukuan & Rekap Keuangan</h1>
            <p class="text-xs text-[#a39c8e]">Sistem data terpadu grafik omset total, pengeluaran, dan laba bersih F55 Center</p>
          </div>
          <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-3.5 py-2 border border-[#d4af37]/40 text-[#faedd0] text-xs font-bold rounded-xl hover:bg-[#1a1d29]">Print Laporan</button>
            <button onclick="window.showToast('Laporan Keuangan berhasil diekspor!', 'success')" class="px-3.5 py-2 gold-gradient-bg text-black text-xs font-extrabold rounded-xl shadow-md">Export Excel</button>
          </div>
        </div>

        <!-- 4 Key Financial Metrics -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div class="bg-[#13161f] p-5 rounded-2xl border border-[#d4af37]/30 space-y-2">
            <span class="text-xs text-[#a39c8e] font-bold block uppercase tracking-wider">Total Pendapatan (Omset)</span>
            <span class="text-2xl font-black text-[#34d399] font-serif-luxury">${window.spaStore.formatPrice(summary.totalRevenueAed, summary.totalRevenueIdr)}</span>
            <span class="text-[10px] text-[#34d399] block font-semibold">+18% dari minggu lalu</span>
          </div>
          <div class="bg-[#13161f] p-5 rounded-2xl border border-[#d4af37]/30 space-y-2">
            <span class="text-xs text-[#a39c8e] font-bold block uppercase tracking-wider">Total Pengeluaran</span>
            <span class="text-2xl font-black text-rose-400 font-serif-luxury">${window.spaStore.formatPrice(summary.totalExpensesAed, summary.totalExpensesIdr)}</span>
            <span class="text-[10px] text-rose-400 block font-semibold">Biaya operasional & bahan</span>
          </div>
          <div class="bg-[#13161f] p-5 rounded-2xl border border-[#d4af37]/30 space-y-2">
            <span class="text-xs text-[#a39c8e] font-bold block uppercase tracking-wider">Laba Bersih (Net Profit)</span>
            <span class="text-2xl font-black text-[#fceda8] font-serif-luxury">${window.spaStore.formatPrice(summary.netProfitAed, summary.netProfitIdr)}</span>
            <span class="text-[10px] text-[#d4af37] block font-semibold">Margin: ${summary.profitMargin}%</span>
          </div>
          <div class="bg-[#13161f] p-5 rounded-2xl border border-[#d4af37]/30 space-y-2">
            <span class="text-xs text-[#a39c8e] font-bold block uppercase tracking-wider">Total Transaksi</span>
            <span class="text-2xl font-black text-[#faedd0] font-serif-luxury">${summary.totalBookingsCount} Reservasi</span>
            <span class="text-[10px] text-[#a39c8e] block font-semibold">Rata-rata 180 AED / tamu</span>
          </div>
        </div>

        <!-- REQUIRED SYSTEM DATA: GRAFIK DATA TOTAL KESELURUHAN -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <div class="lg:col-span-2 bg-[#13161f] p-6 rounded-3xl border border-[#d4af37]/30 shadow-xl space-y-4">
            <div class="flex items-center justify-between">
              <div>
                <h3 class="font-bold text-[#faedd0] text-lg font-serif-luxury">Grafik Data Total Keseluruhan</h3>
                <p class="text-xs text-[#a39c8e]">Perbandingan Pendapatan, Pengeluaran, dan Laba Bersih Mingguan</p>
              </div>
              <span class="px-2.5 py-1 bg-[#1a1d28] border border-[#d4af37]/30 rounded-xl text-[10px] font-bold text-[#fceda8]">Arus Kas Mingguan</span>
            </div>
            <div class="h-72 w-full relative">
              <canvas id="bookkeepingChart"></canvas>
            </div>
          </div>

          <div class="bg-[#13161f] p-6 rounded-3xl border border-[#d4af37]/30 shadow-xl space-y-4">
            <div>
              <h3 class="font-bold text-[#faedd0] text-lg font-serif-luxury">Alokasi Biaya Pengeluaran</h3>
              <p class="text-xs text-[#a39c8e]">Distribusi porsi biaya operasional spa</p>
            </div>
            <div class="h-72 w-full relative">
              <canvas id="expenseCategoryChart"></canvas>
            </div>
          </div>
        </div>

        <!-- Transactions History Table -->
        <div class="bg-[#13161f] rounded-2xl border border-[#d4af37]/30 shadow-xl overflow-x-auto">
          <div class="p-4 border-b border-[#d4af37]/20 flex items-center justify-between">
            <h3 class="font-bold text-[#faedd0] text-sm font-serif-luxury">Daftar Pemasukan Terkini</h3>
            <span class="text-xs text-[#a39c8e]">${bookings.length} Transaksi Tercatat</span>
          </div>
          <table class="w-full text-left text-xs">
            <thead>
              <tr class="bg-[#171b26] text-[#d4af37] uppercase text-[10px] font-bold border-b border-[#d4af37]/20">
                <th class="p-4">Jam</th>
                <th class="p-4">Tamu (Nama & HP)</th>
                <th class="p-4">Layanan</th>
                <th class="p-4">Terapis</th>
                <th class="p-4">Durasi</th>
                <th class="p-4">Metode Bayar</th>
                <th class="p-4 font-mono">Pemasukan</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-[#d4af37]/15">
              ${bookings.map(b => `
                <tr class="hover:bg-[#1a1d29]/60 transition-colors">
                  <td class="p-4 font-mono text-[#a39c8e]">${b.time}</td>
                  <td class="p-4 font-bold text-[#faedd0]">
                    <div>${b.customerName}</div>
                    <div class="text-[10px] text-[#a39c8e] font-normal">${b.customerPhone}</div>
                  </td>
                  <td class="p-4 text-[#fceda8]">${b.serviceName}</td>
                  <td class="p-4 text-[#a39c8e]">${b.therapistName}</td>
                  <td class="p-4 text-[#a39c8e]">${b.duration} Menit</td>
                  <td class="p-4 text-[#a39c8e]">${b.paymentMethod}</td>
                  <td class="p-4 font-bold text-[#34d399] font-serif-luxury">${window.spaStore.formatPrice(b.priceAed || 150, b.price)}</td>
                </tr>
              `).join('')}
            </tbody>
          </table>
        </div>
      </div>
    `;
  }

  // --- ADMIN & BOS DASHBOARD (Matches User Requirement 2 with Image 2 Diagrams) ---
  function renderAdminDashboardHTML() {
    const stats = window.spaStore.getDashboardStats();
    const role = window.spaStore.getActiveRole();
    const isBos = role === 'bos';

    return `
      <div class="space-y-8 animate-fade-in">
        <!-- Executive Role Announcement Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-6 rounded-3xl border border-[#d4af37]/40 bg-gradient-to-r from-[#11131a] via-[#161a25] to-[#11131a] shadow-2xl relative overflow-hidden">
          <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-[#d4af37]/10 rounded-full blur-3xl pointer-events-none"></div>

          <div class="space-y-2 relative z-10">
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full ${isBos ? 'bg-amber-500/20 border border-amber-500/50 text-[#fceda8]' : 'bg-emerald-500/20 border border-emerald-500/50 text-emerald-300'} text-xs font-bold uppercase tracking-wider">
              <i data-lucide="${isBos ? 'crown' : 'shield-check'}" class="w-4 h-4"></i>
              <span>${isBos ? '👑 Role: Bos (Owner) — Mode Pemantauan Eksekutif' : '🛠️ Role: Admin Operasional — Full Access'}</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-[#faedd0] font-serif-luxury tracking-wide">
              ${isBos ? 'Executive Owner Analytics Dashboard' : 'Operational Spa Dashboard'}
            </h1>
            <p class="text-xs md:text-sm text-[#a39c8e] max-w-xl">
              ${isBos ? 'Pantau performa omset harian, tren reservasi, dan arus kas F55 Men Relaxation Center secara real-time.' : 'Kelola reservasi tamu, sesi tunda/tambah waktu, biaya pengeluaran, dan ketersediaan terapis.'}
            </p>
          </div>

          <div class="relative z-10 flex items-center gap-2">
            <button onclick="navigateTo('admin-transactions')" class="px-4 py-2.5 gold-outline-btn font-bold text-xs rounded-xl flex items-center gap-2">
              <i data-lucide="receipt" class="w-4 h-4 text-[#d4af37]"></i>
              <span>Buka Pembukuan</span>
            </button>
            ${!isBos ? `
              <button onclick="openAddExpenseModal()" class="px-4 py-2.5 gold-gradient-bg text-black font-extrabold text-xs rounded-xl shadow-lg flex items-center gap-2">
                <i data-lucide="plus" class="w-4 h-4 text-black"></i>
                <span>+ Pengeluaran</span>
              </button>
            ` : ''}
          </div>
        </div>

        <!-- 5 Key Metrics from Image 2 context -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
          <!-- Today's Revenue -->
          <div class="bg-[#13161f] p-5 rounded-2xl border border-[#d4af37]/30 shadow-md space-y-2">
            <div class="flex items-center justify-between">
              <span class="text-[10px] font-bold text-[#a39c8e] uppercase tracking-wider">Today's Revenue</span>
              <div class="p-2 bg-emerald-500/10 text-emerald-400 rounded-xl border border-emerald-500/20"><i data-lucide="wallet" class="w-4 h-4"></i></div>
            </div>
            <div>
              <span class="text-xl font-black text-[#34d399] font-serif-luxury">${window.spaStore.formatPrice(stats.todayRevenueAed, stats.todayRevenueIdr)}</span>
              <span class="text-[10px] text-emerald-400 font-bold block mt-1">+14% dari kemarin</span>
            </div>
          </div>

          <!-- Today's Bookings -->
          <div class="bg-[#13161f] p-5 rounded-2xl border border-[#d4af37]/30 shadow-md space-y-2">
            <div class="flex items-center justify-between">
              <span class="text-[10px] font-bold text-[#a39c8e] uppercase tracking-wider">Today's Bookings</span>
              <div class="p-2 bg-blue-500/10 text-blue-400 rounded-xl border border-blue-500/20"><i data-lucide="calendar" class="w-4 h-4"></i></div>
            </div>
            <div>
              <span class="text-2xl font-black text-[#faedd0] font-serif-luxury">${stats.todayBookingsCount}</span>
              <span class="text-[10px] text-blue-400 font-bold block mt-1">24 Terkonfirmasi</span>
            </div>
          </div>

          <!-- Active Sessions Live -->
          <div class="bg-[#13161f] p-5 rounded-2xl border border-[#d4af37]/30 shadow-md space-y-2">
            <div class="flex items-center justify-between">
              <span class="text-[10px] font-bold text-[#a39c8e] uppercase tracking-wider">Active Sessions</span>
              <div class="p-2 bg-amber-500/10 text-amber-400 rounded-xl border border-amber-500/20"><i data-lucide="clock" class="w-4 h-4"></i></div>
            </div>
            <div>
              <span class="text-2xl font-black text-amber-400 animate-pulse font-serif-luxury">${stats.activeSessionsCount}</span>
              <span class="text-[10px] text-amber-400 font-bold block mt-1">Live Timer Berjalan</span>
            </div>
          </div>

          <!-- Pengeluaran Hari Ini -->
          <div class="bg-[#13161f] p-5 rounded-2xl border border-[#d4af37]/30 shadow-md space-y-2">
            <div class="flex items-center justify-between">
              <span class="text-[10px] font-bold text-[#a39c8e] uppercase tracking-wider">Today's Expense</span>
              <div class="p-2 bg-rose-500/10 text-rose-400 rounded-xl border border-rose-500/20"><i data-lucide="trending-down" class="w-4 h-4"></i></div>
            </div>
            <div>
              <span class="text-xl font-black text-rose-400 font-serif-luxury">${window.spaStore.formatPrice(stats.todayExpensesAed, stats.todayExpensesIdr)}</span>
              <span class="text-[10px] text-rose-400 font-bold block mt-1">Bahan & Es Bath</span>
            </div>
          </div>

          <!-- Available Therapists -->
          <div class="bg-[#13161f] p-5 rounded-2xl border border-[#d4af37]/30 shadow-md space-y-2">
            <div class="flex items-center justify-between">
              <span class="text-[10px] font-bold text-[#a39c8e] uppercase tracking-wider">Available Therapists</span>
              <div class="p-2 bg-teal-500/10 text-teal-400 rounded-xl border border-teal-500/20"><i data-lucide="user-check" class="w-4 h-4"></i></div>
            </div>
            <div>
              <span class="text-2xl font-black text-[#faedd0] font-serif-luxury">${stats.availableTherapists} / ${stats.totalTherapists}</span>
              <span class="text-[10px] text-teal-400 font-bold block mt-1">Siap Melayani</span>
            </div>
          </div>
        </div>

        <!-- DIAGRAMS GRID MATCHING IMAGE 2 -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          
          <!-- DIAGRAM 1: PENDAPATAN 7 HARI TERAKHIR (Spline Line Chart) -->
          <div class="lg:col-span-2 bg-[#13161f] p-6 rounded-3xl border border-[#d4af37]/30 shadow-xl space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
              <div>
                <h3 class="font-bold text-[#faedd0] text-lg font-serif-luxury">Pendapatan 7 Hari Terakhir</h3>
                <p class="text-xs text-[#a39c8e]">Grafik omset harian spa dalam minggu ini</p>
              </div>
              <div class="flex items-center gap-1 bg-[#1a1d29] p-1 rounded-xl text-xs font-bold border border-[#d4af37]/20">
                <button onclick="window.spaCharts.initRevenueChart('adminRevenueChart', 'today')" class="px-3 py-1 rounded-lg text-[#a39c8e] hover:text-white transition-colors">Today</button>
                <button onclick="window.spaCharts.initRevenueChart('adminRevenueChart', 'week')" class="px-3 py-1 rounded-lg bg-[#24293a] text-[#fceda8] border border-[#d4af37]/40 shadow-sm">This Week</button>
                <button onclick="window.spaCharts.initRevenueChart('adminRevenueChart', 'month')" class="px-3 py-1 rounded-lg text-[#a39c8e] hover:text-white transition-colors">This Month</button>
              </div>
            </div>

            <div class="h-64 sm:h-72 w-full relative">
              <canvas id="adminRevenueChart"></canvas>
            </div>
          </div>

          <!-- DIAGRAM 2: LAYANAN TERPOPULER (Donut Chart) -->
          <div class="bg-[#13161f] p-6 rounded-3xl border border-[#d4af37]/30 shadow-xl space-y-4">
            <div>
              <h3 class="font-bold text-[#faedd0] text-lg font-serif-luxury">Layanan Terpopuler</h3>
              <p class="text-xs text-[#a39c8e]">Persentase porsi pemesanan layanan</p>
            </div>
            <div class="h-64 sm:h-72 w-full relative">
              <canvas id="adminServiceChart"></canvas>
            </div>
          </div>

        </div>

        <!-- Live Sessions & Recent Bookings -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <!-- Active Sessions Monitor -->
          <div class="bg-[#13161f] p-6 rounded-3xl border border-[#d4af37]/30 shadow-xl space-y-4">
            <div class="flex items-center justify-between border-b border-[#d4af37]/20 pb-3">
              <h3 class="font-bold text-[#faedd0] text-base font-serif-luxury">Monitor Sesi Aktif Berjalan</h3>
              <span class="text-xs text-amber-400 font-bold">${window.spaStore.getActiveSessions().length} Sesi</span>
            </div>
            <div class="space-y-3">
              ${window.spaStore.getActiveSessions().map(s => `
                <div class="p-3.5 bg-[#171b26] rounded-xl border border-[#d4af37]/20 flex items-center justify-between">
                  <div>
                    <span class="font-bold text-xs text-[#faedd0] block">${s.customerName}</span>
                    <span class="text-[11px] text-[#d4af37]">${s.serviceName} • Terapis: ${s.therapistName}</span>
                  </div>
                  <div class="text-right">
                    <span class="text-xs font-mono font-bold text-amber-400 block">${Math.floor(s.remainingSeconds / 60)}m ${s.remainingSeconds % 60}s</span>
                    <span class="text-[9px] uppercase px-2 py-0.5 rounded-full ${s.isPaused ? 'bg-amber-500/20 text-amber-300' : 'bg-emerald-500/20 text-emerald-300'} font-bold">
                      ${s.isPaused ? 'Paused' : 'Running'}
                    </span>
                  </div>
                </div>
              `).join('')}
            </div>
          </div>

          <!-- Quick Recent Bookings -->
          <div class="bg-[#13161f] p-6 rounded-3xl border border-[#d4af37]/30 shadow-xl space-y-4">
            <div class="flex items-center justify-between border-b border-[#d4af37]/20 pb-3">
              <h3 class="font-bold text-[#faedd0] text-base font-serif-luxury">Reservasi Terkini</h3>
              <a href="#admin-bookings" class="text-xs text-[#d4af37] hover:underline font-bold">Lihat Semua</a>
            </div>
            <div class="space-y-3">
              ${window.spaStore.getBookings().slice(0, 4).map(b => `
                <div class="p-3.5 bg-[#171b26] rounded-xl border border-[#d4af37]/20 flex items-center justify-between">
                  <div>
                    <span class="font-bold text-xs text-[#faedd0] block">${b.customerName}</span>
                    <span class="text-[11px] text-[#a39c8e]">${b.serviceName} • ${b.time}</span>
                  </div>
                  <div class="text-right">
                    <span class="font-extrabold text-xs text-[#fceda8] block font-serif-luxury">${window.spaStore.formatPrice(b.priceAed, b.price)}</span>
                    <span class="text-[9px] uppercase font-bold text-emerald-400">${b.status}</span>
                  </div>
                </div>
              `).join('')}
            </div>
          </div>
        </div>
      </div>
    `;
  }

  // --- OTHER ADMIN VIEWS ---
  function renderAdminBookingsHTML() {
    const bookings = window.spaStore.getBookings();

    return `
      <div class="space-y-6 animate-fade-in">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-bold text-[#faedd0] font-serif-luxury">Daftar Reservasi & Booking Tamu</h1>
            <p class="text-xs text-[#a39c8e]">Semua jadwal pemesanan layanan spa F55</p>
          </div>
        </div>

        <div class="bg-[#13161f] rounded-2xl border border-[#d4af37]/30 shadow-xl overflow-x-auto">
          <table class="w-full text-left text-xs">
            <thead>
              <tr class="bg-[#171b26] text-[#d4af37] uppercase text-[10px] font-bold border-b border-[#d4af37]/20">
                <th class="p-4">Kode</th>
                <th class="p-4">Nama Tamu & HP</th>
                <th class="p-4">Layanan</th>
                <th class="p-4">Terapis</th>
                <th class="p-4">Waktu</th>
                <th class="p-4 font-mono">Tarif</th>
                <th class="p-4">Status</th>
                <th class="p-4 text-center">Aksi Sesi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-[#d4af37]/15">
              ${bookings.map(b => `
                <tr class="hover:bg-[#1a1d29]/60 transition-colors">
                  <td class="p-4 font-mono font-bold text-[#d4af37]">${b.id}</td>
                  <td class="p-4 font-bold text-[#faedd0]">
                    <div>${b.customerName}</div>
                    <div class="text-[10px] text-[#a39c8e] font-normal">${b.customerPhone}</div>
                  </td>
                  <td class="p-4 text-[#fceda8]">${b.serviceName}</td>
                  <td class="p-4 text-[#a39c8e]">${b.therapistName}</td>
                  <td class="p-4 text-[#a39c8e]">${b.date} • ${b.time}</td>
                  <td class="p-4 font-bold text-[#fceda8] font-serif-luxury">${window.spaStore.formatPrice(b.priceAed, b.price)}</td>
                  <td class="p-4">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold ${b.status === 'completed' ? 'badge-completed' : (b.status === 'in_progress' ? 'badge-busy' : 'badge-confirmed')}">
                      ${b.status}
                    </span>
                  </td>
                  <td class="p-4 text-center">
                    ${b.status === 'confirmed' ? `
                      <button onclick="startSpaSessionFromAdmin('${b.id}')" class="px-3 py-1 bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 rounded-lg text-[10px] font-bold hover:bg-emerald-500/30">
                        Mulai Spa
                      </button>
                    ` : `<span class="text-[10px] text-[#a39c8e]">-</span>`}
                  </td>
                </tr>
              `).join('')}
            </tbody>
          </table>
        </div>
      </div>
    `;
  }

  function renderAdminSessionsHTML() {
    const sessions = window.spaStore.getActiveSessions();

    return `
      <div class="space-y-6 animate-fade-in">
        <div>
          <h1 class="text-2xl font-bold text-[#faedd0] font-serif-luxury">Live Timer Sesi Spa</h1>
          <p class="text-xs text-[#a39c8e]">Pantau sesi perawatan yang sedang berjalan di kamar treatment</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          ${sessions.map(s => `
            <div class="bg-[#13161f] p-6 rounded-3xl border ${s.remainingSeconds <= 600 ? 'border-rose-500 timer-warning-glow' : 'border-[#d4af37]/40'} shadow-2xl space-y-4">
              <div class="flex items-center justify-between">
                <div>
                  <h3 class="font-bold text-[#faedd0] text-lg">${s.customerName}</h3>
                  <p class="text-xs text-[#d4af37]">${s.serviceName} • Terapis: ${s.therapistName}</p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-bold ${s.isPaused ? 'bg-amber-500/20 text-amber-300' : 'bg-emerald-500/20 text-emerald-300'} animate-pulse">
                  ${s.isPaused ? 'PAUSED' : 'LIVE'}
                </span>
              </div>

              <!-- Big Timer Display -->
              <div class="p-6 bg-[#090a0d] rounded-2xl border border-[#d4af37]/25 text-center space-y-1">
                <span class="text-4xl sm:text-5xl font-mono font-black text-[#faedd0] tracking-wider">
                  ${String(Math.floor(s.remainingSeconds / 60)).padStart(2, '0')}:${String(s.remainingSeconds % 60).padStart(2, '0')}
                </span>
                <span class="text-[11px] text-[#a39c8e] block">Sisa Waktu Perawatan</span>
              </div>

              <!-- Controls -->
              <div class="grid grid-cols-3 gap-2">
                <button onclick="togglePauseSession('${s.id}')" class="py-2.5 bg-[#1e2230] hover:bg-[#262c3e] text-[#faedd0] text-xs font-bold rounded-xl border border-[#d4af37]/25">
                  ${s.isPaused ? '▶ Resume' : '⏸ Pause'}
                </button>
                <button onclick="openAddTimeModal('${s.id}')" class="py-2.5 gold-gradient-bg text-black text-xs font-extrabold rounded-xl shadow-md">
                  + Tambah Waktu
                </button>
                <button onclick="confirmFinishSession('${s.id}')" class="py-2.5 bg-rose-500/20 hover:bg-rose-500/30 text-rose-300 text-xs font-bold rounded-xl border border-rose-500/40">
                  🔴 Selesai
                </button>
              </div>
            </div>
          `).join('')}
        </div>
      </div>
    `;
  }

  function renderAdminTherapistsHTML() {
    const therapists = window.spaStore.getTherapists();

    return `
      <div class="space-y-6 animate-fade-in">
        <div>
          <h1 class="text-2xl font-bold text-[#faedd0] font-serif-luxury">Tim Terapis Profesional</h1>
          <p class="text-xs text-[#a39c8e]">Status ketersediaan dan keahlian spesialisasi terapis F55</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
          ${therapists.map(t => `
            <div class="bg-[#13161f] p-5 rounded-2xl border border-[#d4af37]/30 poster-card space-y-3">
              <div class="flex items-center gap-3">
                <img src="${t.image}" class="w-12 h-12 rounded-xl object-cover border border-[#d4af37]/40">
                <div>
                  <h3 class="font-bold text-[#faedd0] text-sm">${t.name}</h3>
                  <span class="text-[10px] text-[#d4af37] block">${t.specialty}</span>
                </div>
              </div>
              <div class="flex items-center justify-between text-xs pt-2 border-t border-[#d4af37]/20">
                <span class="text-[#a39c8e]">Status:</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold ${t.status === 'ready' ? 'badge-ready' : (t.status === 'busy' ? 'badge-busy' : 'badge-off')}">
                  ${t.status.toUpperCase()}
                </span>
              </div>
            </div>
          `).join('')}
        </div>
      </div>
    `;
  }

  function renderAdminServicesHTML() {
    return renderServicesCatalogHTML();
  }

  function renderAdminReportsHTML() {
    return renderAdminTransactionsHTML();
  }

  function renderAdminSettingsHTML() {
    return `
      <div class="space-y-6 animate-fade-in max-w-2xl mx-auto">
        <div>
          <h1 class="text-2xl font-bold text-[#faedd0] font-serif-luxury">Pengaturan Sistem F55</h1>
          <p class="text-xs text-[#a39c8e]">Konfigurasi spa, mata uang, dan manajemen data</p>
        </div>

        <div class="bg-[#13161f] p-6 rounded-2xl border border-[#d4af37]/30 space-y-4">
          <h3 class="font-bold text-[#faedd0] text-sm">Reset & Pemulihan Data</h3>
          <p class="text-xs text-[#a39c8e]">Kembalikan semua layanan, terapis, dan pembukuan ke data bawaan F55 Center.</p>
          <button onclick="handleFactoryReset()" class="px-4 py-2 bg-rose-500/20 text-rose-300 border border-rose-500/40 rounded-xl text-xs font-bold hover:bg-rose-500/30">
            Reset ke Data Awal F55
          </button>
        </div>
      </div>
    `;
  }

  // --- CUSTOMER BOOKING WIZARD ---
  function renderBookingWizardHTML() {
    const services = window.spaStore.getServices();
    const therapists = window.spaStore.getTherapists();

    return `
      <div class="max-w-2xl mx-auto space-y-6 animate-fade-in">
        <div class="text-center space-y-2">
          <h1 class="text-2xl sm:text-3xl font-extrabold text-[#faedd0] font-serif-luxury">Reservasi Perawatan Spa</h1>
          <p class="text-xs text-[#a39c8e]">Lengkapi formulir pemesanan untuk reservasi sesi relaksasi Anda</p>
        </div>

        <form onsubmit="handleBookingWizardSubmit(event)" class="bg-[#13161f] p-6 sm:p-8 rounded-3xl border border-[#d4af37]/30 space-y-5 shadow-2xl">
          <div>
            <label class="block text-xs font-bold text-[#faedd0] mb-1.5">Nama Lengkap Anda</label>
            <input type="text" id="bw-name" required value="Haikal Ahmad"
              class="w-full p-3 bg-[#1a1d29] border border-[#d4af37]/30 rounded-xl text-xs text-[#faedd0] focus:border-[#d4af37] outline-none">
          </div>

          <div>
            <label class="block text-xs font-bold text-[#faedd0] mb-1.5">Nomor HP / WhatsApp (Wajib)</label>
            <input type="tel" id="bw-phone" required value="081234567890"
              class="w-full p-3 bg-[#1a1d29] border border-[#d4af37]/30 rounded-xl text-xs text-[#faedd0] focus:border-[#d4af37] outline-none">
          </div>

          <div>
            <label class="block text-xs font-bold text-[#faedd0] mb-1.5">Pilih Menu Massage</label>
            <select id="bw-service" onchange="handleServiceSelectionChange(this.value)"
              class="w-full p-3 bg-[#1a1d29] border border-[#d4af37]/30 rounded-xl text-xs text-[#faedd0] focus:border-[#d4af37] outline-none font-bold">
              ${services.map(s => `
                <option value="${s.id}">${s.menuNumber}. ${s.name} (${s.duration} Min - ${window.spaStore.formatPrice(s.priceAed, s.price)})</option>
              `).join('')}
            </select>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-[#faedd0] mb-1.5">Tanggal Treatment</label>
              <input type="date" id="bw-date" required value="${new Date().toISOString().split('T')[0]}"
                class="w-full p-3 bg-[#1a1d29] border border-[#d4af37]/30 rounded-xl text-xs text-[#faedd0] focus:border-[#d4af37] outline-none">
            </div>
            <div>
              <label class="block text-xs font-bold text-[#faedd0] mb-1.5">Jam Treatment</label>
              <select id="bw-time"
                class="w-full p-3 bg-[#1a1d29] border border-[#d4af37]/30 rounded-xl text-xs text-[#faedd0] focus:border-[#d4af37] outline-none">
                <option value="11:00">11:00</option>
                <option value="13:00">13:00</option>
                <option value="14:00" selected>14:00</option>
                <option value="16:00">16:00</option>
                <option value="18:00">18:00</option>
                <option value="20:00">20:00</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-[#faedd0] mb-1.5">Pilih Terapis Pilihan</label>
            <select id="bw-therapist"
              class="w-full p-3 bg-[#1a1d29] border border-[#d4af37]/30 rounded-xl text-xs text-[#faedd0] focus:border-[#d4af37] outline-none">
              ${therapists.map(t => `
                <option value="${t.id}">${t.name} - ${t.specialty} (${t.status.toUpperCase()})</option>
              `).join('')}
            </select>
          </div>

          <button type="submit"
            class="w-full py-4 gold-gradient-bg text-black font-extrabold text-sm rounded-xl shadow-xl hover:scale-105 transition-all">
            Konfirmasi Booking Sekarang
          </button>
        </form>
      </div>
    `;
  }

  function renderCustomerBookingsHTML() {
    const bookings = window.spaStore.getBookings();

    return `
      <div class="space-y-6 animate-fade-in max-w-4xl mx-auto">
        <div>
          <h1 class="text-2xl font-bold text-[#faedd0] font-serif-luxury">Riwayat Reservasi Saya</h1>
          <p class="text-xs text-[#a39c8e]">Daftar perawatan spa yang telah dipesan</p>
        </div>

        <div class="space-y-4">
          ${bookings.map(b => `
            <div class="bg-[#13161f] p-5 rounded-2xl border border-[#d4af37]/30 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
              <div class="space-y-1">
                <span class="text-[10px] font-mono text-[#d4af37] font-bold">${b.id}</span>
                <h3 class="font-bold text-[#faedd0] text-base">${b.serviceName}</h3>
                <p class="text-xs text-[#a39c8e]">Terapis: <strong>${b.therapistName}</strong> • ${b.date} pukul ${b.time}</p>
              </div>
              <div class="text-right">
                <span class="text-base font-extrabold text-[#fceda8] block font-serif-luxury">${window.spaStore.formatPrice(b.priceAed, b.price)}</span>
                <span class="text-[10px] uppercase font-bold text-emerald-400">${b.status}</span>
              </div>
            </div>
          `).join('')}
        </div>
      </div>
    `;
  }

  function renderCustomerProfileHTML() {
    const user = window.spaStore.state.currentUser;

    return `
      <div class="max-w-xl mx-auto bg-[#13161f] p-6 sm:p-8 rounded-3xl border border-[#d4af37]/30 space-y-6 shadow-2xl animate-fade-in">
        <div class="flex items-center gap-4 border-b border-[#d4af37]/20 pb-6">
          <img src="${user.avatar}" class="w-16 h-16 rounded-2xl object-cover border border-[#d4af37]">
          <div>
            <h2 class="text-xl font-bold text-[#faedd0] font-serif-luxury">${user.name}</h2>
            <span class="text-xs text-[#d4af37] font-bold uppercase tracking-wider">${user.role.toUpperCase()} PROFILE</span>
          </div>
        </div>

        <div class="space-y-3 text-xs">
          <div class="flex justify-between py-2 border-b border-[#d4af37]/10">
            <span class="text-[#a39c8e]">Nomor WhatsApp</span>
            <span class="text-[#faedd0] font-mono font-bold">${user.phone}</span>
          </div>
          <div class="flex justify-between py-2 border-b border-[#d4af37]/10">
            <span class="text-[#a39c8e]">Email</span>
            <span class="text-[#faedd0]">${user.email}</span>
          </div>
          <div class="flex justify-between py-2 border-b border-[#d4af37]/10">
            <span class="text-[#a39c8e]">Status Keanggotaan</span>
            <span class="text-[#fceda8] font-bold">VIP Platinum Guest</span>
          </div>
        </div>
      </div>
    `;
  }

  // --- AUTH VIEWS ---
  function renderLoginHTML() {
    return `
      <div class="min-h-screen flex items-center justify-center p-6 bg-[#090a0d] animate-fade-in">
        <div class="w-full max-w-md bg-[#13161f] p-8 rounded-3xl border border-[#d4af37]/40 shadow-2xl space-y-6">
          <div class="text-center space-y-2">
            <h1 class="text-2xl font-black text-[#faedd0] font-serif-luxury">F55 Spa Portal</h1>
            <p class="text-xs text-[#a39c8e]">Silakan masuk untuk melanjutkan reservasi</p>
          </div>
          <form onsubmit="handleCustomerLoginSubmit(event)" class="space-y-4 text-xs">
            <div>
              <label class="block font-bold text-[#faedd0] mb-1">Nomor HP</label>
              <input type="text" required value="081234567890" class="w-full p-3 bg-[#1a1d29] border border-[#d4af37]/30 rounded-xl text-[#faedd0]">
            </div>
            <div>
              <label class="block font-bold text-[#faedd0] mb-1">Password</label>
              <input type="password" required value="password123" class="w-full p-3 bg-[#1a1d29] border border-[#d4af37]/30 rounded-xl text-[#faedd0]">
            </div>
            <button type="submit" class="w-full py-3.5 gold-gradient-bg text-black font-extrabold text-sm rounded-xl">
              Masuk
            </button>
          </form>
          <div class="text-center text-xs text-[#a39c8e] pt-3 border-t border-[#d4af37]/15">
            <a href="#admin-dashboard" class="text-[#d4af37] font-bold hover:underline">Masuk ke Dashboard Admin / Bos</a>
          </div>
        </div>
      </div>
    `;
  }

  function renderAdminLoginHTML() {
    return `
      <div class="min-h-screen flex items-center justify-center p-6 bg-[#090a0d] animate-fade-in">
        <div class="w-full max-w-md bg-[#13161f] p-8 rounded-3xl border border-[#d4af37]/40 shadow-2xl space-y-6">
          <div class="text-center space-y-2">
            <h1 class="text-2xl font-black text-[#faedd0] font-serif-luxury">Portal Manajemen F55</h1>
            <p class="text-xs text-[#a39c8e]">Pilih role untuk masuk ke sistem</p>
          </div>
          <div class="space-y-3">
            <button onclick="switchRoleTo('admin')" class="w-full py-3.5 bg-[#1a1d29] hover:bg-[#222738] text-emerald-400 font-extrabold text-xs rounded-xl border border-emerald-500/40">
              Masuk sebagai Admin Operasional
            </button>
            <button onclick="switchRoleTo('bos')" class="w-full py-3.5 gold-gradient-bg text-black font-extrabold text-xs rounded-xl shadow-lg">
              👑 Masuk sebagai Bos (Owner - Lihat Dashboard Saja)
            </button>
          </div>
        </div>
      </div>
    `;
  }

  function renderRegisterHTML() {
    return renderLoginHTML();
  }

  // =========================================================================
  // MODALS & EVENT HANDLERS
  // =========================================================================

  // Modal: Tambah Pengeluaran Baru
  window.openAddExpenseModal = function () {
    const existing = document.getElementById('expense-modal');
    if (existing) existing.remove();

    const modal = document.createElement('div');
    modal.id = 'expense-modal';
    modal.className = 'fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm animate-fade-in';
    modal.innerHTML = `
      <div class="w-full max-w-lg bg-[#13161f] p-6 sm:p-8 rounded-3xl border border-[#d4af37]/40 shadow-2xl space-y-5">
        <div class="flex items-center justify-between border-b border-[#d4af37]/20 pb-4">
          <div class="flex items-center gap-2.5">
            <div class="p-2 bg-rose-500/20 text-rose-300 rounded-xl">
              <i data-lucide="trending-down" class="w-5 h-5"></i>
            </div>
            <div>
              <h3 class="font-bold text-[#faedd0] text-base font-serif-luxury">Catat Pengeluaran Baru</h3>
              <p class="text-[10px] text-[#a39c8e]">Input biaya operasional spa, bahan, dan komisi</p>
            </div>
          </div>
          <button onclick="document.getElementById('expense-modal').remove()" class="text-[#a39c8e] hover:text-white p-1">
            <i data-lucide="x" class="w-5 h-5"></i>
          </button>
        </div>

        <form onsubmit="handleExpenseFormSubmit(event)" class="space-y-4 text-xs">
          <div>
            <label class="block font-bold text-[#faedd0] mb-1">Kategori Pengeluaran</label>
            <select id="exp-category" required class="w-full p-3 bg-[#1a1d29] border border-[#d4af37]/30 rounded-xl text-[#faedd0] outline-none">
              <option value="Bahan & Minyak Spa">Bahan & Minyak Spa</option>
              <option value="Ice Bath & Ice Pool">Ice Bath & Ice Pool (Pasokan Es)</option>
              <option value="Laundry & Handuk Premium">Laundry & Handuk Premium</option>
              <option value="Komisi Terapis Harian">Komisi Terapis Harian</option>
              <option value="Utilitas & Perawatan">Utilitas (Listrik, Air & Perawatan)</option>
              <option value="Konsumsi & Minuman">Konsumsi & Minuman Tamu</option>
              <option value="Perlengkapan Alat">Perlengkapan & Alat Spa</option>
            </select>
          </div>

          <div>
            <label class="block font-bold text-[#faedd0] mb-1">Keterangan / Rincian Pengeluaran</label>
            <input type="text" id="exp-desc" required placeholder="Contoh: Beli minyak aromaterapi lavender 5 liter"
              class="w-full p-3 bg-[#1a1d29] border border-[#d4af37]/30 rounded-xl text-[#faedd0] outline-none">
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block font-bold text-[#faedd0] mb-1">Nominal (AED)</label>
              <input type="number" id="exp-amount-aed" required placeholder="Contoh: 150"
                class="w-full p-3 bg-[#1a1d29] border border-[#d4af37]/30 rounded-xl text-[#faedd0] outline-none">
            </div>
            <div>
              <label class="block font-bold text-[#faedd0] mb-1">Metode Pembayaran</label>
              <select id="exp-payment" class="w-full p-3 bg-[#1a1d29] border border-[#d4af37]/30 rounded-xl text-[#faedd0] outline-none">
                <option value="Transfer Bank">Transfer Bank</option>
                <option value="Cash Kasir">Cash Kasir</option>
                <option value="Kartu Kredit">Kartu Kredit</option>
              </select>
            </div>
          </div>

          <button type="submit" class="w-full py-3.5 gold-gradient-bg text-black font-extrabold text-xs rounded-xl shadow-lg mt-2">
            Simpan Pengeluaran
          </button>
        </form>
      </div>
    `;

    document.body.appendChild(modal);
    if (window.lucide) window.lucide.createIcons();
  };

  window.handleExpenseFormSubmit = function (e) {
    e.preventDefault();
    const category = document.getElementById('exp-category').value;
    const desc = document.getElementById('exp-desc').value;
    const amountAed = Number(document.getElementById('exp-amount-aed').value);
    const payment = document.getElementById('exp-payment').value;

    window.spaStore.addExpense({
      category,
      description: desc,
      amountAed,
      paymentMethod: payment,
      recordedBy: 'Admin F55'
    });

    const modal = document.getElementById('expense-modal');
    if (modal) modal.remove();

    window.showToast('Pengeluaran berhasil dicatat!', 'success');
    renderCurrentRoute();
  };

  window.deleteExpenseEntry = function (id) {
    if (confirm('Hapus catatan pengeluaran ini?')) {
      window.spaStore.deleteExpense(id);
      window.showToast('Catatan pengeluaran dihapus.', 'warning');
      renderCurrentRoute();
    }
  };

  // Modal: Tambah Biodata Baru (Nama dan Nomor HP)
  window.openAddBiodataModal = function () {
    const existing = document.getElementById('biodata-modal');
    if (existing) existing.remove();

    const modal = document.createElement('div');
    modal.id = 'biodata-modal';
    modal.className = 'fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm animate-fade-in';
    modal.innerHTML = `
      <div class="w-full max-w-lg bg-[#13161f] p-6 sm:p-8 rounded-3xl border border-[#d4af37]/40 shadow-2xl space-y-5">
        <div class="flex items-center justify-between border-b border-[#d4af37]/20 pb-4">
          <div class="flex items-center gap-2.5">
            <div class="p-2 bg-emerald-500/20 text-emerald-300 rounded-xl">
              <i data-lucide="user-plus" class="w-5 h-5"></i>
            </div>
            <div>
              <h3 class="font-bold text-[#faedd0] text-base font-serif-luxury">Tambah Biodata Pelanggan Baru</h3>
              <p class="text-[10px] text-[#a39c8e]">Pencatatan data kontak: Nama Lengkap dan Nomor HP</p>
            </div>
          </div>
          <button onclick="document.getElementById('biodata-modal').remove()" class="text-[#a39c8e] hover:text-white p-1">
            <i data-lucide="x" class="w-5 h-5"></i>
          </button>
        </div>

        <form onsubmit="handleBiodataFormSubmit(event)" class="space-y-4 text-xs">
          <div>
            <label class="block font-bold text-[#faedd0] mb-1">Nama Lengkap Tamu *</label>
            <input type="text" id="bio-name" required placeholder="Contoh: Ahmed Al Nuaimi"
              class="w-full p-3 bg-[#1a1d29] border border-[#d4af37]/30 rounded-xl text-[#faedd0] outline-none">
          </div>

          <div>
            <label class="block font-bold text-[#faedd0] mb-1">Nomor HP / WhatsApp *</label>
            <input type="tel" id="bio-phone" required placeholder="Contoh: +971501234567 atau 081234567890"
              class="w-full p-3 bg-[#1a1d29] border border-[#d4af37]/30 rounded-xl text-[#faedd0] outline-none">
          </div>

          <div>
            <label class="block font-bold text-[#faedd0] mb-1">Catatan / Preferensi Terapi Spa</label>
            <input type="text" id="bio-notes" placeholder="Contoh: Lebih suka tekanan kuat, langganan Ice Pool"
              class="w-full p-3 bg-[#1a1d29] border border-[#d4af37]/30 rounded-xl text-[#faedd0] outline-none">
          </div>

          <div>
            <label class="block font-bold text-[#faedd0] mb-1">Status Keanggotaan</label>
            <select id="bio-status" class="w-full p-3 bg-[#1a1d29] border border-[#d4af37]/30 rounded-xl text-[#faedd0] outline-none">
              <option value="Reguler">Reguler</option>
              <option value="VIP Member">VIP Member</option>
            </select>
          </div>

          <button type="submit" class="w-full py-3.5 gold-gradient-bg text-black font-extrabold text-xs rounded-xl shadow-lg mt-2">
            Simpan Biodata Tamu
          </button>
        </form>
      </div>
    `;

    document.body.appendChild(modal);
    if (window.lucide) window.lucide.createIcons();
  };

  window.handleBiodataFormSubmit = function (e) {
    e.preventDefault();
    const name = document.getElementById('bio-name').value;
    const phone = document.getElementById('bio-phone').value;
    const notes = document.getElementById('bio-notes').value;
    const status = document.getElementById('bio-status').value;

    window.spaStore.addBiodata({
      name,
      phone,
      notes,
      status
    });

    const modal = document.getElementById('biodata-modal');
    if (modal) modal.remove();

    window.showToast(`Biodata ${name} berhasil disimpan!`, 'success');
    renderCurrentRoute();
  };

  window.handleBiodataSearch = function (val) {
    window.appState.biodataSearchQuery = val;
    renderCurrentRoute();
  };

  window.deleteBiodataEntry = function (id) {
    if (confirm('Hapus kontak biodata ini?')) {
      window.spaStore.deleteBiodata(id);
      window.showToast('Biodata telah dihapus.', 'warning');
      renderCurrentRoute();
    }
  };

  // Live Timer and Session Controllers
  window.startSpaSessionFromAdmin = function (bookingId) {
    const session = window.spaStore.startSession(bookingId);
    if (session) {
      window.showToast(`🟢 Sesi spa untuk ${session.customerName} dimulai!`, 'success');
      navigateTo('admin-sessions');
    }
  };

  window.togglePauseSession = function (sessionId) {
    const session = window.spaStore.getActiveSessions().find(s => s.id === sessionId);
    if (session) {
      if (session.isPaused) {
        window.spaStore.resumeSession(sessionId);
        window.showToast('Sesi dilanjutkan.', 'success');
      } else {
        window.spaStore.pauseSession(sessionId);
        window.showToast('Sesi di-pause.', 'warning');
      }
      renderAdminView('admin-sessions');
    }
  };

  window.confirmFinishSession = function (sessionId) {
    if (confirm('Selesaikan sesi spa ini?')) {
      window.spaStore.finishSession(sessionId);
      window.showToast('🔴 Sesi spa telah diselesaikan.', 'success');
      renderAdminView('admin-sessions');
    }
  };

  window.openAddTimeModal = function (sessionId) {
    const mins = prompt('Berapa menit waktu tambahan? (contoh: 15 atau 30):', '15');
    if (mins && !isNaN(mins)) {
      window.spaStore.addTimeSession(sessionId, parseInt(mins));
      window.showToast(`Ditambahkan +${mins} menit ke sesi!`, 'success');
      renderAdminView('admin-sessions');
    }
  };

  window.selectServiceForBooking = function (serviceId) {
    window.appState.selectedServiceId = serviceId;
    navigateTo('booking');
  };

  window.setupBookingWizardListeners = function () {
    const srvSelect = document.getElementById('bw-service');
    if (srvSelect && window.appState.selectedServiceId) {
      srvSelect.value = window.appState.selectedServiceId;
    }
  };

  window.handleBookingWizardSubmit = function (e) {
    e.preventDefault();
    const name = document.getElementById('bw-name').value;
    const phone = document.getElementById('bw-phone').value;
    const serviceId = document.getElementById('bw-service').value;
    const service = window.spaStore.getServiceById(serviceId);
    const date = document.getElementById('bw-date').value;
    const time = document.getElementById('bw-time').value;
    const therapistId = document.getElementById('bw-therapist').value;
    const therapist = window.spaStore.getTherapistById(therapistId);

    const newBooking = window.spaStore.addBooking({
      customerName: name,
      customerPhone: phone,
      serviceId: service.id,
      serviceName: service.name,
      therapistId: therapist.id,
      therapistName: therapist.name,
      date,
      time,
      duration: service.duration,
      priceAed: service.priceAed,
      price: service.price,
      paymentMethod: 'Credit Card (Apple Pay)'
    });

    window.showToast(`Reservasi ${newBooking.id} berhasil! Terima kasih.`, 'success');
    navigateTo('bookings');
  };

  window.handleCustomerLoginSubmit = function (e) {
    e.preventDefault();
    window.showToast('Login berhasil! Selamat datang di F55.', 'success');
    navigateTo('dashboard');
  };

  window.handleFactoryReset = function () {
    if (confirm('Kembalikan semua data ke pengaturan awal F55 Center?')) {
      window.spaStore.resetToFactory();
      window.showToast('Data sistem telah direset ke awal.', 'success');
      navigateTo('admin-dashboard');
    }
  };

  // Initial Route Load
  window.addEventListener('hashchange', renderCurrentRoute);
  renderCurrentRoute();
});
