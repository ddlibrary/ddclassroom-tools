<template>

    <Head title="Create student's shoqa" />

    <AuthenticatedLayout>
        <!-- Student List -->
        <div class="mb-2">

            <p>You can create student's attendace shoqa by attendance log</p>
            <div class="space-y-9 divide-y divide-gray-200">
                <form @submit.prevent="submit">
                    <div
                        class="mt-6  grid xs:grid-cols-2 gap-y-8 sm:grid-cols-6 md:grid-cols-6 sm:gap-x-6 lg:grid-cols-10 xl:gap-x-8 mr-2">
                        <div class="sm:col-span-2">
                            <div class="mt-1 rounded-md shadow-sm flex">
                                <select name="name" v-model="form.year"
                                    class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                                    <option value=""> Select Year </option>
                                    <option v-for="year in years" :value="year.name" :key="year">
                                        {{ year . name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <div class="mt-1 rounded-md shadow-sm flex">
                                <select name="name" id="grade" v-model="form.sub_grade_id"
                                    class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                                    <option value=""> Select Grade </option>
                                    <option v-for="grade in subGrades" :value="grade.id" :key="grade">
                                        {{ grade . full_name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <div class="mt-1 rounded-md shadow-sm flex">
                                <select name="name" v-model="form.type"
                                    class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                                    <option value="">Select Exam</option>
                                    <option v-for="examType in examTypes" :value="examType.id" :key="examType">
                                        {{ examType . name }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="sm:col-span-2" v-if="form.type && form.year && form.sub_grade_id">
                            <div class="mt-1 rounded-md shadow-sm flex">
                                <button type="submit"
                                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Generate</button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
        <div class="align-middle min-w-full overflow-x-auto shadow overflow-hidden sm:rounded-lg">

        </div>

    </AuthenticatedLayout>
</template>
<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import Swal from 'sweetalert2'
    import {
        Menu,
        MenuButton,
        MenuItem,
        MenuItems
    } from '@headlessui/vue'
    import {
        ChevronDownIcon
    } from '@heroicons/vue/20/solid'
    import {
        router
    } from '@inertiajs/vue3';
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
    import debounce from 'lodash.debounce'
    import {
        watch
    } from 'vue';
    import axios from 'axios';

    const props = defineProps(['examTypes', 'subGrades', 'years']);


    const form = reactive({
        year: '',
        sub_grade_id: '',
        type: '',
    });

    function submit() {

        router.post(route(`general-attendance-score`), form, {
            forceFormData: true,
            onFinish: () => {
                Swal.fire(`Created`,
                    `Attendance has been successfully created.`)
            },
        })
    }
</script>
