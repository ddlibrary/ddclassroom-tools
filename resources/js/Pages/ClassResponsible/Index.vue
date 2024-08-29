<template>

    <Head title="Grade List" />

    <AuthenticatedLayout>
        <!-- Grade List -->
        <div class="align-middle min-w-full p-2 bg-gray-50 overflow-x-auto shadow overflow-hidden sm:rounded-lg">

            <div class="grid grid-cols-1 gap-4 items-start lg:grid-cols-3 lg:gap-8">

                <div class="grid grid-cols-1 gap-4 lg:col-span-2 ml-2">

                    <!-- Searching -->
                    <div class="flow-root">
                        <div class="space-y-9 divide-y divide-gray-200">
                            <div
                                class="mt-6  grid xs:grid-cols-2 gap-y-8 sm:grid-cols-6 md:grid-cols-6 sm:gap-x-6 lg:grid-cols-10 xl:gap-x-8 mr-2">

                                <div class="sm:col-span-3">
                                    <div class="mt-1 rounded-md shadow-sm flex">
                                        <select name="name" v-model="teacherId" @change="getData()"
                                            class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                                            <option value="">...Select Teacher...</option>
                                            <option v-for="teacher in teachers" :value="teacher.id" :key="teacher">
                                                {{ teacher . name }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="sm:col-span-3">
                                    <div class="mt-1 rounded-md shadow-sm flex">
                                        <select name="name" v-model="subGradeId" @change="getData()"
                                            class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                                            <option value="">...Select Class...</option>
                                            <option v-for="subGrade in subGrades" :value="subGrade.id" :key="subGrade">
                                                {{ subGrade . name }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="sm:col-span-3">
                                    <div class="mt-1 rounded-md shadow-sm flex">
                                        <select name="name" v-model="yearId" @change="getData()"
                                            class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                                            <option value="">...Select Year...</option>
                                            <option v-for="year in years" :value="year.name" :key="year">
                                                {{ year . name }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- result List -->
                    <div class="flex flex-col mt-2">
                        <div class="align-middle min-w-full overflow-x-auto shadow overflow-hidden sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th
                                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Teacher
                                        </th>

                                        <th
                                            class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Year
                                        </th>
                                        <th
                                            class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Class
                                        </th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Edit</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr class="bg-white" v-for="classResponsible in classResponsibles.data" :key="classResponsible">
                                        <td class="px-6 py-4 text-left whitespace-nowrap text-sm text-gray-500">
                                            <span @click="edit(classResponsible)" class="text-gray-900 font-medium"
                                                v-text="classResponsible.teacher.name"> </span>
                                        </td>
                                        <td class="px-6 py-4 text-left whitespace-nowrap text-sm text-gray-500">
                                            {{ classResponsible . year }}
                                        </td>
                                        <td class="px-6 py-4 text-left whitespace-nowrap text-sm text-gray-500">
                                            {{ classResponsible . sub_grade.full_name }}
                                        </td>

                                        <!-- Edit -->
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <span @click="edit(classResponsible)" class="text-indigo-600 hover:text-indigo-900">
                                                Edit </span>
                                            <span class="text-red-700 cursor-pointer ml-2"
                                                @click="deleteItem(classResponsible.id)">
                                                Delete
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <no-record-found v-if="classResponsibles.data.length == 0"></no-record-found>
                        <!-- Pagination -->
                        <div v-if="classResponsibles.links.length > 3" class="mt-2">
                            <div class="flex flex-wrap -mb-1">
                                <template v-for="(link, p) in classResponsibles.links" :key="p">
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
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 mr-2 mt-2">

                    <!-- Create new subGrade -->
                    <section aria-labelledby="announcements-title">
                        <div class="rounded-lg bg-white overflow-hidden shadow">
                            <div class="p-6">
                                <div class="flow-roots">
                                    <form class="space-y-8 divide-y divide-gray-200" @submit.prevent="submit">
                                        <div class="space-y-8 divide-y divide-gray-200">
                                            <div>
                                                <div>
                                                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                                                        <span v-if="form.id">
                                                            Update class responsible
                                                        </span>
                                                        <span v-else>
                                                            Create new class responsible
                                                        </span>
                                                    </h3>
                                                </div>

                                                <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">

                                                    <!-- Class -->
                                                    <div class="sm:col-span-6">
                                                        <label for="sub_grade_id"
                                                            class="block text-sm font-medium text-gray-700">
                                                            Class
                                                        </label>
                                                        <div class="mt-1 flex rounded-md shadow-sm">
                                                            <select v-model="form.sub_grade_id" name="sub_grade_id"
                                                                id="sub_grade_id"
                                                                class="flex-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                                                                <option value="">...</option>
                                                                <option v-for="grade in subGrades" :value="grade.id"
                                                                    :key="grade">{{ grade . name }}</option>
                                                            </select>
                                                        </div>
                                                        <p class="mt-2 text-sm text-red-500" v-if="errors.sub_grade_id">
                                                            {{ errors . sub_grade_id }}
                                                        </p>
                                                    </div>

                                                    <!-- Teacher -->
                                                    <div class="sm:col-span-6">
                                                        <label for="teacher_id"
                                                            class="block text-sm font-medium text-gray-700">
                                                            Teacher
                                                        </label>
                                                        <div class="mt-1 flex rounded-md shadow-sm">
                                                            <select v-model="form.teacher_id" name="teacher_id"
                                                                id="teacher_id"
                                                                class="flex-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                                                                <option value="">...</option>
                                                                <option v-for="teacher in teachers" :value="teacher.id"
                                                                    :key="teacher">{{ teacher . name }}</option>
                                                            </select>
                                                        </div>
                                                        <p class="mt-2 text-sm text-red-500" v-if="errors.teacher_id">
                                                            {{ errors . teacher_id }}
                                                        </p>
                                                    </div>

                                                    <!-- Year -->
                                                    <div class="sm:col-span-6">
                                                        <label for="year"
                                                            class="block text-sm font-medium text-gray-700">
                                                            Year
                                                        </label>
                                                        <div class="mt-1 flex rounded-md shadow-sm">
                                                            <select type="text" placeholder="Name"
                                                                v-model="form.year" name="grade" id="grade"
                                                                autocomplete="grade"
                                                                class="flex-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                                                                <option value="">...</option>
                                                                <option v-for="year in years" :value="year.name"
                                                                    :key="year">{{ year . name }}</option>
                                                            </select>
                                                        </div>
                                                        <p class="mt-2 text-sm text-red-500" v-if="errors.year">
                                                            {{ errors . year }}
                                                        </p>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <!-- Submit button -->
                                        <div class="pt-5">
                                            <div class="flex justify-end">
                                                <button type="button" @click="resetForm()"
                                                    class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    Cancel
                                                </button>

                                                <button v-if="form.id == null" type="submit" :disabled="isEnabled"
                                                    class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                    :class="isEnabled ? 'bg-indigo-200 hover:bg-indigo-200' :
                                                        'bg-indigo-600 hover:bg-indigo-700'">
                                                    Save
                                                </button>
                                                <button v-else type="submit" :disabled="isEnabled"
                                                    class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                    :class="isEnabled ? 'bg-indigo-200 hover:bg-indigo-200' :
                                                        'bg-indigo-600 hover:bg-indigo-700'">
                                                    Update
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import ErrorsAndMessages from "./../Partials/ErrorsAndMessages.vue";
    import NoRecordFound from "./../Partials/NoRecordFound.vue";
    import Swal from 'sweetalert2'
    import {
        Inertia
    } from '@inertiajs/inertia';
    import {
        computed,
        defineComponent,
        reactive,
        ref
    } from 'vue';
    import {
        Switch
    } from '@headlessui/vue';
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

    const props = defineProps(['classResponsibles', 'subGrades', 'years', 'errors', 'teachers']);

    const enabled = ref(false);
    const isEnabled = ref(false);

    const form = reactive({
        teacher_id: '',
        sub_grade_id: '',
        year: '',
        id: null,
    });

    function deleteItem(id) {
        if (confirm('Are you sure to delete this Grade?')) {
            router.delete(route(`class-responsible.destroy`, {
                'id': id
            }), {
                forceFormData: true,
                onFinish: () => {
                    Swal.fire(`Deleted`,
                        `Grade has been successfully deleted.`)
                },
            });
        }
    }

    let subGradeName = ref(null);
    let yearId = ref('');
    let teacherId = ref('');
    let subGradeId = ref('');

    watch(
        subGradeName,
        debounce(function(value) {
            getData(value)
        }, 500),
    );

    function getData(name = '') {
        router.get(route('class-responsible.index'), {
            name: (name ? name : subGradeName.value),
            year: yearId.value,
            teacher_id: teacherId.value,
            sub_grade_id: subGradeId.value,
        }, {
            preserveState: true,
        });
    }

    const data = reactive({
        'is_active': true,
        'id': null
    });

    const searchForm = reactive({
        year_id: '',
    });


    // Submit the form
    function submit() {
        router.post(route('class-responsible.store'), form, {
            forceFormData: true,
            onStart: () => (isEnabled.value = true),
            onFinish: () => {
                isEnabled.value = false;
                let error = props.errors;
                if (!error.grade_id && !error.year && !error.location) {
                    resetForm();
                    Swal.fire(`Wow`,
                        `Grade successfully ${form.id ? 'updated.' : 'added.'}`)
                }
            },
        });
    }

    // Reset the form
    function resetForm() {
        form.id = null;
        form.sub_grade_id = '';
        form.year = '';
        form.teacher_id = '';
    }

    // Edit
    function edit(classResponsible) {
        form.id = classResponsible.id
        form.sub_grade_id = classResponsible.sub_grade_id;
        form.year = classResponsible.year;
        form.teacher_id = classResponsible.teacher_id;
    }
</script>
