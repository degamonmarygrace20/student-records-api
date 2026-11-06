<script setup>
import { reactive, watch } from 'vue';

const props = defineProps({
    student: { type: Object, default: null },
    errors: { type: Object, default: () => ({}) },
});
const emit = defineEmits(['save', 'cancel']);

const form = reactive({ id: null, name: '', email: '', course: '' });

const reset = () => Object.assign(form, { id: null, name: '', email: '', course: '' });

watch(() => props.student, (s) => {
    if (s) Object.assign(form, s);
    else reset();
});

const submit = () => {
    emit('save', { ...form });
    if (!form.id) reset();
};
</script>

<template>
    <form @submit.prevent="submit" class="form">
        <h2>{{ form.id ? 'Edit Student' : 'Add Student' }}</h2>

        <input v-model="form.name" placeholder="Name" />
        <small v-if="errors.name" class="error">{{ errors.name[0] }}</small>

        <input v-model="form.email" placeholder="Email" />
        <small v-if="errors.email" class="error">{{ errors.email[0] }}</small>

        <input v-model="form.course" placeholder="Course" />
        <small v-if="errors.course" class="error">{{ errors.course[0] }}</small>

        <div>
            <button type="submit" class="btn save">{{ form.id ? 'Update' : 'Add' }}</button>
            <button v-if="form.id" type="button" class="btn" @click="$emit('cancel')">Cancel</button>
        </div>
    </form>
</template>