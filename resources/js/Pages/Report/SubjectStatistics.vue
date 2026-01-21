<template>
    <Head title="Subject Statistics Report" />

    <AuthenticatedLayout>
        <!-- Subject Statistics Report -->
        <div class="mb-2">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">Subject Statistics Report</h1>
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
                        <label for="country_id" class="block text-sm font-medium text-gray-700 mb-1">Country <span class="text-red-500">*</span></label>
                        <div class="mt-1 rounded-md shadow-sm flex">
                            <select
                                name="country_id"
                                v-model="form.country_id"
                                class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                                <option value="">Select Country</option>
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

                    <!-- Exam Type Filter -->
                    <div class="sm:col-span-2">
                        <label for="exam_type" class="block text-sm font-medium text-gray-700 mb-1">Exam Type</label>
                        <div class="mt-1 rounded-md shadow-sm flex">
                            <select
                                name="exam_type"
                                v-model="form.exam_type"
                                class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                                <option value="result">Final Result (Type 3)</option>
                                <option value="midterm">Midterm Exam (Type 1)</option>
                                <option value="final">Final Exam (Type 2)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Summary -->
        <div class="mb-4">
            <div class="bg-blue-50 border border-blue-200 rounded-md p-3 mb-4">
                <div class="flex flex-wrap justify-center items-center gap-6 mb-4">
                    <div class="text-center">
                        <p class="text-xs text-gray-600 mb-1">Total Passed</p>
                        <p class="text-2xl font-bold text-green-600">{{ totalPassed || 0 }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs text-gray-600 mb-1">Total Failed</p>
                        <p class="text-2xl font-bold text-red-600">{{ totalFailed || 0 }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs text-gray-600 mb-1">Total Students</p>
                        <p class="text-2xl font-bold text-gray-700">{{ totalStudents || 0 }}</p>
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
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                #
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Subject Name
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total Students
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Passed
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Failed
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pass Rate (%)
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="(result, index) in results" :key="result.subject_id">
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ index + 1 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium">
                                {{ result.subject_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ result.total_count }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-green-600 font-semibold">
                                {{ result.passed_count }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-red-600 font-semibold">
                                {{ result.failed_count }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="getPassRate(result) >= 50 ? 'text-green-600' : 'text-red-600'">
                                    {{ getPassRate(result).toFixed(1) }}%
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <no-record-found v-if="results.length == 0"></no-record-found>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NoRecordFound from "./../Partials/NoRecordFound.vue";
import { router } from '@inertiajs/vue3';
import { reactive, computed, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';

const exportUrl = computed(() => {
    const params = new URLSearchParams();
    if (form.student_id) params.append('student_id', form.student_id);
    if (form.email) params.append('email', form.email);
    if (form.country_id) params.append('country_id', form.country_id);
    if (form.grade_id) params.append('grade_id', form.grade_id);
    if (form.sub_grade_id) params.append('sub_grade_id', form.sub_grade_id);
    if (form.year) params.append('year', form.year);
    if (form.exam_type) params.append('exam_type', form.exam_type);
    return route('reports.subject-statistics.export') + '?' + params.toString();
});

const props = defineProps(['results', 'grades', 'subGrades', 'years', 'countries', 'subjects', 'totalPassed', 'totalFailed', 'totalStudents']);

// Get query parameters from URL
const getQueryParams = () => {
    const urlParams = new URLSearchParams(window.location.search);
    return {
        student_id: urlParams.get('student_id') || '',
        email: urlParams.get('email') || '',
        country_id: urlParams.get('country_id') || '',
        grade_id: urlParams.get('grade_id') || '',
        sub_grade_id: urlParams.get('sub_grade_id') || '',
        year: urlParams.get('year') || new Date().getFullYear().toString(),
        exam_type: urlParams.get('exam_type') || 'result'
    };
};

const currentYear = new Date().getFullYear().toString();

const form = reactive({
    student_id: '',
    email: '',
    country_id: '',
    grade_id: '',
    sub_grade_id: '',
    year: currentYear,
    exam_type: 'result'
});

// Initialize form with URL query parameters on mount (but don't auto-fetch)
onMounted(() => {
    const params = getQueryParams();
    Object.assign(form, params);
    // Don't auto-fetch data - only fetch when Search button is clicked
});

// Filter sub grades based on selected grade
const filteredSubGrades = computed(() => {
    if (!form.grade_id) {
        return props.subGrades;
    }
    return props.subGrades.filter(subGrade => subGrade.grade_id == form.grade_id);
});

// Watch for grade changes and reset sub_grade_id if needed
const onGradeChange = () => {
    if (form.grade_id) {
        const selectedSubGrade = props.subGrades.find(sg => sg.id == form.sub_grade_id);
        if (selectedSubGrade && selectedSubGrade.grade_id != form.grade_id) {
            form.sub_grade_id = '';
        }
    }
};

// Calculate pass rate percentage
const getPassRate = (result) => {
    if (result.total_count === 0) return 0;
    return (result.passed_count / result.total_count) * 100;
};

// Search function to fetch data when button is clicked
const search = () => {
    if (!form.country_id || !form.year) {
        alert('Please select Country and Year');
        return;
    }
    const params = {
        ...form,
        search: true, // Flag to indicate search was clicked
    };
    router.get(route('reports.subject-statistics'), params, {
        preserveState: true,
        preserveScroll: true,
    });
};
</script>

