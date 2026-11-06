/**
 * F55 Men Relaxation & Massage Center
 * Data Store & Persistent State Management
 */

const STORAGE_KEY = 'F55_SPA_STATE_V3';

const INITIAL_DATA = {
  activeRole: 'admin', // 'admin', 'bos', 'customer'
  currencyMode: 'AED', // 'AED' or 'IDR'
  
  currentUser: {
    id: 'CUST-001',
    name: 'Haikal Ahmad',
    phone: '081234567890',
    email: 'haikal@example.com',
    role: 'customer',
    avatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=250'
  },
  
  adminUser: {
    id: 'ADM-001',
    name: 'Operational Manager',
    phone: '+971 50 123 4567',
    role: 'admin',
    title: 'F55 Center Administrator',
    avatar: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&q=80&w=250'
  },

  bosUser: {
    id: 'BOS-001',
    name: 'Mr. David Hendra',
    phone: '+971 50 999 8888',
    role: 'bos',
    title: 'Executive Owner & Founder',
    avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&q=80&w=250'
  },

  // 10 Official Services from F55 Menu Poster
  services: [
    {
      id: 'SRV-001',
      menuNumber: '01',
      name: 'Balinese Massage',
      category: 'Signature Massage',
      duration: 60,
      priceAed: 150,
      price90Aed: 200,
      price: 375000,
      rating: 5.0,
      reviewsCount: 164,
      status: 'active',
      image: 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?auto=format&fit=crop&q=80&w=800',
      description: 'A traditional massage from Bali that combines gentle to firm pressure, long strokes, and aromatherapy oils to relieve muscle tension, improve blood circulation, and promote deep relaxation.',
      benefits: ['Gentle to firm pressure', 'Long flowing strokes & aromatherapy', 'Relieves chronic muscle tension', 'Promotes deep full-body relaxation'],
      included: ['Foot bath with warm herbal salts', 'Pure organic essential oils', 'Post-treatment hot ginger tea']
    },
    {
      id: 'SRV-002',
      menuNumber: '02',
      name: 'Thai Massage',
      category: 'Therapeutic',
      duration: 60,
      priceAed: 150,
      price90Aed: 200,
      price: 375000,
      rating: 4.9,
      reviewsCount: 118,
      status: 'active',
      image: 'https://images.unsplash.com/photo-1600334089648-b0d9d3028eb2?auto=format&fit=crop&q=80&w=800',
      description: 'An ancient Thai therapy that combines acupressure, stretching, and assisted yoga postures to improve flexibility, relieve muscle tension, and balance energy flow.',
      benefits: ['Ancient passive stretching & yoga postures', 'Balances bodily energy flow (Sen lines)', 'Improves joint flexibility and posture'],
      included: ['Loose cotton massage attire', 'Herbal liniment balms', 'Acupressure stretching sequence']
    },
    {
      id: 'SRV-003',
      menuNumber: '03',
      name: 'Sports Massage',
      category: 'Recovery',
      duration: 60,
      priceAed: 150,
      price90Aed: 200,
      price: 375000,
      rating: 4.9,
      reviewsCount: 142,
      status: 'active',
      image: 'https://images.unsplash.com/photo-1519823551278-64ac92734fb1?auto=format&fit=crop&q=80&w=800',
      description: 'Designed for active individuals and athletes to reduce muscle soreness, prevent injuries, improve athletic performance, and speed up recovery.',
      benefits: ['Reduces lactic acid buildup & muscle knots', 'Prevents sports injuries & sprains', 'Fast recovery for active men'],
      included: ['Deep friction muscle release', 'Cooling menthol recovery balm', 'Electrolyte refreshment']
    },
    {
      id: 'SRV-004',
      menuNumber: '04',
      name: 'Shiatsu Massage',
      category: 'Pressure Point',
      duration: 60,
      priceAed: 150,
      price90Aed: 200,
      price: 375000,
      rating: 4.8,
      reviewsCount: 96,
      status: 'active',
      image: 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&q=80&w=800',
      description: 'A Japanese massage technique using rhythmic finger and palm pressure on specific meridian points to restore energy flow, reduce stress, and promote overall well-being.',
      benefits: ['Acupoint meridian stimulation', 'Relieves tension headaches & mental fatigue', 'Restores natural Qi / Ki energy'],
      included: ['Japanese pressure point sequence', 'Head and neck acupressure', 'Warm green tea']
    },
    {
      id: 'SRV-005',
      menuNumber: '05',
      name: 'Massage with Ice Pool',
      category: 'Cold Therapy',
      duration: 60,
      priceAed: 200,
      price90Aed: 250,
      price: 500000,
      rating: 5.0,
      reviewsCount: 185,
      status: 'active',
      image: 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?auto=format&fit=crop&q=80&w=800',
      description: 'A refreshing contrast therapy that combines therapeutic deep massage with an ice pool immersion to reduce systemic inflammation, relieve muscle fatigue, and rejuvenate the body.',
      benefits: ['Contrast heat and cold vascular pump', 'Massive reduction in inflammation & DOMS', 'Elevates dopamine and mental alertness'],
      included: ['Full body deep massage session', 'Sub-zero ice pool immersion with supervision', 'Thermal towel wrap & hot recovery tea']
    },
    {
      id: 'SRV-006',
      menuNumber: '06',
      name: 'Massage with Cupping',
      category: 'Detoxification',
      duration: 60,
      priceAed: 200,
      price90Aed: 250,
      price: 500000,
      rating: 4.9,
      reviewsCount: 110,
      status: 'active',
      image: 'https://images.unsplash.com/photo-1596178065887-1198b6148b2b?auto=format&fit=crop&q=80&w=800',
      description: 'This therapy uses specialized suction cups to stimulate micro-circulation, release deep myofascial tension, detoxify muscle layers, and accelerate cellular healing.',
      benefits: ['Decompresses tight connective tissue', 'Flushes metabolic waste and toxins', 'Relieves stubborn back and shoulder tightness'],
      included: ['Thermal glass/silicone cupping therapy', 'Soothing arnica oil application', 'Hot towel cleanse']
    },
    {
      id: 'SRV-007',
      menuNumber: '07',
      name: 'Massage with Hot Stone',
      category: 'Thermal Healing',
      duration: 60,
      priceAed: 200,
      price90Aed: 250,
      price: 500000,
      rating: 4.9,
      reviewsCount: 135,
      status: 'active',
      image: 'https://images.unsplash.com/photo-1519823551278-64ac92734fb1?auto=format&fit=crop&q=80&w=800',
      description: 'Warm natural volcanic basalt stones are heated and placed on key energy vortices, then smoothly glided across muscles to melt away chronic knots and induce profound calmness.',
      benefits: ['Penetrating volcanic mineral heat', 'Relaxes rigid muscles without heavy pain', 'Calms nervous system and reduces insomnia'],
      included: ['Heated basalt volcanic stones', 'Warm organic massage balm', 'Spiced herbal wellness beverage']
    },
    {
      id: 'SRV-008',
      menuNumber: '08',
      name: 'Leg Massage',
      category: 'Targeted Relief',
      duration: 30,
      priceAed: 70,
      price60Aed: 100,
      price: 175000,
      rating: 4.8,
      reviewsCount: 88,
      status: 'active',
      image: 'https://images.unsplash.com/photo-1519823551278-64ac92734fb1?auto=format&fit=crop&q=80&w=800',
      description: 'Focuses intensely on the legs, calves, and feet to stimulate lymphatic drainage, relieve heavy swelling, loosen hamstring tension, and restore lightness to every step.',
      benefits: ['Reduces swelling & varicose discomfort', 'Relieves calf cramps & foot fatigue', 'Ideal for travelers, runners & office workers'],
      included: ['Epsom salt foot soak', 'Calf acupressure and reflexology', 'Cooling peppermint massage lotion']
    },
    {
      id: 'SRV-009',
      menuNumber: '09',
      name: 'Back Massage',
      category: 'Targeted Relief',
      duration: 30,
      priceAed: 80,
      price: 200000,
      rating: 4.9,
      reviewsCount: 154,
      status: 'active',
      image: 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?auto=format&fit=crop&q=80&w=800',
      description: 'Targets the upper, mid, and lower back, shoulder blades, and cervical spine to eradicate desk-slump stiffness, relieve pinched sensations, and realign posture.',
      benefits: ['Fast targeted back pain relief', 'Releases trapezii & neck knots', 'Improves breathing and spinal alignment'],
      included: ['Heated back compress', 'Deep spinal trigger point massage', 'Herbal hot compress']
    },
    {
      id: 'SRV-010',
      menuNumber: '10',
      name: 'Only Ice Bath',
      category: 'Cryo Immersion',
      duration: 20,
      priceAed: 50,
      price: 125000,
      rating: 4.9,
      reviewsCount: 204,
      status: 'active',
      image: 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?auto=format&fit=crop&q=80&w=800',
      description: 'Pure sub-zero ice bath immersion designed to supercharge circulation, eliminate systemic soreness, boost brown fat activation, and reset physical endurance.',
      benefits: ['Instant 250% dopamine spike', 'Rapid muscle recovery after workout', 'Vascular conditioning & mental resilience'],
      included: ['Chilled immersion tub (3-5°C)', 'Guided breathing assistance', 'Warm towel & revitalizing citrus water']
    }
  ],

  // Registered Biodata (Nama dan Nomor HP)
  biodata: [
    {
      id: 'BIO-001',
      name: 'Haikal Ahmad',
      phone: '081234567890',
      email: 'haikal@example.com',
      notes: 'Suka Balinese Massage 90m + Ice Bath. Tekanan sedang.',
      visitCount: 12,
      registeredAt: '2026-08-14',
      status: 'VIP Member'
    },
    {
      id: 'BIO-002',
      name: 'Ahmed Al Mansoori',
      phone: '+971501234567',
      email: 'ahmed.dxb@gmail.com',
      notes: 'Sports Massage 60m pasca padel tennis. Reguler tiap Jumat.',
      visitCount: 18,
      registeredAt: '2026-07-20',
      status: 'VIP Member'
    },
    {
      id: 'BIO-003',
      name: 'Siti Rahma',
      phone: '081298112233',
      email: 'rahma@example.com',
      notes: 'Aromatherapy dengan minyak lavender murni.',
      visitCount: 7,
      registeredAt: '2026-09-02',
      status: 'Reguler'
    },
    {
      id: 'BIO-004',
      name: 'Rashid Al Nuaimi',
      phone: '+971559876543',
      email: 'rashid.nuaimi@outlook.com',
      notes: 'Massage with Cupping + Ice Pool contrast recovery.',
      visitCount: 9,
      registeredAt: '2026-09-11',
      status: 'VIP Member'
    },
    {
      id: 'BIO-005',
      name: 'Budi Santoso',
      phone: '081377889900',
      email: 'budi.santoso@corp.id',
      notes: 'Punggung kaku duduk lama di kantor. Back Massage & Cupping.',
      visitCount: 5,
      registeredAt: '2026-09-25',
      status: 'Reguler'
    },
    {
      id: 'BIO-006',
      name: 'Dewi Lestari',
      phone: '085611223344',
      email: 'dewi.lestari@gmail.com',
      notes: 'Massage with Hot Stone hangat untuk bahu pegal.',
      visitCount: 8,
      registeredAt: '2026-09-18',
      status: 'Reguler'
    },
    {
      id: 'BIO-007',
      name: 'Mohammed Tariq',
      phone: '+971523344556',
      email: 'tariq.m@emirates.ae',
      notes: 'Thai Massage 90m & Ice Bath rutin seminggu sekali.',
      visitCount: 14,
      registeredAt: '2026-08-30',
      status: 'VIP Member'
    },
    {
      id: 'BIO-008',
      name: 'Rian Hidayat',
      phone: '087822334455',
      email: 'rian.h@yahoo.com',
      notes: 'Leg Massage pasca lari maraton.',
      visitCount: 4,
      registeredAt: '2026-10-01',
      status: 'Reguler'
    }
  ],

  // Fitur Pengeluaran (Expense Tracking)
  expenses: [
    {
      id: 'EXP-101',
      date: '2026-10-14',
      category: 'Bahan & Minyak Spa',
      description: 'Pasokan Minyak Aromaterapi Murni (Lavender, Lemongrass, Sandalwood)',
      amountAed: 140,
      amountIdr: 350000,
      paymentMethod: 'Transfer Bank',
      recordedBy: 'Admin F55'
    },
    {
      id: 'EXP-102',
      date: '2026-10-14',
      category: 'Ice Bath & Ice Pool',
      description: 'Pasokan Balok Es Kristal Harian untuk Ice Pool & Teraphy Tub',
      amountAed: 60,
      amountIdr: 150000,
      paymentMethod: 'Cash Kasir',
      recordedBy: 'Admin F55'
    },
    {
      id: 'EXP-103',
      date: '2026-10-13',
      category: 'Laundry & Handuk Premium',
      description: 'Laundry Higienis 85 Set Handuk Tebal & Kimono Linen Hitam',
      amountAed: 90,
      amountIdr: 225000,
      paymentMethod: 'Transfer Bank',
      recordedBy: 'Admin F55'
    },
    {
      id: 'EXP-104',
      date: '2026-10-13',
      category: 'Komisi Terapis Harian',
      description: 'Bagi hasil komisi terapis harian Siti Rahayu & Nurul Hidayah',
      amountAed: 180,
      amountIdr: 450000,
      paymentMethod: 'Transfer Bank',
      recordedBy: 'Admin F55'
    },
    {
      id: 'EXP-105',
      date: '2026-10-12',
      category: 'Utilitas & Perawatan Ruang',
      description: 'Listrik, air filtrasi ice pool & sauna heating maintenance',
      amountAed: 150,
      amountIdr: 375000,
      paymentMethod: 'Transfer Bank',
      recordedBy: 'Admin F55'
    },
    {
      id: 'EXP-106',
      date: '2026-10-11',
      category: 'Konsumsi & Minuman Tamu',
      description: 'Seduhan Teh Herbal Asli, Jahe Merah Madu & Air Mineral Spa',
      amountAed: 50,
      amountIdr: 125000,
      paymentMethod: 'Cash Kasir',
      recordedBy: 'Kasir F55'
    },
    {
      id: 'EXP-107',
      date: '2026-10-10',
      category: 'Perlengkapan Cupping & Batu',
      description: 'Penggantian 2 set cup kaca vakum & minyak pelicin batu vulkanik',
      amountAed: 80,
      amountIdr: 200000,
      paymentMethod: 'Transfer Bank',
      recordedBy: 'Admin F55'
    }
  ],

  // Therapists
  therapists: [
    {
      id: 'THP-001',
      name: 'Siti Rahayu',
      specialty: 'Balinese & Aromatherapy Specialist',
      rating: 4.9,
      totalCustomers: 384,
      status: 'ready',
      image: 'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?auto=format&fit=crop&q=80&w=250',
      schedule: '10:00 - 19:00',
      phone: '+971 50 111 2233'
    },
    {
      id: 'THP-002',
      name: 'Nurul Hidayah',
      specialty: 'Thai & Shiatsu Pressure Master',
      rating: 4.8,
      totalCustomers: 312,
      status: 'busy',
      image: 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&q=80&w=250',
      schedule: '10:00 - 19:00',
      phone: '+971 50 222 3344'
    },
    {
      id: 'THP-003',
      name: 'Ratna Kusuma',
      specialty: 'Sports Massage & Cupping Expert',
      rating: 5.0,
      totalCustomers: 425,
      status: 'ready',
      image: 'https://images.unsplash.com/photo-1567532939604-b6b5b0db2604?auto=format&fit=crop&q=80&w=250',
      schedule: '12:00 - 21:00',
      phone: '+971 50 333 4455'
    },
    {
      id: 'THP-004',
      name: 'Tari Utama',
      specialty: 'Ice Pool Contrast & Recovery',
      rating: 4.9,
      totalCustomers: 220,
      status: 'ready',
      image: 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&q=80&w=250',
      schedule: '10:00 - 18:00',
      phone: '+971 50 444 5566'
    },
    {
      id: 'THP-005',
      name: 'Rina Permata',
      specialty: 'Volcanic Hot Stone Therapy',
      rating: 4.8,
      totalCustomers: 195,
      status: 'not_ready',
      image: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&q=80&w=250',
      schedule: '14:00 - 23:00',
      phone: '+971 50 555 6677'
    },
    {
      id: 'THP-006',
      name: 'Dian Sastro',
      specialty: 'Leg & Back Reflexology',
      rating: 4.9,
      totalCustomers: 260,
      status: 'ready',
      image: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=250',
      schedule: '10:00 - 19:00',
      phone: '+971 50 666 7788'
    },
    {
      id: 'THP-007',
      name: 'Yuni Astuti',
      specialty: 'Deep Tissue & Ice Bath',
      rating: 4.9,
      totalCustomers: 340,
      status: 'off',
      image: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&q=80&w=250',
      schedule: 'Off Duty Today',
      phone: '+971 50 777 8899'
    },
    {
      id: 'THP-008',
      name: 'Lestari Putri',
      specialty: 'Balinese Flow & Foot Therapy',
      rating: 4.8,
      totalCustomers: 180,
      status: 'ready',
      image: 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&q=80&w=250',
      schedule: '13:00 - 22:00',
      phone: '+971 50 888 9900'
    }
  ],

  // Bookings with F55 Services
  bookings: [
    {
      id: 'F55-20261014-001',
      customerId: 'BIO-001',
      customerName: 'Haikal Ahmad',
      customerPhone: '081234567890',
      serviceId: 'SRV-001',
      serviceName: 'Balinese Massage (60 Min)',
      therapistId: 'THP-001',
      therapistName: 'Siti Rahayu',
      date: '2026-10-14',
      time: '14:00',
      duration: 60,
      priceAed: 150,
      price: 375000,
      status: 'in_progress',
      paymentMethod: 'Credit Card (Visa)',
      notes: 'Customer ingin fokus di area leher dan punggung atas.',
      createdAt: '2026-10-14T08:30:00Z'
    },
    {
      id: 'F55-20261014-002',
      customerId: 'BIO-002',
      customerName: 'Ahmed Al Mansoori',
      customerPhone: '+971501234567',
      serviceId: 'SRV-005',
      serviceName: 'Massage with Ice Pool (60 Min)',
      therapistId: 'THP-002',
      therapistName: 'Nurul Hidayah',
      date: '2026-10-14',
      time: '14:15',
      duration: 60,
      priceAed: 200,
      price: 500000,
      status: 'in_progress',
      paymentMethod: 'Apple Pay',
      notes: 'Immersion ice pool 5 menit di akhir sesi.',
      createdAt: '2026-10-14T09:00:00Z'
    },
    {
      id: 'F55-20261014-003',
      customerId: 'BIO-004',
      customerName: 'Rashid Al Nuaimi',
      customerPhone: '+971559876543',
      serviceId: 'SRV-006',
      serviceName: 'Massage with Cupping (60 Min)',
      therapistId: 'THP-003',
      therapistName: 'Ratna Kusuma',
      date: '2026-10-14',
      time: '15:30',
      duration: 60,
      priceAed: 200,
      price: 500000,
      status: 'confirmed',
      paymentMethod: 'Cash',
      notes: 'Cupping area belikat dan pinggang.',
      createdAt: '2026-10-14T10:15:00Z'
    },
    {
      id: 'F55-20261014-004',
      customerId: 'BIO-005',
      customerName: 'Budi Santoso',
      customerPhone: '081377889900',
      serviceId: 'SRV-002',
      serviceName: 'Thai Massage (90 Min)',
      therapistId: 'THP-004',
      therapistName: 'Tari Utama',
      date: '2026-10-14',
      time: '16:00',
      duration: 90,
      priceAed: 200,
      price: 500000,
      status: 'confirmed',
      paymentMethod: 'Transfer Bank',
      notes: '',
      createdAt: '2026-10-14T11:00:00Z'
    },
    {
      id: 'F55-20261014-005',
      customerId: 'BIO-007',
      customerName: 'Mohammed Tariq',
      customerPhone: '+971523344556',
      serviceId: 'SRV-003',
      serviceName: 'Sports Massage (60 Min)',
      therapistId: 'THP-006',
      therapistName: 'Dian Sastro',
      date: '2026-10-14',
      time: '10:00',
      duration: 60,
      priceAed: 150,
      price: 375000,
      status: 'completed',
      paymentMethod: 'Card',
      notes: 'Selesai tepat waktu. Puas.',
      createdAt: '2026-10-14T07:00:00Z'
    },
    {
      id: 'F55-20261014-006',
      customerId: 'BIO-006',
      customerName: 'Dewi Lestari',
      customerPhone: '085611223344',
      serviceId: 'SRV-007',
      serviceName: 'Massage with Hot Stone (60 Min)',
      therapistId: 'THP-008',
      therapistName: 'Lestari Putri',
      date: '2026-10-14',
      time: '11:30',
      duration: 60,
      priceAed: 200,
      price: 500000,
      status: 'completed',
      paymentMethod: 'QRIS',
      notes: '',
      createdAt: '2026-10-14T08:00:00Z'
    },
    {
      id: 'F55-20261014-007',
      customerId: 'BIO-008',
      customerName: 'Rian Hidayat',
      customerPhone: '087822334455',
      serviceId: 'SRV-010',
      serviceName: 'Only Ice Bath (20 Min)',
      therapistId: 'THP-004',
      therapistName: 'Tari Utama',
      date: '2026-10-14',
      time: '12:30',
      duration: 20,
      priceAed: 50,
      price: 125000,
      status: 'completed',
      paymentMethod: 'Cash',
      notes: '',
      createdAt: '2026-10-14T09:30:00Z'
    }
  ],

  // Active Live Sessions
  activeSessions: [
    {
      id: 'SES-001',
      bookingId: 'F55-20261014-001',
      customerName: 'Haikal Ahmad',
      serviceName: 'Balinese Massage (60 Min)',
      therapistName: 'Siti Rahayu',
      startTime: '14:00',
      totalDurationMinutes: 60,
      remainingSeconds: 2450, // ~40 mins
      isPaused: false,
      status: 'running'
    },
    {
      id: 'SES-002',
      bookingId: 'F55-20261014-002',
      customerName: 'Ahmed Al Mansoori',
      serviceName: 'Massage with Ice Pool (60 Min)',
      therapistName: 'Nurul Hidayah',
      startTime: '14:15',
      totalDurationMinutes: 60,
      remainingSeconds: 480, // ~8 mins (warning glow)
      isPaused: false,
      status: 'running'
    }
  ],

  notifications: [
    { id: 1, title: 'Booking Masuk 🟡', text: 'Booking baru dari Ahmed Al Mansoori (Ice Pool Massage)', time: '15 menit lalu', read: false },
    { id: 2, title: 'Sesi Dimulai 🟢', text: 'Therapist Siti Rahayu memulai Balinese Massage', time: '35 menit lalu', read: false },
    { id: 3, title: 'Pengeluaran Dicatat 💸', text: 'Pengeluaran 140 AED untuk Minyak Aromaterapi dicatat.', time: '1 jam lalu', read: true }
  ]
};

