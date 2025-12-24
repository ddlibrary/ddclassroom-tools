<template>
    <Head title="Sub Grade Subject Semesters" />

    <AuthenticatedLayout>
        <div class="mb-2">
            <Link href="/sub-grade-subject-semesters/create"
                class="pointer-events-auto float-right mb-2 rounded-md bg-indigo-600 px-3 py-2 text-[0.8125rem] font-semibold leading-5 text-white hover:bg-indigo-500">
                Assign Subjects to Semester
            </Link>
            <div class="space-y-9 divide-y divide-gray-200">
                <div
                    class="mt-6 grid xs:grid-cols-2 gap-y-8 sm:grid-cols-6 md:grid-cols-6 sm:gap-x-6 lg:grid-cols-10 xl:gap-x-8 mr-2">
                    <div class="sm:col-span-3">
                        <select v-model="filters.sub_grade_id" @change="getData()"
                            class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                            <option value="">All Sub Grades</option>
                            <option v-for="subGrade in subGrades" :key="subGrade.id" :value="subGrade.id">
                                {{ subGrade.full_name }}
                            </option>
                        </select>
                    </div>
                    <div class="sm:col-span-3">
                        <select v-model="filters.semester" @change="getData()"
                            class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                            <option value="">All Semesters</option>
                            <option value="1">First Semester</option>
                            <option value="2">Second Semester</option>
                        </select>
                    </div>
                    <div class="sm:col-span-3">
                        <select v-model="filters.year" @change="getData()"
                            class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                            <option value="">All Years</option>
                            <option v-for="year in years" :key="year.id" :value="year.name">
                                {{ year.name }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="align-middle min-w-full overflow-x-auto shadow overflow-hidden sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Sub Grade
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Subject
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Semester
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Year
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="item in subGradeSubjectSemesters.data" :key="item.id">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ item.sub_grade?.full_name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ item.subject?.name }} ({{ item.subject?.en_name }})
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ item.semester == 1 ? 'First Semester' : 'Second Semester' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ item.year }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <span class="text-red-700 cursor-pointer" @click="deleteItem(item.id)">
                                Delete
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <no-record-found v-if="subGradeSubjectSemesters.data.length == 0"></no-record-found>

        <!-- Pagination -->
        <div v-if="subGradeSubjectSemesters.links.length > 3" class="mt-2">
            <div class="flex flex-wrap -mb-1">
                <template v-for="(link, p) in subGradeSubjectSemesters.links" :key="p">
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
import NoRecordFound from "./../Partials/NoRecordFound.vue";
import { router } from '@inertiajs/vue3';
import { Head, Link } from '@inertiajs/vue3';
import { reactive } from 'vue';
import Swal from 'sweetalert2';

const props = defineProps(['subGradeSubjectSemesters', 'subGrades', 'subjects', 'years']);

const filters = reactive({
    sub_grade_id: '',
    semester: '',
    year: '',
});

function getData() {
    router.get('/sub-grade-subject-semesters', filters, {
        preserveState: true,
    });
}

function deleteItem(id) {
    if (confirm('Are you sure you want to remove this subject assignment?')) {
        router.delete(`/sub-grade-subject-semesters/${id}`, {
            onFinish: () => {
                Swal.fire('Deleted', 'Subject assignment removed successfully.');
            },
        });
    }
}
</script>

