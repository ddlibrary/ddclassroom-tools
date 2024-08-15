<template>
    <Head title="Create Student" />
    <AuthenticatedLayout>
        <div class="py-4 bg-sky-30 rounded-lg border">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow-sm sm:rounded-lg">
                    <!-- Create Student -->
                    <form @submit.prevent="submit">
                        <div class="space-y-12">
                            <div class="border-b border-gray-900/10 pb-12">
                                <h2
                                    class="text-base font-semibold leading-7 text-gray-900"
                                >
                                    Create Score
                                </h2>
                                <p class="mt-1 text-sm leading-6 text-gray-600">
                                    You can create student's scores.
                                </p>

                                <div
                                    class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6"
                                >
                                    <!-- Grade -->
                                    <div class="sm:col-span-1">
                                        <label
                                            for="grade_id"
                                            class="block text-sm font-medium leading-6 text-gray-900"
                                            >Grade
                                        </label>
                                        <div class="mt-2">
                                            <select
                                                v-model="form.grade_id"
                                                @change="getStudents()"
                                                name="grade"
                                                id="grade"
                                                autocomplete="grade"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                            >
                                                <option value="">...</option>
                                                <option
                                                    v-for="grade in grades"
                                                    :value="grade.id"
                                                    :key="grade"
                                                >
                                                    {{ grade.full_name }}
                                                </option>
                                            </select>
                                            <p
                                                class="mt-2 text-sm text-red-500"
                                                v-if="errors.grade_id"
                                            >
                                                {{ errors.grade_id }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="sm:col-span-1">
                                        <label
                                            for="subject_id"
                                            class="block text-sm font-medium leading-6 text-gray-900"
                                            >Subject
                                        </label>
                                        <div class="mt-2">
                                            <select
                                                v-model="form.subject_id"
                                                @change="getStudents()"
                                                name="subject"
                                                id="subject"
                                                autocomplete="subject"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                            >
                                                <option value="">...</option>
                                                <option
                                                    v-for="subject in subjects"
                                                    :value="subject.id"
                                                    :key="subject"
                                                >
                                                    {{ subject.name }}
                                                </option>
                                            </select>
                                            <p
                                                class="mt-2 text-sm text-red-500"
                                                v-if="errors.subject_id"
                                            >
                                                {{ errors.subject_id }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="sm:col-span-1">
                                        <label
                                            for="grade_id"
                                            class="block text-sm font-medium leading-6 text-gray-900"
                                            >Teacher
                                        </label>
                                        <div class="mt-2">
                                            <select
                                                v-model="form.teacher_id"
                                                name="teacher"
                                                id="teacher"
                                                autocomplete="teacher"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                            >
                                                <option value="">...</option>
                                                <option
                                                    v-for="teacher in teachers"
                                                    :value="teacher.id"
                                                    :key="teacher"
                                                >
                                                    {{ teacher.name }}
                                                </option>
                                            </select>
                                            <p
                                                class="mt-2 text-sm text-red-500"
                                                v-if="errors.teacher_id"
                                            >
                                                {{ errors.teacher_id }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Year -->
                                    <div class="sm:col-span-1">
                                        <label
                                            for="year"
                                            class="block text-sm font-medium leading-6 text-gray-900"
                                            >Year
                                        </label>
                                        <div class="mt-2">
                                            <select
                                                v-model="form.year"
                                                @change="getStudents()"
                                                name="year"
                                                id="year"
                                                autocomplete="year"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                            >
                                                <option value="">...</option>
                                                <option
                                                    v-for="year in years"
                                                    :value="year.name"
                                                    :key="year"
                                                >
                                                    {{ year.name }}
                                                </option>
                                            </select>
                                            <p
                                                class="mt-2 text-sm text-red-500"
                                                v-if="errors.year"
                                            >
                                                {{ errors.year }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <label
                                            for="subject_id"
                                            class="block text-sm font-medium leading-6 text-gray-900"
                                            >Exam Type
                                        </label>
                                        <div class="mt-2">
                                            <select
                                                name="name"
                                                @change="getStudents()"
                                                v-model="form.type_id"
                                                class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300"
                                            >
                                                <option value="">
                                                    Select Exam
                                                </option>
                                                <option
                                                    v-for="type in types"
                                                    :value="type.id"
                                                    :key="type"
                                                >
                                                    {{ type.name }}
                                                </option>
                                            </select>
                                            <p
                                                class="mt-2 text-sm text-red-500"
                                                v-if="errors.type"
                                            >
                                                {{ errors.type }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <div
                                            class="mt-1 rounded-md shadow-sm flex"
                                        ></div>
                                    </div>

                                    <div class="sm:col-span-6">
                                        <div
                                            class="align-middle min-w-full overflow-x-auto shadow overflow-hidden sm:rounded-lg"
                                        >
                                            <!-- student list table -->
                                            <table
                                                class="min-w-full divide-y divide-gray-200"
                                            >
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th
                                                            scope="col"
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                                        >
                                                            Student
                                                        </th>
                                                        <th
                                                            scope="col"
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap"
                                                        >
                                                            Moodle ID
                                                        </th>
                                                        <th
                                                            scope="col"
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                                        >
                                                            Written
                                                        </th>
                                                        <th
                                                            scope="col"
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                                        >
                                                            Oral
                                                        </th>
                                                        <th
                                                            scope="col"
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                                        >
                                                            Attendance
                                                        </th>
                                                        <th
                                                            scope="col"
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                                        >
                                                            Activity
                                                        </th>
                                                        <th
                                                            scope="col"
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                                        >
                                                            Homework
                                                        </th>
                                                        <th
                                                            scope="col"
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                                        >
                                                            Evaluation
                                                        </th>
                                                        <th
                                                            scope="col"
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                                        >
                                                            Total
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody
                                                    class="bg-white divide-y divide-gray-200"
                                                >
                                                    <tr
                                                        v-for="(
                                                            student, index
                                                        ) in form.students"
                                                        :key="index"
                                                    >
                                                        <td
                                                            class="px-6 py-4 text-left"
                                                        >
                                                            {{
                                                                student.fa_name
                                                            }}
                                                            {{
                                                                student.fa_father_name
                                                            }}

                                                        </td>
                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap"
                                                        >
                                                            {{
                                                                student.id_number
                                                            }}
                                                        </td>
                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap"
                                                        >
                                                            <input
                                                                @input="
                                                                    updateStudentScores(
                                                                        $event,
                                                                        index
                                                                    )
                                                                "
                                                                id="written"
                                                                v-model="
                                                                    form
                                                                        .written[
                                                                        index
                                                                    ]
                                                                "
                                                                name="written"
                                                                type="number"
                                                                autocomplete="written"
                                                                :placeholder="'0 - '+ scoreLimits.written"
                                                                step="0.01"
                                                                :max="scoreLimits.written"
                                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                                            />
                                                            <p class="mt-2 text-sm text-red-500" v-if="errors.written && errors.written+'.'+[index]">
                                                {{ errors . written+'.'+[index] }}</p>
                                                        </td>

                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap"
                                                        >
                                                            <input
                                                                @input="
                                                                    updateStudentScores(
                                                                        $event,
                                                                        index
                                                                    )
                                                                "
                                                                id="oral"
                                                                v-model="
                                                                    form.oral[
                                                                        index
                                                                    ]
                                                                "
                                                                name="oral"
                                                                type="number"
                                                                autocomplete="oral"
                                                                :placeholder="'0 - '+ scoreLimits.oral"
                                                                step="0.01"
                                                                :max="scoreLimits.oral"
                                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                                            />
                                                        </td>
                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap"
                                                        >
                                                            <input
                                                                id="attendance"
                                                                v-model="
                                                                    form
                                                                        .attendance[
                                                                        index
                                                                    ]
                                                                "
                                                                @input="
                                                                    updateStudentScores(
                                                                        $event,
                                                                        index
                                                                    )
                                                                "
                                                                name="attendance"
                                                                type="number"
                                                                autocomplete="attendance"
                                                                :placeholder="'0 - '+ scoreLimits.attendance"
                                                                step="0.01"
                                                                :max="scoreLimits.attendance"
                                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                                            />
                                                        </td>

                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap"
                                                        >
                                                            <input
                                                                id="activity"
                                                                v-model="
                                                                    form
                                                                        .activity[
                                                                        index
                                                                    ]
                                                                "
                                                                @input="
                                                                    updateStudentScores(
                                                                        $event,
                                                                        index
                                                                    )
                                                                "
                                                                name="activity"
                                                                type="number"
                                                                autocomplete="activity"
                                                                :placeholder="'0 - '+ scoreLimits.activity"
                                                                step="0.01"
                                                                :max="scoreLimits.activity"
                                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                                            />
                                                        </td>

                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap"
                                                        >
                                                            <input
                                                                id="homework"
                                                                v-model="
                                                                    form
                                                                        .homework[
                                                                        index
                                                                    ]
                                                                "
                                                                @input="
                                                                    updateStudentScores(
                                                                        $event,
                                                                        index
                                                                    )
                                                                "
                                                                name="homework"
                                                                type="number"
                                                                autocomplete="homework"
                                                                :placeholder="'0 - '+ scoreLimits.homework"
                                                                step="0.01"
                                                                :max="scoreLimits.homework"
                                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                                            />
                                                        </td>
                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap"
                                                        >
                                                            <input
                                                                id="evaluation"
                                                                v-model="
                                                                    form
                                                                        .evaluation[
                                                                        index
                                                                    ]
                                                                "
                                                                @input="
                                                                    updateStudentScores(
                                                                        $event,
                                                                        index
                                                                    )
                                                                "
                                                                name="evaluation"
                                                                type="number"
                                                                step="0.01"
                                                                :max="scoreLimits.evaluation"
                                                                autocomplete="evaluation"
                                                                :placeholder="'0 - '+ scoreLimits.evaluation"
                                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                                            />
                                                        </td>
                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap"
                                                        >
                                                            <input
                                                                id="total"
                                                                v-model="
                                                                    form.total[
                                                                        index
                                                                    ]
                                                                "
                                                                readonly
                                                                name="total"
                                                                type="number"
                                                                step="0.01"
                                                                :max="scoreLimits.total"
                                                                autocomplete="total"
                                                                :placeholder="'0 - '+ scoreLimits.total"
                                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                                            />
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit button -->
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <button
                                type="button"
                                class="text-sm font-semibold leading-6 text-gray-900"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                            >
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Pagination -->
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Swal from "sweetalert2";
import { Inertia } from "@inertiajs/inertia";
import { Switch } from "@headlessui/vue";
import { computed, defineComponent, reactive, ref } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import { router } from "@inertiajs/vue3";
import debounce from "lodash.debounce";
import { watch } from "vue";
import axios from "axios";

const props = defineProps([
    "grades",
    "errors",
    "types",
    "years",
    "teachers",
    "subjects",
    "midtermScores",
    "finalScores",
]);

const form = reactive({
    students: Object,
    student_id: [],
    written: [],
    oral: [],
    attendance: [],
    activity: [],
    homework: [],
    evaluation: [],
    total: [],
    grade_id: "",
    teacher_id: "",
    year: "",
    subject_id: "",
    type_id: "",
});

const scoreLimits = ref([]);

function selectFile($event) {
    form.file = $event.target.files[0];
}

function submit() {
    form.student_id = form.students.map(s => s.id)
    router.post(route(`student-score.store`), form, {
        forceFormData: true,
        onFinish: (res) => {
            Swal.fire(`Created`, `Students have been successfully created.`);
        },
    });
}

function getStudents() {
    if (form.year && form.subject_id && form.grade_id && form.type_id) {
        scoreLimits.value = form.type_id == 1 ? props.midtermScores: props.finalScores;
        axios
            .post(route("get-students"), {
                grade_id: form.grade_id,
                subject_id: form.subject_id,
                year: form.year,
            })
            .then((response) => {
                // Handle the response
                form.students = response.data.data;
            })
            .catch((error) => {
                // Handle any errors
                console.log(error);
            });
    }else{
        form.students = {}
    }
}

function validateInput(event, index) {
    const inputValue = event.target.value.trim();
    const inputName = event.target.name.trim();

    const scores = form.type_id === 1 ? props.midtermScores : props.finalScores;
    const max = scores[inputName];

    if (inputValue !== "" && (isNaN(inputValue) || inputValue > max)) {
        alert(`Please enter a number between ${0} and ${max}.`);
        form[inputName][index] = "";

        return false;
    }
    return true;
}

function updateStudentScores(event, index) {
    if (!validateInput(event, index)) return;

    form.total[index] = Number(form.written[index] || 0) + Number(form.oral[index] || 0) + Number(form.attendance[index] || 0) + Number(form.evaluation[index] || 0) + Number(form.homework[index] || 0) + Number(form.activity[index] || 0);
}
</script>
