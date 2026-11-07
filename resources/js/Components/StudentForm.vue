<script setup>
import { reactive, watch } from 'vue';

const props = defineProps({
    student: { type: Object, default: null },
    errors: { type: Object, default: () => ({}) },
});
defineEmits(['save', 'cancel']);

const empty = { id: null, first_name: '', last_name: '', email: '', course: '', year_level: '' };
const form = reactive({ ...empty, ...(props.student || {}) });

watch(() => props.student, (s) => Object.assign(form, s ? s : empty));
</script>

<template>
    <form @submit.prevent="$emit('save', { ...form })" class="form">
        <h2>{{ form.id ? 'Edit Student' : 'Add Student' }}</h2>

        <input v-model="form.first_name" placeholder="First Name" />
        <small v-if="errors.first_name" class="error">{{ errors.first_name[0] }}</small>

        <input v-model="form.last_name" placeholder="Last Name" />
        <small v-if="errors.last_name" class="error">{{ errors.last_name[0] }}</small>

        <input v-model="form.email" placeholder="Email" />
        <small v-if="errors.email" class="error">{{ errors.email[0] }}</small>

        <input v-model="form.course" placeholder="Course" />
        <small v-if="errors.course" class="error">{{ errors.course[0] }}</small>

        <input v-model="form.year_level" type="er" placeholder="Year Level (1-6)" />
        <small v-if="errors.year_level" class="error">{{ errors.year_level[0] }}</small>

        <div>
            <button type="submit" class="btn save">{{ form.id ? 'Update' : 'Add' }}</button>
            <button v-if="form.id" type="button" class="btn" @click="$emit('cancel')">Cancel</button>
        </div>
    </form>
</template>