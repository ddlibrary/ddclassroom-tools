<template>
    <Head title="Grade 9 Report" />

    <AuthenticatedLayout>
        <div class="mb-2">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">Grade 9 Report (صنف نهم)</h1>
                <a :href="exportUrl" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export to Excel
                </a>
            </div>

            <!-- Filter Form -->
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

                    <!-- Sub Grade Filter -->
                    <div class="sm:col-span-2">
                        <label for="sub_grade_id" class="block text-sm font-medium text-gray-700 mb-1">Sub Grade</label>
                        <div class="mt-1">
                            <multiselect
                                v-model="form.sub_grade_id"
                                :options="subGrades"
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

                    <!-- Subject Filter -->
                    <div class="sm:col-span-2">
                        <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                        <div class="mt-1 rounded-md shadow-sm flex">
                            <select
                                name="subject_id"
                                v-model="form.subject_id"
                                class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                                <option value="">All Subjects</option>
                                <option v-for="subject in subjects" :value="subject.id" :key="subject.id">{{ subject.name }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Result Filter -->
                    <div class="sm:col-span-2">
                        <label for="result_status" class="block text-sm font-medium text-gray-700 mb-1">Result</label>
                        <div class="mt-1 rounded-md shadow-sm flex">
                            <select
                                name="result_status"
                                v-model="form.result_status"
                                class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                                <option value="">All</option>
                                <option value="passed">کامیاب (Passed)</option>
                                <option value="failed">ناکام (Failed)</option>
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

        <!-- Statistics Summary -->
        <div class="mb-4">
            <div class="bg-blue-50 border border-blue-200 rounded-md p-3 mb-4">
                <div class="flex flex-wrap justify-center items-center gap-6 mb-4">
                    <div class="text-center">
                        <p class="text-xs text-gray-600 mb-1">کامیاب (Successful)</p>
                        <p class="text-2xl font-bold text-gray-700">{{ totalKamyab || 0 }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs text-gray-600 mb-1">ناکام (Failed)</p>
                        <p class="text-2xl font-bold text-red-600">{{ totalNakam || 0 }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs text-gray-600 mb-1">Total Students</p>
                        <p class="text-2xl font-bold text-gray-700">{{ totalStudents || 0 }}</p>
                    </div>
                </div>
                <div class="text-center text-sm text-gray-600">
                    <p><strong>Note:</strong> Students must score ≥ 50 in all 11 subjects to pass. No conditional status (مشروط) for Grade 9.</p>
                </div>
            </div>
        </div>

        <!-- Results Table -->
        <div class="mb-4">
            <div class="align-middle min-w-full overflow-x-auto shadow overflow-hidden sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50">#</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50">Student Name</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Father Name</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Moodle ID</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sub Grade</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year</th>
                            <th v-for="subject in (subjects || [])" :key="subject.id" scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[80px]">
                                {{ subject.name }}
                            </th>
                            <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Final Result</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="(result, index) in results.data" :key="result.student_result_id">
                            <td class="px-4 py-3 whitespace-nowrap text-sm sticky left-0 bg-white">
                                {{ index + 1 + (results.current_page - 1) * results.per_page }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm sticky left-0 bg-white">
                                {{ result.student?.name }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                {{ result.student?.father_name }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                {{ result.student?.id_number }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                {{ result.student?.email }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                {{ result.sub_grade?.full_name }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                {{ result.year }}
                            </td>
                            <!-- All 11 subjects -->
                            <td v-for="subject in (subjects || [])" :key="subject.id" class="px-3 py-3 text-center text-sm">
                                <template v-if="result.all_subjects && result.all_subjects[subject.id]">
                                    <span :class="result.all_subjects[subject.id].is_passed ? '' : 'text-red-600 font-semibold'" :title="result.all_subjects[subject.id].is_passed ? 'Passed' : 'Failed'">
                                        {{ result.all_subjects[subject.id].score || 0 }}
                                    </span>
                                </template>
                                <span v-else class="text-gray-400">-</span>
                            </td>
                            <!-- Final Result -->
                            <td class="px-4 py-3 text-center">
                                <span :class="result.final_result.is_passed ? 'font-bold' : 'text-red-600 font-bold'">
                                    {{ result.final_result.result_name }}
                                </span>
                                <div class="text-xs text-gray-600 mt-1">
                                    {{ result.final_result.passed_subjects }}/{{ result.final_result.total_subjects }} passed
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
                    <button v-else @click="goToPage(link.url)"
                        class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-white focus:border-indigo-500 focus:text-indigo-500"
                        :class="{ 'bg-blue-700 text-white': link.active }" v-html="link.label" />
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
import { reactive, onMounted, computed } from 'vue';
import { Head } from '@inertiajs/vue3';

const exportUrl = computed(() => {
    const params = new URLSearchParams();
    if (form.student_id) params.append('student_id', form.student_id);
    if (form.email) params.append('email', form.email);
    if (form.country_id) params.append('country_id', form.country_id);
    if (form.sub_grade_id && form.sub_grade_id.length > 0) {
        const subGradeIds = Array.isArray(form.sub_grade_id) ? form.sub_grade_id.map(sg => sg.id || sg) : [form.sub_grade_id];
        subGradeIds.forEach(id => params.append('sub_grade_id[]', id));
    }
    if (form.year) params.append('year', form.year);
    if (form.subject_id) params.append('subject_id', form.subject_id);
    if (form.result_status) params.append('result_status', form.result_status);
    return route('reports.grade-9.export') + '?' + params.toString();
});

const props = defineProps(['results', 'subGrades', 'years', 'countries', 'subjects', 'totalKamyab', 'totalNakam', 'totalStudents']);

// Get query parameters from URL
const getQueryParams = () => {
    const urlParams = new URLSearchParams(window.location.search);
    const subGradeIds = urlParams.getAll('sub_grade_id[]').length > 0
        ? urlParams.getAll('sub_grade_id[]').map(id => props.subGrades?.find(sg => sg.id == id)).filter(Boolean)
        : (urlParams.get('sub_grade_id') ? [props.subGrades?.find(sg => sg.id == urlParams.get('sub_grade_id'))].filter(Boolean) : []);
    return {
        student_id: urlParams.get('student_id') || '',
        email: urlParams.get('email') || '',
        country_id: urlParams.get('country_id') || '',
        sub_grade_id: subGradeIds,
        year: urlParams.get('year') || new Date().getFullYear().toString(),
        subject_id: urlParams.get('subject_id') || '',
        result_status: urlParams.get('result_status') || '',
    };
};

const currentYear = new Date().getFullYear().toString();

const form = reactive({
    student_id: '',
    email: '',
    country_id: '',
    sub_grade_id: [],
    year: currentYear,
    subject_id: '',
    result_status: '',
});

// Initialize form with URL query parameters on mount (but don't auto-fetch)
onMounted(() => {
    const params = getQueryParams();
    Object.assign(form, params);
    // Don't auto-fetch data - only fetch when Search button is clicked
});


// Pagination handler
const goToPage = (url) => {
    if (!url) return;
    const urlObj = new URL(url, window.location.origin);
    const params = {};
    urlObj.searchParams.forEach((value, key) => {
        params[key] = value;
    });
    const finalParams = {
        ...params,
        search: true,
        student_id: form.student_id,
        email: form.email,
        country_id: form.country_id,
        sub_grade_id: form.sub_grade_id && form.sub_grade_id.length > 0 ? form.sub_grade_id.map(sg => sg.id || sg) : [],
        year: form.year,
        subject_id: form.subject_id,
        result_status: form.result_status,
        page: params.page || 1
    };
    router.get(route('reports.grade-9'), finalParams, {
        preserveState: true,
        preserveScroll: true,
    });
};

// Search function to fetch data when button is clicked
const search = () => {
    if (!form.country_id || !form.year) {
        alert('Please select Country and Year');
        return;
    }
    const params = {
        search: true,
        student_id: form.student_id,
        email: form.email,
        country_id: form.country_id,
        sub_grade_id: form.sub_grade_id && form.sub_grade_id.length > 0 ? form.sub_grade_id.map(sg => sg.id || sg) : [],
        year: form.year,
        subject_id: form.subject_id,
        result_status: form.result_status,
    };
    router.get(route('reports.grade-9'), params, {
        preserveState: true,
        preserveScroll: true,
    });
};
</script>