class DataStore {
  constructor() {
    this.init();
  }

  init() {
    const saved = localStorage.getItem(STORAGE_KEY);
    if (!saved) {
      this.state = JSON.parse(JSON.stringify(INITIAL_DATA));
      this.save();
    } else {
      try {
        this.state = JSON.parse(saved);
        // Ensure new structures exist
        if (!this.state.expenses) this.state.expenses = JSON.parse(JSON.stringify(INITIAL_DATA.expenses));
        if (!this.state.biodata) this.state.biodata = JSON.parse(JSON.stringify(INITIAL_DATA.biodata));
        if (!this.state.bosUser) this.state.bosUser = JSON.parse(JSON.stringify(INITIAL_DATA.bosUser));
        if (!this.state.activeRole) this.state.activeRole = 'admin';
        if (!this.state.currencyMode) this.state.currencyMode = 'AED';
      } catch (e) {
        console.error('Failed to parse saved state, resetting to initial.', e);
        this.state = JSON.parse(JSON.stringify(INITIAL_DATA));
        this.save();
      }
    }
  }

  save() {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(this.state));
    window.dispatchEvent(new CustomEvent('spa-data-updated'));
  }

  resetToFactory() {
    this.state = JSON.parse(JSON.stringify(INITIAL_DATA));
    this.save();
  }

  // --- ROLE MANAGEMENT ---
  getActiveRole() {
    return this.state.activeRole || 'admin';
  }

  setActiveRole(role) {
    this.state.activeRole = role;
    this.save();
  }

  // --- CURRENCY TOGGLE & FORMATTERS ---
  getCurrencyMode() {
    return this.state.currencyMode || 'AED';
  }

  toggleCurrencyMode() {
    this.state.currencyMode = this.state.currencyMode === 'AED' ? 'IDR' : 'AED';
    this.save();
    return this.state.currencyMode;
  }

  formatPrice(priceAed, priceIdr = null) {
    if (this.state.currencyMode === 'IDR') {
      const idr = priceIdr || (priceAed * 4200);
      return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(idr);
    }
    return `${priceAed} AED`;
  }

  formatRupiah(amount) {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
  }

  // --- SERVICES ---
  getServices() {
    return this.state.services;
  }

  getServiceById(id) {
    return this.state.services.find(s => s.id === id);
  }

  addService(serviceData) {
    const newId = 'SRV-' + String(this.state.services.length + 1).padStart(3, '0');
    const newService = {
      id: newId,
      menuNumber: String(this.state.services.length + 1).padStart(2, '0'),
      rating: 5.0,
      reviewsCount: 1,
      status: 'active',
      image: serviceData.image || 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?auto=format&fit=crop&q=80&w=800',
      benefits: ['Premium relaxation', 'Improves vitality and stamina', 'Luxury F55 standard'],
      included: ['Herbal welcome tea', 'Full relaxation treatment'],
      priceAed: Number(serviceData.priceAed) || 150,
      price: (Number(serviceData.priceAed) || 150) * 2500,
      ...serviceData
    };
    this.state.services.push(newService);
    this.save();
    return newService;
  }

  updateService(id, updatedData) {
    const idx = this.state.services.findIndex(s => s.id === id);
    if (idx !== -1) {
      this.state.services[idx] = { ...this.state.services[idx], ...updatedData };
      this.save();
      return this.state.services[idx];
    }
    return null;
  }

  deleteService(id) {
    this.state.services = this.state.services.filter(s => s.id !== id);
    this.save();
  }

  // --- BIODATA (Nama dan Nomor HP) ---
  getBiodata() {
    return this.state.biodata || [];
  }

  getBiodataById(id) {
    return (this.state.biodata || []).find(b => b.id === id);
  }

  addBiodata(data) {
    const newId = 'BIO-' + String((this.state.biodata || []).length + 1).padStart(3, '0');
    const newEntry = {
      id: newId,
      name: data.name,
      phone: data.phone,
      email: data.email || `${data.name.toLowerCase().replace(/\s+/g, '')}@example.com`,
      notes: data.notes || 'Pelanggan baru F55 Relaxation Center',
      visitCount: data.visitCount || 1,
      registeredAt: data.registeredAt || new Date().toISOString().split('T')[0],
      status: data.status || 'Reguler'
    };
    if (!this.state.biodata) this.state.biodata = [];
    this.state.biodata.unshift(newEntry);
    this.addNotification('Biodata Baru 📋', `Biodata ${newEntry.name} (${newEntry.phone}) berhasil disimpan.`);
    this.save();
    return newEntry;
  }

  updateBiodata(id, data) {
    const idx = (this.state.biodata || []).findIndex(b => b.id === id);
    if (idx !== -1) {
      this.state.biodata[idx] = { ...this.state.biodata[idx], ...data };
      this.save();
      return this.state.biodata[idx];
    }
    return null;
  }

  deleteBiodata(id) {
    this.state.biodata = (this.state.biodata || []).filter(b => b.id !== id);
    this.save();
  }

  // --- FITUR PENGELUARAN (Expenses) ---
  getExpenses() {
    return this.state.expenses || [];
  }

  addExpense(data) {
    const newId = 'EXP-' + String((this.state.expenses || []).length + 101);
    const amountAed = Number(data.amountAed) || (Number(data.amountIdr) ? Math.round(Number(data.amountIdr) / 2500) : 50);
    const amountIdr = Number(data.amountIdr) || (amountAed * 2500);

    const newExpense = {
      id: newId,
      date: data.date || new Date().toISOString().split('T')[0],
      category: data.category || 'Operasional',
      description: data.description,
      amountAed: amountAed,
      amountIdr: amountIdr,
      paymentMethod: data.paymentMethod || 'Transfer Bank',
      recordedBy: data.recordedBy || 'Admin'
    };
    if (!this.state.expenses) this.state.expenses = [];
    this.state.expenses.unshift(newExpense);
    this.addNotification('Pengeluaran Dicatat 💸', `${this.formatPrice(newExpense.amountAed, newExpense.amountIdr)} untuk ${newExpense.category}`);
    this.save();
    return newExpense;
  }

  deleteExpense(id) {
    this.state.expenses = (this.state.expenses || []).filter(e => e.id !== id);
    this.save();
  }

  // --- BOOKKEEPING & FINANCIAL SUMMARY ---
  getFinancialSummary() {
    const bookings = this.getBookings().filter(b => b.status !== 'cancelled');
    const totalRevenueAed = bookings.reduce((sum, b) => sum + (b.priceAed || 150), 0) + 1250; // Base baseline + today
    const totalRevenueIdr = totalRevenueAed * 2500;

    const expenses = this.getExpenses();
    const totalExpensesAed = expenses.reduce((sum, e) => sum + (e.amountAed || 0), 0);
    const totalExpensesIdr = totalExpensesAed * 2500;

    const netProfitAed = totalRevenueAed - totalExpensesAed;
    const netProfitIdr = totalRevenueIdr - totalExpensesIdr;
    const profitMargin = totalRevenueAed > 0 ? Math.round((netProfitAed / totalRevenueAed) * 100) : 0;

    // Expenses category breakdown
    const categoryTotals = {};
    expenses.forEach(e => {
      categoryTotals[e.category] = (categoryTotals[e.category] || 0) + e.amountAed;
    });

    return {
      totalRevenueAed,
      totalRevenueIdr,
      totalExpensesAed,
      totalExpensesIdr,
      netProfitAed,
      netProfitIdr,
      profitMargin,
      totalBookingsCount: bookings.length + 18,
      categoryTotals
    };
  }

  // --- THERAPISTS ---
  getTherapists() {
    return this.state.therapists;
  }

  getTherapistById(id) {
    return this.state.therapists.find(t => t.id === id);
  }

  updateTherapistStatus(id, newStatus) {
    const therapist = this.getTherapistById(id);
    if (therapist) {
      therapist.status = newStatus;
      this.save();
      return therapist;
    }
    return null;
  }

  // --- BOOKINGS ---
  getBookings() {
    return this.state.bookings;
  }

  getBookingById(id) {
    return this.state.bookings.find(b => b.id === id);
  }

  addBooking(bookingData) {
    const dateStr = new Date().toISOString().slice(0, 10).replace(/-/g, '');
    const newId = `F55-${dateStr}-${String(this.state.bookings.length + 1).padStart(3, '0')}`;
    const newBooking = {
      id: newId,
      status: 'confirmed',
      createdAt: new Date().toISOString(),
      ...bookingData
    };
    this.state.bookings.unshift(newBooking);

    // Auto add to biodata if phone not yet registered
    if (bookingData.customerName && bookingData.customerPhone) {
      const exists = (this.state.biodata || []).find(b => b.phone === bookingData.customerPhone);
      if (!exists) {
        this.addBiodata({
          name: bookingData.customerName,
          phone: bookingData.customerPhone,
          notes: `Booking pertama: ${bookingData.serviceName}`
        });
      }
    }

    this.addNotification('Booking Baru Dibuat 📅', `Reservasi ${newBooking.id} (${newBooking.customerName}) telah terkonfirmasi.`);
    this.save();
    return newBooking;
  }

  updateBookingStatus(id, newStatus) {
    const booking = this.getBookingById(id);
    if (booking) {
      booking.status = newStatus;
      if (newStatus === 'completed') {
        const therapist = this.state.therapists.find(t => t.name === booking.therapistName);
        if (therapist) therapist.status = 'ready';
      }
      this.save();
      return booking;
    }
    return null;
  }

  // --- ACTIVE SESSIONS & TIMERS ---
  getActiveSessions() {
    return this.state.activeSessions;
  }

  startSession(bookingId) {
    const booking = this.getBookingById(bookingId);
    if (!booking) return null;

    const existingSession = this.state.activeSessions.find(s => s.bookingId === bookingId);
    if (existingSession) return existingSession;

    const newSession = {
      id: 'SES-' + Date.now().toString().slice(-4),
      bookingId: booking.id,
      customerName: booking.customerName,
      serviceName: booking.serviceName,
      therapistName: booking.therapistName,
      startTime: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
      totalDurationMinutes: booking.duration,
      remainingSeconds: booking.duration * 60,
      isPaused: false,
      status: 'running'
    };

    booking.status = 'in_progress';
    const therapist = this.state.therapists.find(t => t.name === booking.therapistName);
    if (therapist) therapist.status = 'busy';

    this.state.activeSessions.push(newSession);
    this.addNotification('Sesi Dimulai 🟢', `Sesi spa ${newSession.customerName} telah dimulai.`);
    this.save();
    return newSession;
  }

  pauseSession(sessionId) {
    const session = this.state.activeSessions.find(s => s.id === sessionId);
    if (session) {
      session.isPaused = true;
      session.status = 'paused';
      this.save();
    }
  }

  resumeSession(sessionId) {
    const session = this.state.activeSessions.find(s => s.id === sessionId);
    if (session) {
      session.isPaused = false;
      session.status = 'running';
      this.save();
    }
  }

  finishSession(sessionId) {
    const sessionIdx = this.state.activeSessions.findIndex(s => s.id === sessionId);
    if (sessionIdx !== -1) {
      const session = this.state.activeSessions[sessionIdx];
      const booking = this.getBookingById(session.bookingId);
      if (booking) {
        booking.status = 'completed';
        const therapist = this.state.therapists.find(t => t.name === booking.therapistName);
        if (therapist) therapist.status = 'ready';
      }
      this.state.activeSessions.splice(sessionIdx, 1);
      this.addNotification('Sesi Selesai 🔴', `Perawatan spa untuk ${session.customerName} telah selesai.`);
      this.save();
    }
  }

  addTimeSession(sessionId, minutesToAdd) {
    const session = this.state.activeSessions.find(s => s.id === sessionId);
    if (session) {
      session.remainingSeconds += minutesToAdd * 60;
      session.totalDurationMinutes += minutesToAdd;
      this.addNotification('Waktu Ditambahkan ⏳', `Ditambahkan +${minutesToAdd} menit untuk ${session.customerName}.`);
      this.save();
    }
  }

  // --- NOTIFICATIONS ---
  getNotifications() {
    return this.state.notifications;
  }

  addNotification(title, text) {
    const newNotif = {
      id: Date.now(),
      title,
      text,
      time: 'Baru saja',
      read: false
    };
    this.state.notifications.unshift(newNotif);
  }

  markNotificationsRead() {
    this.state.notifications.forEach(n => n.read = true);
    this.save();
  }

  // --- DASHBOARD STATS ---
  getDashboardStats() {
    const summary = this.getFinancialSummary();
    const activeSessionsCount = this.state.activeSessions.filter(s => s.status === 'running' || s.status === 'paused').length;
    const availableTherapists = this.state.therapists.filter(t => t.status === 'ready').length;
    const totalTherapists = this.state.therapists.length;

    return {
      todayRevenueAed: 1350,
      todayRevenueIdr: 3375000,
      todayExpensesAed: 200,
      todayExpensesIdr: 500000,
      todayBookingsCount: 24,
      activeSessionsCount,
      customersToday: 19,
      availableTherapists,
      totalTherapists,
      financialSummary: summary
    };
  }
}

// Global Store Instance
window.spaStore = new DataStore();
