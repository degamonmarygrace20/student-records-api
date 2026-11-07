<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import axios from 'axios';
import { auth, clearAuth } from './auth';
import Login from './Components/Login.vue';
import StudentList from './Components/StudentList.vue';
import StudentForm from './Components/StudentForm.vue';

const students = ref([]);
const editing = ref(null);
const errors = ref({});
const message = ref('');
const messageType = ref('success');
const loading = ref(false);
const formKey = ref(0);

const isLoggedIn = computed(() => !!auth.token);
const isAdmin = computed(() => auth.role === 'admin');

const notify = (text, type = 'success') => {
    message.value = text;
    messageType.value = type;
    setTimeout(() => (message.value = ''), 3000);
};

const handleAuthError = (e) => {
    if (e.response?.status === 401) {
        clearAuth();
        notify('Session expired. Please log in again.', 'error');
        return true;
    }
    if (e.response?.status === 403) {
        notify('Only administrators can do this.', 'error');
        return true;
    }
    return false;
};

const fetchStudents = async () => {
    loading.value = true;
    try {
        const res = await axios.get('/api/students');
        students.value = res.data.data ?? res.data;
    } catch (e) {
        if (!handleAuthError(e)) notify('Failed to load students.', 'error');
    } finally {
        loading.value = false;
    }
};

const saveStudent = async (form) => {
    errors.value = {};
    try {
        if (form.id) {
            await axios.put(/api/students/${form.id}, form);
            notify('Student updated successfully!');
        } else {
            await axios.post('/api/students', form);
            notify('Student added successfully!');
        }
        editing.value = null;
        formKey.value++;
        await fetchStudents();
    } catch (e) {
        if (e.response?.status === 422) {
            errors.value = e.response.data.errors;
            notify('Please fix the validation errors.', 'error');
        } else if (!handleAuthError(e)) {
            notify('Something went wrong.', 'error');
        }
    }
};

const deleteStudent = async (id) => {
    if (!confirm('Delete this student?')) return;
    try {
        await axios.delete(/api/students/${id});
        notify('Student deleted successfully!');
        await fetchStudents();
    } catch (e) {
        if (!handleAuthError(e)) notify('Failed to delete student.', 'error');
    }
};

const logout = async () => {
    try {
        await axios.post('/api/logout');
    } catch (e) {
        // ignore, we clear local state anyway
    }
    clearAuth();
    students.value = [];
    editing.value = null;
    notify('Logged out successfully.');
};

watch(isLoggedIn, (loggedIn) => {
    if (loggedIn) fetchStudents();
    else students.value = [];
});

onMounted(() => {
    if (isLoggedIn.value) fetchStudents();
});
</script>

<template>
    <div class="container">
        <h1>Student Management</h1>

        <p v-if="message" :class="['alert', messageType]">{{ message }}</p>

        <Login v-if="!isLoggedIn" />

        <template v-else>
            <div class="topbar">
                <span>
                    {{ auth.name || 'User' }}
                    ({{ isAdmin ? 'Administrator' : 'Regular User' }})
                </span>
                <button class="btn" @click="logout">Logout</button>
            </div>

            <StudentForm
                v-if="isAdmin"
                :key="formKey"
                :student="editing"
                :errors="errors"
                @save="saveStudent"
                @cancel="editing = null"
            />
            <p v-else class="note">You are a Regular User: view-only access.</p>

            <p v-if="loading">Loading students...</p>

            <StudentList
                :students="students"
                :is-admin="isAdmin"
                @edit="editing = $event"
                @delete="deleteStudent"
            />
        </template>
    </div>
</template>