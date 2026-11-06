<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import StudentList from './Components/StudentList.vue';
import StudentForm from './Components/StudentForm.vue';

const students = ref([]);
const editing = ref(null);       // student being edited, or null
const errors = ref({});
const message = ref('');
const messageType = ref('success');

const notify = (text, type = 'success') => {
    message.value = text;
    messageType.value = type;
    setTimeout(() => (message.value = ''), 3000);
};

const fetchStudents = async () => {
    try {
        const res = await axios.get('/api/students');
        students.value = res.data.data ?? res.data; // handles Resource or plain array
    } catch (e) {
        notify('Failed to load students.', 'error');
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
        await fetchStudents();
    } catch (e) {
        if (e.response?.status === 422) {
            errors.value = e.response.data.errors;
            notify('Please fix the validation errors.', 'error');
        } else {
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
        notify('Failed to delete student.', 'error');
    }
};

onMounted(fetchStudents);
</script>

<template>
    <div class="container">
        <h1>Student Management</h1>

        <p v-if="message" :class="['alert', messageType]">{{ message }}</p>

        <StudentForm
            :student="editing"
            :errors="errors"
            @save="saveStudent"
            @cancel="editing = null"
        />

        <StudentList
            :students="students"
            @edit="editing = $event"
            @delete="deleteStudent"
        />
    </div>
</template>