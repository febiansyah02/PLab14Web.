const { createApp } = Vue;
const { createRouter, createWebHashHistory } = VueRouter;

// === IMPLEMENTASI AXIOS INTERCEPTORS (Penyuntik Token Otomatis) ===
axios.interceptors.request.use(
    (config) => {
        // Ambil token dari local storage browser
        const token = localStorage.getItem('userToken');

        // Jika token tersedia, masukkan ke dalam HTTP Header Authorization Bearer
        if (token) {
            config.headers['Authorization'] = 'Bearer ' + token;
        }

        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Tangkap secara global jika server merespon dengan error 401 (Unauthorized)
axios.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        if (error.response && error.response.status === 401) {
            alert('Sesi Anda telah berakhir atau Token tidak sah. Silakan login kembali.');
            localStorage.clear();
            window.location.href = '#/login';
            window.location.reload();
        }

        return Promise.reject(error);
    }
);

// 1. Definisikan mapping rute URL ke Komponen
const routes = [
    { path: '/', component: Home },
    { path: '/login', component: Login },
    {
        path: '/artikel',
        component: Artikel,
        meta: { requiresAuth: true }
    },
    {
        path: '/about',
        component: About,
        meta: { requiresAuth: true }
    }
];

const router = createRouter({
    history: createWebHashHistory(),
    routes
});

// 2. Navigation Guards (Client-Side Security)
router.beforeEach((to, from, next) => {
    const isAuthenticated = localStorage.getItem('isLoggedIn') === 'true';

    if (
        to.matched.some(record => record.meta.requiresAuth) &&
        !isAuthenticated
    ) {
        alert('Akses Ditolak! Anda harus login terlebih dahulu.');
        next('/login');
    } else {
        next();
    }
});

// 3. Inisialisasi Root Instance VueJS Application
const app = createApp({
    data() {
        return {
            isLoggedIn: false
        };
    },

    mounted() {
        this.isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';
    },

    methods: {
        logout() {
            if (confirm('Apakah Anda yakin ingin keluar aplikasi?')) {
                localStorage.clear();
                this.isLoggedIn = false;
                this.$router.push('/');
            }
        }
    }
});

app.use(router);
app.mount('#app');