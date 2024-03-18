<template>

    <Head title="Create Student" />
    <AuthenticatedLayout>
        <div class="py-4 bg-sky-30 rounded-lg border">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class=" overflow-hidden shadow-sm sm:rounded-lg">

                    <!-- Create Student -->
                    <form @submit.prevent="submit">
                        <div class="space-y-12">

                            <div class="border-b border-gray-900/10 pb-12">
                                <h2 class="text-base font-semibold leading-7 text-gray-900">Create Score</h2>
                                <p class="mt-1 text-sm leading-6 text-gray-600">You can create student's scores.
                                </p>

                                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                                    <!-- Grade -->
                                    <div class="sm:col-span-1">
                                        <label for="grade_id"
                                            class="block text-sm font-medium leading-6 text-gray-900">Grade
                                        </label>
                                        <div class="mt-2">
                                            <select v-model="form.grade_id" @change="getStudents()" name="grade" id="grade"
                                                autocomplete="grade"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                <option value="">...</option>
                                                <option v-for="grade in grades" :value="grade.id"
                                                    :key="grade">{{ grade . full_name }}</option>
                                            </select>
                                            <p class="mt-2 text-sm text-red-500" v-if="errors.grade_id">
                                                {{ errors . grade_id }}</p>
                                        </div>
                                    </div>

                                    <div class="sm:col-span-1">
                                        <label for="subject_id"
                                            class="block text-sm font-medium leading-6 text-gray-900">Subject
                                        </label>
                                        <div class="mt-2">
                                            <select v-model="form.subject_id" @change="getStudents()" name="subject" id="subject"
                                                autocomplete="subject"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                <option value="">...</option>
                                                <option v-for="subject in subjects" :value="subject.id"
                                                    :key="subject">{{ subject . name }}</option>
                                            </select>
                                            <p class="mt-2 text-sm text-red-500" v-if="errors.subject_id">
                                                {{ errors . subject_id }}</p>
                                        </div>
                                    </div>

                                    <div class="sm:col-span-1">
                                        <label for="grade_id"
                                            class="block text-sm font-medium leading-6 text-gray-900">Teacher
                                        </label>
                                        <div class="mt-2">
                                            <select v-model="form.teacher_id" name="teacher" id="teacher"
                                                autocomplete="teacher"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                <option value="">...</option>
                                                <option v-for="teacher in teachers" :value="teacher.id"
                                                    :key="teacher">{{ teacher . name }}</option>
                                            </select>
                                            <p class="mt-2 text-sm text-red-500" v-if="errors.teacher_id">
                                                {{ errors . teacher_id }}</p>
                                        </div>
                                    </div>

                                    <!-- Year -->
                                    <div class="sm:col-span-1">
                                        <label for="year"
                                            class="block text-sm font-medium leading-6 text-gray-900">Year
                                        </label>
                                        <div class="mt-2">
                                            <select v-model="form.year"  @change="getStudents()" name="year" id="year"
                                                autocomplete="year"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                <option value="">...</option>
                                                <option v-for="year in years" :value="year.name"
                                                    :key="year">{{ year . name }}</option>
                                            </select>
                                            <p class="mt-2 text-sm text-red-500" v-if="errors.year">
                                                {{ errors . year }}</p>
                                        </div>
                                    </div>

                                    <div class="sm:col-span-6">
                                        <div
                                            class="align-middle min-w-full overflow-x-auto shadow overflow-hidden sm:rounded-lg">

                                            <!-- student list table -->
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th scope="col"
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Student
                                                        </th>
                                                        <th scope="col"
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Written
                                                        </th>
                                                        <th scope="col"
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Verbal
                                                        </th>
                                                        <th scope="col"
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Attendance
                                                        </th>
                                                        <th scope="col"
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Activity
                                                        </th>
                                                        <th scope="col"
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Homework
                                                        </th>
                                                        <th scope="col"
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Evaluation
                                                        </th>
                                                        <th scope="col"
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Total
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    <tr v-for="(student, index) in form.students" :key="index">

                                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                                            {{ student . name }} {{ student . father_name }}
                                                            <input type="" v-model="form.student_id[index]">
                                                        </td>

                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <input id="written" v-model="form.written[index]"
                                                                name="written" type="text"
                                                                autocomplete="written"
                                                                placeholder="Written"
                                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                                        </td>
                                                        
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <input id="verbal" v-model="form.verbal[index]"
                                                                name="verbal" type="text"
                                                                autocomplete="verbal"
                                                                placeholder="Verbal"
                                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <input id="attendance" v-model="form.attendance[index]"
                                                                name="attendance" type="text"
                                                                autocomplete="attendance"
                                                                placeholder="Attendance"
                                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                                        </td>

                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <input id="activity" v-model="form.activity[index]"
                                                                name="activity" type="text"
                                                                autocomplete="activity"
                                                                placeholder="Activity"
                                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                                        </td>

                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <input id="homework" v-model="form.homework[index]"
                                                                name="homework" type="text"
                                                                autocomplete="homework"
                                                                placeholder="Homework"
                                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <input id="evaluation" v-model="form.evaluation[index]"
                                                                name="evaluation" type="text"
                                                                autocomplete="evaluation"
                                                                placeholder="Evaluation"
                                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <input id="total" v-model="form.total[index]"
                                                                name="total" type="text"
                                                                autocomplete="total"
                                                                placeholder="Total"
                                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit button -->
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <button type="button" class="text-sm font-semibold leading-6 text-gray-900">Cancel</button>
                            <button type="submit"
                                class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Pagination -->
    </AuthenticatedLayout>
</template>
<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import Swal from 'sweetalert2'
    import {
        Inertia
    } from '@inertiajs/inertia';
    import {
        Switch
    } from '@headlessui/vue';
    import {
        computed,
        defineComponent,
        reactive,
        ref
    } from 'vue'
    import {
        Head,
        Link
    } from '@inertiajs/vue3';
    import {
        router
    } from '@inertiajs/vue3';
    import debounce from 'lodash.debounce'
    import {
        watch
    } from 'vue';
    import axios from 'axios';

    const props = defineProps(['grades', 'errors', 'years', 'teachers', 'subjects']);

    const form = reactive({
        students: Object,
        student_id: [],
        written: [],
        verbal: [],
        attendance: [],
        activity: [],
        homework: [],
        evaluation: [],
        total: [],
        grade_id: '',
        teacher_id: '',
        year: '',
        subject_id: '',
    });

    function selectFile($event) {
        form.file = $event.target.files[0];
    }

    function submit() {
        router.post(route(`student-score.store`), form, {
            forceFormData: true,
            onFinish: (res) => {

                Swal.fire(`Created`,
                    `Students have been successfully created.`)
            },
        })
    }

    function getStudents() {
        if (form.year && form.subject_id && form.grade_id) {

            axios.post(route('get-students'), {
                grade_id: form.grade_id,
                subject_id: form.subject_id,
                year: form.year,
            }).then(response => {
                // Handle the response
                form.students = response.data.data;
            }).catch(error => {
                // Handle any errors
                console.log(error);
            });
        }
    };
</script>
