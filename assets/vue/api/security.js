import axios from 'axios';

export default {
    login(email, password) {
        return axios.post('/auth/login', {
            email: email,
            password: password
        });
    }
}
