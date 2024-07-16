<template>

    <Head title="Student List" />

    <AuthenticatedLayout>
        <!-- Student List -->
        <div class="mb-2">


            <div class="space-y-9 divide-y divide-gray-200">
                <div
                    class="mt-6  grid xs:grid-cols-2 gap-y-8 sm:grid-cols-6 md:grid-cols-6 sm:gap-x-6 lg:grid-cols-10 xl:gap-x-8 mr-2">
                    <div class="sm:col-span-2">
                        <div class="mt-1 rounded-md shadow-sm flex">
                            <input name="search" v-model="form.search" id="search" autocomplete="search"
                            placeholder="Search"
                                class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <div class="mt-1 rounded-md shadow-sm flex">
                            <select name="subject_id" v-model="form.subject_id"
                                class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                                <option value="">...Subject...</option>
                                <option v-for="subject in subjects" :value="subject.id" :key="subject">{{ subject.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <div class="mt-1 rounded-md shadow-sm flex">
                            <select name="year" v-model="form.year"
                                class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                                <option value="">...Year...</option>
                                <option v-for="year in years" :value="year.name" :key="year">{{ year.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <div class="mt-1 rounded-md shadow-sm flex">
                            <select name="month_id" v-model="form.month_id"
                                class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                                <option value="">...Month...</option>
                                <option v-for="month in months" :value="month.id" :key="month">{{ month.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <div class="mt-1 rounded-md shadow-sm flex">
                            <select name="sub_grade_id" v-model="form.sub_grade_id"
                                class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                                <option value="">...Grade...</option>
                                <option v-for="grade in grades" :value="grade.id" :key="grade">{{ grade.full_name }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="sm:col-span-2 right">
                    <div class="mt-1 rounded-md shadow-sm flex">
                        <Link href="/student-attendance-log/create/multiple"
            class="pointer-events-auto float-right mb-2 rounded-md bg-indigo-600 px-3 py-2 text-[0.8125rem] font-semibold leading-5 text-white hover:bg-indigo-500">
        Upload Attendance
        </Link>
                    </div>
                </div>
            </div>
        </div>
        <div class="align-middle min-w-full overflow-x-auto shadow overflow-hidden sm:rounded-lg">

            <!-- student list table -->
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            NO
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Name
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Father Name
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Moodle ID
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total Hours
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Present
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Absent
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Late
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Excused
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Absent %
                        </th>

                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="(student, index) in students.data" :key="student">

                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ index + 1 }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ student.fa_name }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ student.fa_father_name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ student.id_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ student.attendance_logs_count }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ student.present_count }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ student.absent_count }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ student.late_count }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ student.excused_count }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap"
                        :class="Math.floor(student.absent_count * 100 / student.attendance_logs_count)>=20 ? 'text-red-600': ''"
                        >

                            {{ Math.floor(student.absent_count * 100 / student.attendance_logs_count) }}%
                        </td>

                    </tr>
                </tbody>
            </table>


        </div>
        <no-record-found v-if="students.data.length == 0"></no-record-found>
        <!-- Pagination -->
        <div v-if="students.links.length > 3" class="mt-2">
            <div class="flex flex-wrap -mb-1">
                <template v-for="(link, p) in students.links" :key="p">
                    <div v-if="link.url === null"
                        class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded"
                        v-html="link.label" />
                    <Link v-else
                        class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-white focus:border-indigo-500 focus:text-indigo-500"
                        :class="{ 'bg-blue-700 text-white': link.active }" :href="link.url" v-html="link.label" />
                </template>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import ErrorsAndMessages from "./../Partials/ErrorsAndMessages.vue";
    import NoRecordFound from "./../Partials/NoRecordFound.vue";
    import Pagination from "@/Components/Pagination.vue";
    import {
        router
    } from '@inertiajs/vue3';
    import {
        computed,
        defineComponent,
        reactive, ref
    } from 'vue'
    import {
        Head,
        Link
    } from '@inertiajs/vue3';
    import debounce from 'lodash.debounce'
    import {
        watch
    } from 'vue';

    defineProps(['students', 'errors', 'years', 'grades', 'months', 'subjects']);

    function submit(id) {
        if (confirm('Are you sure to delete this student?')) {
            router.delete(route(`students.destroy`, {
                'id': id
            }));
        }
    }


    const form = reactive({
        search : null,
        type: '',
        year : '',
        sub_grade_id : '',
        month_id : '',
        subject_id : '',
    });

    watch(form, debounce(() => {
        router.get(route('student-attendance-log.students-attendance-log-reports'), form, {
            preserveState: true,
        });
    }, 300));
</script>
