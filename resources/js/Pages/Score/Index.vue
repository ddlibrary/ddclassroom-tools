<template>

    <Head title="Student List" />

    <AuthenticatedLayout>
        <!-- Student List -->
        <div class="mb-2">
            <Menu as="div" class="relative inline-block text-left float-right">
                <div>
                    <MenuButton
                        class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                        Options
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

                            <Link href="/student-result/create/multiple"
                                :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">
                            Upload Student Score</Link>
                            </MenuItem>
                            <MenuItem v-slot="{ active }">
                            <Link href="/student-score/create-scores"
                                :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">
                            Create Scores</Link>
                            </MenuItem>
                            <MenuItem v-slot="{ active }">
                            <Link href="/student-score/create"
                                :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">
                            Create</Link>
                            </MenuItem>
                            <hr>

                            <MenuItem v-slot="{ active }" v-if="form.grade_id && form.year">
                            <p @click="exportResultCard()"
                                :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm cursor-pointer']">
                                Export Student Result</p>
                            </MenuItem>

                            <MenuItem v-slot="{ active }" v-if="form.grade_id && form.year">
                            <Link download="download"
                                :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">
                            Export Student Result in Details</Link>
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
                            <input name="name" v-model="form.search" id="search" autocomplete="search"
                                placeholder="Search"
                                class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <div class="mt-1 rounded-md shadow-sm flex">
                            <select name="name" v-model="form.year"
                                class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                                <option value="">...</option>
                                <option v-for="year in years" :value="year.name" :key="year">
                                    {{ year . name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <div class="mt-1 rounded-md shadow-sm flex">
                            <select name="name" v-model="form.subject_id"
                                class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                                <option value="">...</option>
                                <option v-for="subject in subjects" :value="subject.id" :key="subject">
                                    {{ subject . name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <div class="mt-1 rounded-md shadow-sm flex">
                            <select name="name" v-model="form.grade_id"
                                class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                                <option value="">...</option>
                                <option v-for="grade in grades" :value="grade.id" :key="grade">
                                    {{ grade . full_name }}</option>
                            </select>
                        </div>
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
                            Grade
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Subject
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Year
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total Score
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Result
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Exam
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Result
                        </th>

                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Edit</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="(score, index) in scores.data" :key="score.email" :class="score.type == 3 ? 'bg-green-200' : ''">

                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ index + 1 }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ score . student . fa_name }} - {{ score . student . fa_father_name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ score . sub_grade . name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ score . subject . name }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ score . year }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ score . total }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{  score.is_passed ? 'کامیاب' : 'ناکام' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span v-if="score.type != 3">
                                {{ score.type == 1 ? 'چهارنیم ماهه' : 'سالانه' }}
                            </span>
                            <span v-else>
                                نتیجه نهایی
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <Link :href="'/student-score/' + score.id + '/edit'"  v-if="score.type != 3"
                                class="text-indigo-600 mr-2 hover:text-indigo-900">
                                Edit </Link>

                        </td>
                    </tr>
                </tbody>
            </table>


        </div>
        <no-record-found v-if="scores.data.length == 0"></no-record-found>
        <!-- Pagination -->
        <div v-if="scores.links.length > 3" class="mt-2">
            <div class="flex flex-wrap -mb-1">
                <template v-for="(link, p) in scores.links" :key="p">
                    <div v-if="link.url === null"
                        class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded"
                        v-html="link.label" />
                    <Link v-else
                        class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-white focus:border-indigo-500 focus:text-indigo-500"
                        :class="{ 'bg-blue-700 text-white': link.active }" :href="link.url"
                        v-html="link.label" />
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

    defineProps(['scores', 'errors', 'grades', 'subjects', 'years']);

    function submit(id) {
        if (confirm('Are you sure to delete this student?')) {
            router.delete(route(`students.destroy`, {
                'id': id
            }));
        }
    }

    function exportResultCard() {

        axios({
                url: '/get-student-result-as-excel?year='+form.year+'&grade_id='+form.grade_id,
                method: 'get',
                responseType: 'blob', // Important
            })
            .then(response => {
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', `student-result-from-${form.year}.xlsx`);
                document.body.appendChild(link);
                link.click();
            })
            .catch(error => {
                // Handle the error, e.g., show an error message
            });
   // },
    }

    const form = reactive({
        search: null,
        year: '',
        grade_id: '',
        subject_id: '',
    });

    watch(form, debounce(() => {
        router.get(route('student-score.index'), form, {
            preserveState: true,
        });
    }, 300));
</script>
