<template>
    <Head title="Filter Students" />

    <AuthenticatedLayout>
        <!-- Filter Students -->
        <div class="mb-2">
            <h1 class="text-2xl font-bold mb-4">Filter Students</h1>

            <div class="space-y-9 divide-y divide-gray-200 mb-2">
                <div class="mt-6 grid xs:grid-cols-2 gap-y-8 sm:grid-cols-6 md:grid-cols-6 sm:gap-x-6 lg:grid-cols-12 xl:gap-x-8 mr-2">
                    <!-- Student ID Filter -->
                    <div class="sm:col-span-2">
                        <label for="student_id" class="block text-sm font-medium text-gray-700 mb-1">Student ID</label>
                        <div class="mt-1 rounded-md shadow-sm flex">
                            <input
                                name="student_id"
                                v-model="form.student_id"
                                id="student_id"
                                autocomplete="student_id"
                                placeholder="Enter Student ID"
                                class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                        </div>
                    </div>

                    <!-- Email Filter -->
                    <div class="sm:col-span-2">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <div class="mt-1 rounded-md shadow-sm flex">
                            <input
                                name="email"
                                v-model="form.email"
                                id="email"
                                autocomplete="email"
                                placeholder="Enter Email"
                                class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                        </div>
                    </div>

                    <!-- Country Filter -->
                    <div class="sm:col-span-2">
                        <label for="country_id" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                        <div class="mt-1 rounded-md shadow-sm flex">
                            <select
                                name="country_id"
                                v-model="form.country_id"
                                class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                                <option value="">All Countries</option>
                                <option v-for="country in countries" :value="country.id" :key="country.id">
                                    {{ country.name }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Grade Filter -->
                    <div class="sm:col-span-2">
                        <label for="grade_id" class="block text-sm font-medium text-gray-700 mb-1">Grade</label>
                        <div class="mt-1 rounded-md shadow-sm flex">
                            <select
                                name="grade_id"
                                v-model="form.grade_id"
                                @change="onGradeChange"
                                class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                                <option value="">All Grades</option>
                                <option v-for="grade in grades" :value="grade.id" :key="grade.id">{{ grade.name }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Sub Grade Filter -->
                    <div class="sm:col-span-2">
                        <label for="sub_grade_id" class="block text-sm font-medium text-gray-700 mb-1">Sub Grade</label>
                        <div class="mt-1 rounded-md shadow-sm flex">
                            <select
                                name="sub_grade_id"
                                v-model="form.sub_grade_id"
                                class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                                <option value="">All Sub Grades</option>
                                <option v-for="subGrade in filteredSubGrades" :value="subGrade.id" :key="subGrade.id">
                                    {{ subGrade.full_name }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Year Filter -->
                    <div class="sm:col-span-2">
                        <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                        <div class="mt-1 rounded-md shadow-sm flex">
                            <select
                                name="year"
                                v-model="form.year"
                                class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                                <option value="">All Years</option>
                                <option v-for="year in years" :value="year.name" :key="year.id">{{ year.name }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Subject Filter -->
                    <div class="sm:col-span-2">
                        <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                        <div class="mt-1 rounded-md shadow-sm flex">
                            <select
                                name="subject_id"
                                v-model="form.subject_id"
                                class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                                <option value="">All Subjects</option>
                                <option v-for="subject in subjects" :value="subject.id" :key="subject.id">
                                    {{ subject.name }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Exam Type Filter -->
                    <div class="sm:col-span-2">
                        <label for="exam_type" class="block text-sm font-medium text-gray-700 mb-1">Exam Type</label>
                        <div class="mt-1 rounded-md shadow-sm flex">
                            <select
                                name="exam_type"
                                v-model="form.exam_type"
                                class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                                <option value="midterm">Midterm (Type 1)</option>
                                <option value="final">Final Exam (Type 2)</option>
                                <option value="result">Final Result (Type 3)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Status Filter (Passed/Failed) - Only show when subject is selected -->
                    <div v-if="form.subject_id" class="sm:col-span-2">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <div class="mt-1 rounded-md shadow-sm flex">
                            <select
                                name="status"
                                v-model="form.status"
                                class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                                <option value="">All</option>
                                <option value="passed">Passed</option>
                                <option value="failed">Failed</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Table - Show Score Details when Subject is Selected -->
        <div v-if="form.subject_id" class="mb-4">
            <!-- Type Legend and Statistics -->
            <div class="bg-blue-50 border border-blue-200 rounded-md p-3 mb-4">
                <div class="flex flex-wrap justify-between items-center mb-3">
                    <div>
                        <p class="text-sm font-semibold text-gray-700 mb-2">Score Type Reference:</p>
                        <div class="flex flex-wrap gap-4 text-sm">
                            <span class="text-blue-600 font-medium">1 = Midterm</span>
                            <span class="text-green-600 font-medium">2 = Final Exam</span>
                            <span class="text-purple-600 font-medium">3 = Final Result (Combined)</span>
                        </div>
                    </div>
                    <div class="flex gap-6 mt-2">
                        <div class="text-center">
                            <p class="text-xs text-gray-600 mb-1">Total Passed</p>
                            <p class="text-2xl font-bold text-green-600">{{ passedCount || 0 }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-600 mb-1">Total Failed</p>
                            <p class="text-2xl font-bold text-red-600">{{ failedCount || 0 }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-600 mb-1">Total Students</p>
                            <p class="text-2xl font-bold text-gray-700">{{ (passedCount || 0) + (failedCount || 0) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="align-middle min-w-full overflow-x-auto shadow overflow-hidden sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            NO
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Father Name
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Subject
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Grade
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Year
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Type
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Written
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Verbal
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Attendance
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Activity
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Homework
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Evaluation
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="(score, index) in results.data" :key="score.id">
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ index + 1 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ score.student?.fa_name ? score.student.fa_name : score.student?.name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ score.student?.fa_father_name ? score.student.fa_father_name : score.student?.father_name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ score.subject?.name || '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ score.sub_grade?.full_name || '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ score.year }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <!-- type=1 => midterm -->
                            <span v-if="score.type == 1" class="text-blue-600 font-semibold">Midterm</span>
                            <!-- type=2 => final exam -->
                            <span v-else-if="score.type == 2" class="text-green-600 font-semibold">Final</span>
                            <!-- type=3 => final result (combination of midterm and final exam) -->
                            <span v-else-if="score.type == 3" class="text-purple-600 font-semibold">Result</span>
                            <span v-else>-</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ score.written || '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ score.verbal || '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ score.attendance || '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ score.activity || '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ score.homework || '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ score.evaluation || '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap font-semibold">
                            {{ score.total || '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span :class="score.is_passed ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold'">
                                {{ score.is_passed ? 'Passed' : 'Failed' }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
            </div>
        </div>

        <div v-else class="mb-4">
            <!-- Statistics for Student Results -->
            <div class="bg-blue-50 border border-blue-200 rounded-md p-3 mb-4">
                <div class="flex flex-wrap justify-center items-center gap-6 mb-4">
                    <div class="text-center">
                        <p class="text-xs text-gray-600 mb-1">کامیاب (Successful)</p>
                        <p class="text-2xl font-bold text-green-600">{{ kamyabCount || 0 }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs text-gray-600 mb-1">ناکام (Failed)</p>
                        <p class="text-2xl font-bold text-red-600">{{ nakamCount || 0 }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs text-gray-600 mb-1">مشروط (Conditional)</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ mashrootCount || 0 }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs text-gray-600 mb-1">Total Students</p>
                        <p class="text-2xl font-bold text-gray-700">{{ (kamyabCount || 0) + (nakamCount || 0) + (mashrootCount || 0) }}</p>
                    </div>
                </div>
            </div>

            <div class="align-middle min-w-full overflow-x-auto shadow overflow-hidden sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            NO
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Father Name
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Grade
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Year
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total Score
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Result
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="(result, index) in results.data" :key="result.id">
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ index + 1 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ result.student?.fa_name ? result.student.fa_name : result.student?.name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ result.student?.fa_father_name ? result.student.fa_father_name : result.student?.father_name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ result.sub_grade?.full_name || '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ result.year }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ result.total }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ result.result_name ? result.result_name : result.middle_result_name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a
                                v-if="result.student?.uuid"
                                :href="'/student-result-card/' + result.student.uuid + '/' + result.year + '/' + result.id"
                                target="_blank"
                                class="text-indigo-600 mr-2 hover:text-indigo-900">
                                Result Card
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
            </div>
        </div>

        <no-record-found v-if="results.data.length == 0"></no-record-found>

        <!-- Pagination -->
        <div v-if="results.links.length > 3" class="mt-2">
            <div class="flex flex-wrap -mb-1">
                <template v-for="(link, p) in results.links" :key="p">
                    <div v-if="link.url === null"
                        class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded"
                        v-html="link.label" />
                    <button v-else
                        @click="goToPage(link.url)"
                        class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-white focus:border-indigo-500 focus:text-indigo-500"
                        :class="{ 'bg-blue-700 text-white': link.active }"
                        v-html="link.label" />
                </template>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NoRecordFound from "./../Partials/NoRecordFound.vue";
import { router, usePage } from '@inertiajs/vue3';
import { reactive, computed, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import debounce from 'lodash.debounce';
import { watch } from 'vue';

const props = defineProps(['results', 'grades', 'subGrades', 'years', 'countries', 'subjects', 'passedCount', 'failedCount', 'kamyabCount', 'nakamCount', 'mashrootCount']);

const getQueryParams = () => {
    const urlParams = new URLSearchParams(window.location.search);
    return {
        student_id: urlParams.get('student_id') || '',
        email: urlParams.get('email') || '',
        country_id: urlParams.get('country_id') || '',
        grade_id: urlParams.get('grade_id') || '',
        sub_grade_id: urlParams.get('sub_grade_id') || '',
        year: urlParams.get('year') || '',
        subject_id: urlParams.get('subject_id') || '',
        exam_type: urlParams.get('exam_type') || 'result',
        status: urlParams.get('status') || ''
    };
};

const form = reactive({
    student_id: '',
    email: '',
    country_id: '',
    grade_id: '',
    sub_grade_id: '',
    year: '',
    subject_id: '',
    exam_type: 'result',
    status: ''
});

let isInitialLoad = true;

onMounted(() => {
    const params = getQueryParams();
    Object.assign(form, params);
    setTimeout(() => {
        isInitialLoad = false;
    }, 100);
});

const filteredSubGrades = computed(() => {
    if (!form.grade_id) {
        return props.subGrades;
    }
    return props.subGrades.filter(subGrade => subGrade.grade_id == form.grade_id);
});

const onGradeChange = () => {
    if (form.grade_id) {
        const selectedSubGrade = props.subGrades.find(sg => sg.id == form.sub_grade_id);
        if (selectedSubGrade && selectedSubGrade.grade_id != form.grade_id) {
            form.sub_grade_id = '';
        }
    }
};

const goToPage = (url) => {
    if (!url) return;

    const urlObj = new URL(url, window.location.origin);
    const params = {};

    urlObj.searchParams.forEach((value, key) => {
        params[key] = value;
    });

    const finalParams = {
        ...params,
        ...form,
        page: params.page || 1
    };

    router.get(route('students.filter'), finalParams, {
        preserveState: true,
        preserveScroll: true,
    });
};

watch(form, debounce(() => {
    if (isInitialLoad) {
        return;
    }
    router.get(route('students.filter'), form, {
        preserveState: true,
        preserveScroll: true,
    });
}, 300));
</script>

