<template>
    <Head title="Assign Subjects to Semester" />

    <AuthenticatedLayout>
        <div class="align-middle min-w-full p-2 bg-gray-50 overflow-x-auto shadow overflow-hidden sm:rounded-lg">
            <div class="rounded-lg bg-white overflow-hidden shadow p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-6">
                    Assign Subjects to Sub Grade Semester
                </h3>

                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Sub Grade Selection -->
                    <div>
                        <label for="sub_grade_id" class="block text-sm font-medium text-gray-700">
                            Sub Grade <span class="text-red-500">*</span>
                        </label>
                        <select v-model="form.sub_grade_id" id="sub_grade_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Select Sub Grade...</option>
                            <option v-for="subGrade in subGrades" :key="subGrade.id" :value="subGrade.id">
                                {{ subGrade.full_name }}
                            </option>
                        </select>
                        <p v-if="errors.sub_grade_id" class="mt-2 text-sm text-red-500">
                            {{ errors.sub_grade_id }}
                        </p>
                    </div>

                    <!-- Semester Selection -->
                    <div>
                        <label for="semester" class="block text-sm font-medium text-gray-700">
                            Semester <span class="text-red-500">*</span>
                        </label>
                        <select v-model="form.semester" id="semester" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Select Semester...</option>
                            <option value="1">First Semester</option>
                            <option value="2">Second Semester</option>
                        </select>
                        <p v-if="errors.semester" class="mt-2 text-sm text-red-500">
                            {{ errors.semester }}
                        </p>
                    </div>

                    <!-- Year Selection -->
                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700">
                            Year <span class="text-red-500">*</span>
                        </label>
                        <select v-model="form.year" id="year" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Select Year...</option>
                            <option v-for="year in years" :key="year.id" :value="year.name">
                                {{ year.name }}
                            </option>
                        </select>
                        <p v-if="errors.year" class="mt-2 text-sm text-red-500">
                            {{ errors.year }}
                        </p>
                    </div>

                    <!-- Subjects Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Subjects <span class="text-red-500">*</span>
                        </label>
                        <div class="border border-gray-300 rounded-md p-4 max-h-96 overflow-y-auto">
                            <div v-for="subject in subjects" :key="subject.id" class="flex items-center mb-2">
                                <input type="checkbox" :value="subject.id" v-model="form.subject_ids"
                                    :id="`subject-${subject.id}`"
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label :for="`subject-${subject.id}`" class="ml-2 text-sm text-gray-700">
                                    {{ subject.name }} ({{ subject.en_name }})
                                </label>
                            </div>
                        </div>
                        <p v-if="errors.subject_ids" class="mt-2 text-sm text-red-500">
                            {{ errors.subject_ids }}
                        </p>
                        <p class="mt-2 text-sm text-gray-500">
                            Selected: {{ form.subject_ids.length }} subject(s)
                        </p>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-end space-x-3">
                        <Link href="/sub-grade-subject-semesters"
                            class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </Link>
                        <button type="submit" :disabled="isSubmitting"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            :class="isSubmitting ? 'bg-indigo-200 hover:bg-indigo-200' : 'bg-indigo-600 hover:bg-indigo-700'">
                            <span v-if="isSubmitting">Saving...</span>
                            <span v-else>Save Assignment</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { router } from '@inertiajs/vue3';
import { Head, Link } from '@inertiajs/vue3';
import { reactive, ref } from 'vue';
import Swal from 'sweetalert2';

const props = defineProps(['subGrades', 'subjects', 'years', 'errors']);

const isSubmitting = ref(false);

const form = reactive({
    sub_grade_id: '',
    semester: '',
    year: '',
    subject_ids: [],
});

function submit() {
    isSubmitting.value = true;
    router.post('/sub-grade-subject-semesters', form, {
        onFinish: () => {
            isSubmitting.value = false;
            if (!props.errors.sub_grade_id && !props.errors.semester && !props.errors.subject_ids) {
                Swal.fire('Success', 'Subjects assigned successfully!', 'success');
                router.visit('/sub-grade-subject-semesters');
            }
        },
    });
}
</script>

