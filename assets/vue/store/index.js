import UsersModule from "./users";
import SecurityModule from "./security";
import { createStore } from 'vuex'

const store = createStore({
    modules: {
        users: UsersModule,
        security: SecurityModule,
    }
});

export default store;