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
                    <th>ID</th><th>First Name</th><th>Last Name</th>
                    <th>Email</th><th>Course</th><th>Year</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="student in students" :key="student.id">
                    <td>{{ student.id }}</td>
                    <td>{{ student.first_name }}</td>
                    <td>{{ student.last_name }}</td>
                    <td>{{ student.email }}</td>
                    <td>{{ student.course }}</td>
                    <td>{{ student.year_level }}</td>
                    <td>
                        <button class="btn edit" @click="$emit('edit', student)">Edit</button>
                        <button class="btn delete" @click="$emit('delete', student.id)">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>