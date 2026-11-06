<script setup>
defineProps({
    students: { type: Array, required: true },
});
defineEmits(['edit', 'delete']);
</script>

<template>
    <div>
        <h2>Student Records</h2>

        <p v-if="students.length === 0">No students found.</p>

        <table v-else>
            <thead>
                <tr>
                    <th>ID</th><th>Name</th><th>Email</th><th>Course</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="student in students" :key="student.id">
                    <td>{{ student.id }}</td>
                    <td>{{ student.name }}</td>
                    <td>{{ student.email }}</td>
                    <td>{{ student.course }}</td>
                    <td>
                        <button class="btn edit" @click="$emit('edit', student)">Edit</button>
                        <button class="btn delete" @click="$emit('delete', student.id)">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>