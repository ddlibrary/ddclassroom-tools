<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    code: '',
});

const submit = () => {
    form.post('two-factor-challenge');
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="code" value="Code" />

                <TextInput
                    id="code"
                    class="mt-1 block w-full"
                    v-model="form.code"
                    placeholder="Please enter your 6-digit code."
                    required
                    autofocus
                    autocomplete="code"
                />

                <InputError class="mt-2" :message="form.errors.code" />
            </div>


            <div class="flex items-center justify-between mt-4">
                <Link href="two-factor-challenge-backup-code" class="left">Use backup code</Link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Send
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
