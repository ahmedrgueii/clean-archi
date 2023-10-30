<template>
    <div className="container">
        <nav className="navbar navbar-expand-lg navbar-light bg-light">
            <button
                    className="navbar-toggler"
                    type="button"
                    data-toggle="collapse"
                    data-target="#navbarNav"
                    aria-controls="navbarNav"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
            >
                <span className="navbar-toggler-icon"/>
            </button>
            <div
                    id="navbarNav"
                    className="collapse navbar-collapse"
            >
                <ul className="navbar-nav">
                    <router-link className="nav-item" tag="li" to="/home" active-class="active">
                        <a className="nav-link">Home</a>
                    </router-link>
                    <router-link className="nav-item" tag="li" to="/users">
                        <a className="nav-link">Users</a>
                    </router-link>
                    <li v-if="isAuthenticated" class="nav-item">
                        <a class="nav-link" href="#" @click="logout()">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>

        <router-view/>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'App',
    computed: {
        isAuthenticated() {
            return this.$store.getters['security/isAuthenticated']
        },
    },
    created() {
        axios.interceptors.response.use(undefined, (err) => {
            return new Promise(() => {
                if (err.response.status === 401) {
                    this.$router.push({path: '/login'})
                }
                throw err;
            });
        });
    },
    methods: {
        logout() {
            this.$store.dispatch("security/logout");
            this.$router.push({path: '/home'});
        }
    },
}
</script>
