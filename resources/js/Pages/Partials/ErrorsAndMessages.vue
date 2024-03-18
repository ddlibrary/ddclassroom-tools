<template>
    <div class="text-sm font-medium text-green-800 rounded-md bg-green-50 p-4 m-2" v-if="$page.props">
        {{$page.props}}
    </div>
</template>
<script>
export default {
    name: "ErrorsAndMessages",
    props: ["errors"]
}
</script>