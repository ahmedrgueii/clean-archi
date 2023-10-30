<template>
    <div>
        <div class="row col">
            <h1>Login</h1>
        </div>

        <div class="row col">
            <form>
                <div class="form-row">
                    <div class="col-4">
                        <input v-model="email" type="text" class="form-control">
                    </div>
                    <div class="col-4">
                        <input v-model="password" type="password" class="form-control">
                    </div>
                    <div class="col-4">
                        <button :disabled="email.length === 0 || password.length === 0 || isLoading" type="button"
                                class="btn btn-primary" @click="performLogin()"
                        >
                            Login
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div v-if="isLoading" class="row col">
            <p>Loading...</p>
        </div>

        <div v-else-if="hasError" class="row col">
            <div class="alert alert-danger" role="alert">
                {{ error }}
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Login",
    data() {
        return {
            email: "",
            password: ""
        };
    },
    computed: {
        isLoading() {
            return this.$store.getters["security/isLoading"];
        },
        hasError() {
            return this.$store.getters["security/hasError"];
        },
        error() {
            return this.$store.getters["security/error"];
        },
    },
    created() {
        const redirect = this.$route.query.redirect;

        if (this.$store.getters["security/isAuthenticated"]) {
            if (typeof redirect !== "undefined") {
                this.$router.push({path: redirect});
            } else {
                this.$router.push({path: "/home"});
            }
        }
    },
    methods: {
        async performLogin() {
            let payload = {email: this.$data.email, password: this.$data.password},
                redirect = this.$route.query.redirect;

            await this.$store.dispatch("security/login", payload);
            if (!this.$store.getters["security/hasError"]) {
                if (typeof redirect !== "undefined") {
                    this.$router.push({path: redirect});
                } else {
                    this.$router.push({path: "/home"});
                }
            }
        }
    }
}
</script>
