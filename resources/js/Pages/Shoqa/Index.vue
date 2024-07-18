<template>

    <Head title="Student List" />

    <AuthenticatedLayout>
        <!-- Student List -->
        <div class="mb-2">
            <Menu as="div" v-if="form.grade_id && form.year && form.subject_id && form.type"
                class="relative inline-block text-left float-right">
                <div>
                    <MenuButton
                        class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-3 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                        Get Report
                        <ChevronDownIcon class="-mr-1 h-5 w-5 text-gray-400" aria-hidden="true" />
                    </MenuButton>
                </div>

                <transition enter-active-class="transition ease-out duration-100"
                    enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100"
                    leave-active-class="transition ease-in duration-75"
                    leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                    <MenuItems
                        class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                        <div class="py-1">
                            <MenuItem v-slot="{ active }">
                            <p @click="navigate('score')"
                                :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700',
                                    'block px-4 py-2 text-sm cursor-pointer'
                                ]">
                                Export Shoqa</p>
                            </MenuItem>

                            <MenuItem v-slot="{ active }">
                            <p @click="navigate('attendance')"
                                :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700',
                                    'block px-4 py-2 text-sm cursor-pointer'
                                ]">
                                Export Attendance Shoqa</p>
                            </MenuItem>
                        </div>
                    </MenuItems>
                </transition>
            </Menu>

            <div class="space-y-9 divide-y divide-gray-200">
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
                            <select name="name" id="grade" v-model="form.grade_id"
                                class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                                <option value=""> Select Grade </option>
                                <option v-for="grade in grades" :value="grade.id" :key="grade">
                                    {{ grade . full_name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <div class="mt-1 rounded-md shadow-sm flex">
                            <select name="name" id="subject" v-model="form.subject_id"
                                class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                                <option value=""> Select Subject </option>
                                <option v-for="subject in subjects" :value="subject.id" :key="subject">
                                    {{ subject . name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <div class="mt-1 rounded-md shadow-sm flex">
                            <select name="name" v-model="form.type"
                                class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                                <option value="">Select Exam</option>
                                <option v-for="examType in examTypes" :value="examType.id" :key="examType">{{ examType.name }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="align-middle min-w-full overflow-x-auto shadow overflow-hidden sm:rounded-lg">

        </div>

    </AuthenticatedLayout>
</template>
<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
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

    defineProps(['subjects', 'grades', 'years', 'examTypes']);

    function navigate(type) {
        const params = {
            year: form.year,
            grade_id: form.grade_id,
            subject_id: form.subject_id,
            type: form.type,
            export_type: type,
        };

        fetch(`/get-shoqa-as-excel?${new URLSearchParams(params)}`, {
            method: 'GET',
            headers: {
            'Content-Type': 'application/vnd.ms-excel',
            'Content-Disposition': 'attachment; filename="shoqa_report.xlsx"',
            },
        })
        .then((response) => response.blob())
        .then((blob) => {
            const url = window.URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', 'shoqa_report.xlsx');
            document.body.appendChild(link);
            link.click();
            link.remove();
            window.URL.revokeObjectURL(url);
        })
        .catch((error) => {
            console.error('Error exporting Shoqa report:', error);
        });
    }

    const form = reactive({
        subject_id: '',
        year: '',
        grade_id: '',
        type: '',
    });
</script>
