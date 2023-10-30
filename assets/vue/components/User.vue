<template>
    <div class="card w-100 mt-2">
        <div class="card-body">
            {{ firstName }} / {{ lastName }}  / {{ email }}
        </div>
    </div>
</template>

<script>
export default {
    name: 'User',
    props: {
        firstName: {
            type: String,
            required: true,
        },
        lastName: {
            type: String,
            required: true,
        },
        email: {
            type: String,
            required: true,
        },
    }
};
</script>
