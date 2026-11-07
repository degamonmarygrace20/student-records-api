import { reactive } from 'vue';
import axios from 'axios';

axios.defaults.headers.common['Accept'] = 'application/json';

export const auth = reactive({
    token: localStorage.getItem('token') || '',
    role: localStorage.getItem('role') || '',
    name: localStorage.getItem('name') || '',
});

if (auth.token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${auth.token}`;
}

export function setAuth(token, role = '', name = '') {
    auth.token = token;
    auth.role = role;
    auth.name = name;
    localStorage.setItem('token', token);
    localStorage.setItem('role', role);
    localStorage.setItem('name', name);
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

export function clearAuth() {
    auth.token = '';
    auth.role = '';
    auth.name = '';
    localStorage.removeItem('token');
    localStorage.removeItem('role');
    localStorage.removeItem('name');
    delete axios.defaults.headers.common['Authorization'];
}