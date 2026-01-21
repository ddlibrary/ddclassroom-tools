<template>
    <Head :title="`All Students Subject Scores Report - ${examTypeLabel}`" />

    <AuthenticatedLayout>
        <!-- All Students Subject Scores Report -->
        <div class="mb-2">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">All Students Subject Scores Report - {{ examTypeLabel }}</h1>
                <a :href="exportUrl" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export to Excel
                </a>
            </div>

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
                        <div class="mt-1">
                            <multiselect
                                v-model="form.grade_id"
                                :options="grades"
                                :multiple="true"
                                :close-on-select="false"
                                :clear-on-select="false"
                                :preserve-search="true"
                                placeholder="Select Grades"
                                label="name"
                                track-by="id"
                                :searchable="true"
                                @update:modelValue="onGradeChange"
                            >
                                <template #noOptions>
                                    <span>No grades found</span>
                                </template>
                            </multiselect>
                        </div>
                    </div>

                    <!-- Sub Grade Filter -->
                    <div class="sm:col-span-2">
                        <label for="sub_grade_id" class="block text-sm font-medium text-gray-700 mb-1">Sub Grade</label>
                        <div class="mt-1">
                            <multiselect
                                v-model="form.sub_grade_id"
                                :options="filteredSubGrades"
                                :multiple="true"
                                :close-on-select="false"
                                :clear-on-select="false"
                                :preserve-search="true"
                                placeholder="Select Sub Grades"
                                label="full_name"
                                track-by="id"
                                :searchable="true"
                            >
                                <template #noOptions>
                                    <span>No sub grades found</span>
                                </template>
                            </multiselect>
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

                    <!-- Exam Type Filter -->
                    <div class="sm:col-span-2">
                        <label for="exam_type" class="block text-sm font-medium text-gray-700 mb-1">Exam Type <span class="text-red-500">*</span></label>
                        <div class="mt-1 rounded-md shadow-sm flex">
                            <select
                                name="exam_type"
                                v-model="form.exam_type"
                                class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                                <option value="midterm">Midterm</option>
                                <option value="final">Final</option>
                                <option value="result">Final Result</option>
                            </select>
                        </div>
                    </div>

                    <!-- Search Button -->
                    <div class="sm:col-span-2 flex items-end">
                        <button
                            @click="search"
                            type="button"
                            class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Search
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Result Statistics Summary -->
        <div class="mb-4 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">کامیاب (Passed)</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ totalKamyab }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">مشروط (Conditional)</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ totalMashroot }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">ناکام (Failed)</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ totalNakam }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Students</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ totalStudents }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Table -->
        <div class="mb-4">
            <div class="align-middle min-w-full overflow-x-auto shadow overflow-hidden sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50 z-10">
                                NO
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-12 bg-gray-50 z-10">
                                Name
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-48 bg-gray-50 z-10">
                                Father Name
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-72 bg-gray-50 z-10">
                                English Name
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-96 bg-gray-50 z-10">
                                English Father Name
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-[28rem] bg-gray-50 z-10">
                                Moodle ID
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-[32rem] bg-gray-50 z-10">
                                Email
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-[36rem] bg-gray-50 z-10">
                                Grade
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-[40rem] bg-gray-50 z-10">
                                Result Status
                            </th>
                            <th v-for="subject in subjects" :key="subject.id" scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-l border-gray-300">
                                <div class="whitespace-nowrap">
                                    {{ subject.en_name || subject.name }}
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="(result, index) in results.data" :key="result.student.id">
                            <td class="px-6 py-4 whitespace-nowrap sticky left-0 bg-white z-10">
                                {{ index + 1 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap sticky left-12 bg-white z-10">
                                {{ result.student?.fa_name ? result.student.fa_name : result.student?.name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap sticky left-48 bg-white z-10">
                                {{ result.student?.fa_father_name ? result.student.fa_father_name : result.student?.father_name || '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap sticky left-72 bg-white z-10">
                                {{ result.student?.name || '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap sticky left-96 bg-white z-10">
                                {{ result.student?.father_name || '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap sticky left-[28rem] bg-white z-10">
                                {{ result.student?.id_number || '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap sticky left-[32rem] bg-white z-10">
                                {{ result.student?.email || '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap sticky left-[36rem] bg-white z-10">
                                {{ result.student?.sub_grade?.full_name || '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap sticky left-[40rem] bg-white z-10">
                                <span :class="getResultStatusClass(result.result_status)">
                                    {{ result.result_status || '-' }}
                                </span>
                            </td>
                            <td v-for="subjectScore in result.subjects" :key="subjectScore.subject_id" class="px-3 py-4 text-center border-l border-gray-200">
                                <div :class="subjectScore.score ? (subjectScore.score.is_passed ? 'text-green-600 font-semibold' : 'text-red-600') : 'text-gray-300'">
                                    {{ subjectScore.score ? formatScore(subjectScore.score.total) : '-' }}
                                </div>
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
import Multiselect from 'vue-multiselect';
import { router } from '@inertiajs/vue3';
import { reactive, computed, onMounted, watch } from 'vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps(['results', 'grades', 'subGrades', 'years', 'countries', 'subjects', 'examType','totalKamyab', 'totalMashroot','totalNakam']);

const examTypeLabel = computed(() => {
    const labels = {
        'midterm': 'Midterm',
        'final': 'Final',
        'result': 'Final Result'
    };
    return labels[form.exam_type] || 'Final Result';
});

const totalStudents = computed(() => {
    return (props.totalKamyab || 0) + (props.totalMashroot || 0) + (props.totalNakam || 0);
});

// Format score to remove trailing zeros
const formatScore = (score) => {
    if (score === null || score === undefined) return '-';
    const num = parseFloat(score);
    if (isNaN(num)) return score;
    // Remove trailing zeros
    return num % 1 === 0 ? num.toString() : num.toString();
};

// Get CSS class for result status
const getResultStatusClass = (status) => {
    if (!status) return 'text-gray-500';

    const statusLower = status.toLowerCase();
    if (statusLower.includes('کامیاب') || statusLower.includes('passed') || statusLower.includes('success')) {
        return 'text-green-600 font-semibold';
    } else if (statusLower.includes('ناکام') || statusLower.includes('failed') || statusLower.includes('repeat')) {
        return 'text-red-600 font-semibold';
    } else if (statusLower.includes('مشروط') || statusLower.includes('conditional') || statusLower.includes('trayagain')) {
        return 'text-yellow-600 font-semibold';
    }
    return 'text-gray-600';
};

const exportUrl = computed(() => {
    const params = new URLSearchParams();
    if (form.student_id) params.append('student_id', form.student_id);
    if (form.email) params.append('email', form.email);
    if (form.country_id) params.append('country_id', form.country_id);
    if (form.grade_id && form.grade_id.length > 0) {
        const gradeIds = Array.isArray(form.grade_id) ? form.grade_id.map(g => g.id || g) : [form.grade_id];
        gradeIds.forEach(id => params.append('grade_id[]', id));
    }
    if (form.sub_grade_id && form.sub_grade_id.length > 0) {
        const subGradeIds = Array.isArray(form.sub_grade_id) ? form.sub_grade_id.map(sg => sg.id || sg) : [form.sub_grade_id];
        subGradeIds.forEach(id => params.append('sub_grade_id[]', id));
    }
    if (form.year) params.append('year', form.year);
    if (form.exam_type) params.append('exam_type', form.exam_type);
    return route('reports.all-students-subject-scores.export') + '?' + params.toString();
});

// Get query parameters from URL
const getQueryParams = () => {
    const urlParams = new URLSearchParams(window.location.search);
    const gradeIds = urlParams.getAll('grade_id[]').length > 0
        ? urlParams.getAll('grade_id[]').map(id => props.grades.find(g => g.id == id)).filter(Boolean)
        : (urlParams.get('grade_id') ? [props.grades.find(g => g.id == urlParams.get('grade_id'))].filter(Boolean) : []);
    const subGradeIds = urlParams.getAll('sub_grade_id[]').length > 0
        ? urlParams.getAll('sub_grade_id[]').map(id => props.subGrades.find(sg => sg.id == id)).filter(Boolean)
        : (urlParams.get('sub_grade_id') ? [props.subGrades.find(sg => sg.id == urlParams.get('sub_grade_id'))].filter(Boolean) : []);
    const currentYear = new Date().getFullYear().toString();
    return {
        student_id: urlParams.get('student_id') || '',
        email: urlParams.get('email') || '',
        country_id: urlParams.get('country_id') || '',
        grade_id: gradeIds,
        sub_grade_id: subGradeIds,
        year: urlParams.get('year') || currentYear,
        exam_type: urlParams.get('exam_type') || 'result',
    };
};

const currentYear = new Date().getFullYear().toString();

const form = reactive({
    student_id: '',
    email: '',
    country_id: '',
    grade_id: [],
    sub_grade_id: [],
    year: currentYear,
    exam_type: 'result',
});

// Initialize form with URL query parameters on mount
let isInitialLoad = true;

onMounted(() => {
    const params = getQueryParams();
    Object.assign(form, params);
    // Allow auto-search after initial load
    setTimeout(() => {
        isInitialLoad = false;
    }, 100);
});

// Filter sub grades based on selected grades
const filteredSubGrades = computed(() => {
    if (!form.grade_id || form.grade_id.length === 0) {
        return props.subGrades;
    }
    const selectedGradeIds = form.grade_id.map(g => g.id || g);
    return props.subGrades.filter(subGrade => selectedGradeIds.includes(subGrade.grade_id));
});

// Watch for grade changes and filter sub_grade_id if needed
const onGradeChange = () => {
    if (form.grade_id && form.grade_id.length > 0) {
        const selectedGradeIds = form.grade_id.map(g => g.id || g);
        // Filter out sub grades that don't belong to selected grades
        form.sub_grade_id = form.sub_grade_id.filter(sg => {
            const subGradeId = sg.id || sg;
            const subGrade = props.subGrades.find(s => s.id == subGradeId);
            return subGrade && selectedGradeIds.includes(subGrade.grade_id);
        });
    }
};

// Function to handle pagination clicks
const goToPage = (url) => {
    if (!url) return;

    const urlObj = new URL(url, window.location.origin);
    const params = {};

    urlObj.searchParams.forEach((value, key) => {
        params[key] = value;
    });

        const finalParams = {
            ...params,
            student_id: form.student_id,
            email: form.email,
            country_id: form.country_id,
            grade_id: form.grade_id && form.grade_id.length > 0 ? form.grade_id.map(g => g.id || g) : [],
            sub_grade_id: form.sub_grade_id && form.sub_grade_id.length > 0 ? form.sub_grade_id.map(sg => sg.id || sg) : [],
            year: form.year,
            exam_type: form.exam_type,
            page: params.page || 1
        };

    router.get(route('reports.all-students-subject-scores'), finalParams, {
        preserveState: true,
        preserveScroll: true,
    });
};

// Search function to fetch data when button is clicked
const search = () => {
    const params = {
        student_id: form.student_id,
        email: form.email,
        country_id: form.country_id,
        grade_id: form.grade_id && form.grade_id.length > 0 ? form.grade_id.map(g => g.id || g) : [],
        sub_grade_id: form.sub_grade_id && form.sub_grade_id.length > 0 ? form.sub_grade_id.map(sg => sg.id || sg) : [],
        year: form.year,
        exam_type: form.exam_type
    };
    router.get(route('reports.all-students-subject-scores'), params, {
        preserveState: true,
        preserveScroll: true,
    });
};


</script>

