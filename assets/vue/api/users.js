import axios from 'axios';

export default {
    create(message) {
        return axios.post('/users', { message: message }, {
            headers: { 'Authorization': localStorage.getItem('_token') },
        });
    },
    findAll() {
        return axios.get('users?page=1', {
            headers: { 'Authorization': localStorage.getItem('_token') },
        });
    }
};
