import UsersApi from '../api/users';

const CREATING_USER = "CREATING_USER",
    CREATING_USER_SUCCESS = "CREATING_USER_SUCCESS",
    CREATING_USER_ERROR = "CREATING_USER_ERROR",
    FETCHING_USERS = "FETCHING_USERS",
    FETCHING_USERS_SUCCESS = "FETCHING_USERS_SUCCESS",
    FETCHING_USERS_ERROR = "FETCHING_USERS_ERROR";

export default {
    namespaced: true,
    state: {
        isLoading: false,
        error: null,
        users: []
    },
    getters: {
        isLoading(state) {
            return state.isLoading;
        },
        hasError(state) {
            return state.error !== null;
        },
        error(state) {
            return state.error;
        },
        hasUsers(state) {
            return state.users.length > 0;
        },
        users(state) {
            return state.users;
        }
    },
    mutations: {
        [CREATING_USER](state) {
            state.isLoading = true;
            state.error = null;
        },
        [CREATING_USER_SUCCESS](state, user) {
            state.isLoading = false;
            state.error = null;
            state.users.unshift(user);
        },
        [CREATING_USER_ERROR](state, error) {
            state.isLoading = false;
            state.error = error;
            state.users = [];
        },
        [FETCHING_USERS](state) {
            state.isLoading = true;
            state.error = null;
            state.users = [];
        },
        [FETCHING_USERS_SUCCESS](state, users) {
            state.isLoading = false;
            state.error = null;
            state.users = users;
        },
        [FETCHING_USERS_ERROR](state, error) {
            state.isLoading = false;
            state.error = error;
            state.users = [];
        }
    },
    actions: {
        async create({ commit }, message) {
            commit(CREATING_USER);
            try {
                let response = await UsersApi.create(message);
                commit(CREATING_USER_SUCCESS, response.data);
                return response.data;
            } catch (error) {
                commit(CREATING_USER_ERROR, error);
                return null;
            }
        },
        async findAll({ commit }) {
            commit(FETCHING_USERS);
            try {
                let response = await UsersApi.findAll();
                commit(FETCHING_USERS_SUCCESS, response.data['hydra:member']);
                return response.data;
            } catch (error) {
                commit(FETCHING_USERS_ERROR, error);
                return null;
            }
        }
    }
};
