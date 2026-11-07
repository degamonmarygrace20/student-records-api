<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { setAuth } from '../auth';

const email = ref('');
const password = ref('');
const loading = ref(false);
const error = ref('');

const login = async () => {
    loading.value = true;
    error.value = '';
    try {
        const res = await axios.post('/api/login', {
            email: email.value,
            password: password.value,
        });

        const token = res.data.token ?? res.data.access_token;
        const user = res.data.user ?? {};
        let role = user.role ?? res.data.role ?? '';
        let name = user.name ?? '';

        setAuth(token, role, name);

        // If the login response has no role, ask /api/me
        if (!role) {
            const me = await axios.get('/api/me');
            const u = me.data.user ?? me.data;
            role = u.role ?? '';
            name = u.name ?? '';
            setAuth(token, role, name);
        }
    } catch (e) {
        if (e.response?.status === 422) {
            error.value = Object.values(e.response.data.errors || {})[0]?.[0] ?? 'Please check your input.';
        } else {
            error.value = e.response?.data?.message ?? 'Login failed. Check your email and password.';
        }
    } finally {
        loading.value = false;
    }
};
</script>

<template>
    <form @submit.prevent="login" class="form">
        <h2>Login</h2>

        <p v-if="error" class="alert error">{{ error }}</p>

        <input v-model="email" type="email" placeholder="Email" />
        <input v-model="password" type="password" placeholder="Password" />

        <button type="submit" class="btn save" :disabled="loading">
            {{ loading ? 'Logging in...' : 'Login' }}
        </button>
    </form>
</template>