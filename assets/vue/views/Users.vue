<template>
    <div>
        <div class="row col">
            <h1>Users</h1>
        </div>

        <div class="row col">
            <form>
                <div class="form-row">
                    <div class="col-8">
                        <input v-model="message" type="text" class="form-control">
                    </div>
                    <div class="col-4">
                        <button :disabled="message.length === 0 || isLoading" type="button"
                                class="btn btn-primary" @click="createUser()"
                        >
                            Create
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

        <div v-else-if="!hasUsers" class="row col">
            No users!
        </div>

        <div v-for="user in users" v-else :key="user.id" class="row col">
            <user :firstName="user.firstName" :lastName="user.lastName" :email="user.email"/>
        </div>
    </div>
</template>

<script>
import User from '../components/User';

export default {
    name: 'Users',
    components: {
        User,
    },
    data() {
        return {
            message: '',
        };
    },
    computed: {
        isLoading() {
            return this.$store.getters["users/isLoading"];
        },
        hasError() {
            return this.$store.getters["users/hasError"];
        },
        error() {
            return this.$store.getters["users/error"];
        },
        hasUsers() {
            return this.$store.getters["users/hasUsers"];
        },
        users() {
            return this.$store.getters["users/users"];
        }
    },
    created() {
        this.$store.dispatch('users/findAll');
    },
    methods: {
        async createUser() {
            const result = await this.$store.dispatch('users/create', this.$data.message);
            if (result !== null) {
                this.$data.message = '';
            }
        }
    }
};
</script>
